<?php
namespace App\Http\Controllers\Api\Gifs;

use App\Http\Controllers\Controller;
use App\Services\Logging\ErrorLogger;
use App\Services\FavoriteGif\FavoriteGifService;
use App\Services\Giphy\GiphyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GifController extends Controller
{
    protected GiphyService $giphyService;
    protected FavoriteGifService $favoriteGifService;
    protected ErrorLogger $logger;

    public function __construct(
        GiphyService $giphyService,
        FavoriteGifService $favoriteGifService,
        ErrorLogger $logger
    ) {
        $this->giphyService       = $giphyService;
        $this->favoriteGifService = $favoriteGifService;
        $this->logger             = $logger;
    }

    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'query'  => 'required|string',
            'limit'  => 'sometimes|integer|min:1|max:50',
            'offset' => 'sometimes|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $results = $this->giphyService->searchGifs(
                $request->query('query'),
                $request->query('limit', 25),
                $request->query('offset', 0)
            );

            return response()->json($results, 200);
        } catch (\Throwable $e) {
            $this->logger->log('GifController@search', $e, [
                'query' => $request->query('query'),
            ]);

            return response()->json([
                'error'   => 'Error al buscar GIFs.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id): JsonResponse
    {
        if (! is_string($id) || empty($id)) {
            return response()->json([
                'success' => false,
                'message' => 'El ID del GIF es obligatorio y debe ser texto.',
            ], 422);
        }

        try {
            $gif = $this->giphyService->getGifById($id);

            return response()->json($gif, 200);
        } catch (\Throwable $e) {
            $this->logger->log('GifController@show', $e, [
                'gif_id' => $id,
            ]);

            return response()->json([
                'error'   => 'Error al obtener el GIF.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function showFavorite(string $id): JsonResponse
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|string|exists:favorite_gifs,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ID inválido.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();

            $favorite = $this->favoriteGifService->findFavoriteGifByIdAndUser($id, $user->id);

            if (! $favorite) {
                return response()->json([
                    'error' => 'GIF no encontrado o no pertenece al usuario.',
                ], 404);
            }

            $gifData = $this->giphyService->getGifOrFail($favorite->gif_id);

            return response()->json([
                'success' => true,
                'data'    => $gifData,
            ]);
        } catch (\Throwable $e) {
            $this->logger->log('GifController@showFavorite', $e, [
                'favorite_id' => $id,
                'user_id'     => Auth::id(),
            ]);

            return response()->json([
                'error'   => 'Error al obtener el GIF favorito.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}