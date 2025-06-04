<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ErrorLogger;
use App\Services\FavoriteGifService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class FavoriteGifController extends Controller
{
    protected FavoriteGifService $favoriteGifService;
    protected ErrorLogger $logger;

    public function __construct(FavoriteGifService $favoriteGifService, ErrorLogger $logger)
    {
        $this->favoriteGifService = $favoriteGifService;
        $this->logger             = $logger;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gif_id'  => 'required|string',
            'alias'   => 'required|string',
            'user_id' => 'required|exists:users,id',
        ], [
            'user_id.exists' => 'El usuario especificado no existe.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $favorite = $this->favoriteGifService->storeFavorite($validator->validated());

            return response()->json([
                'success' => true,
                'data'    => $favorite,
            ], 201);
        } catch (RuntimeException $e) {
            $this->logger->log('FavoriteGifController@store', $e, [
                'gif_id'  => $request->input('gif_id'),
                'alias'   => $request->input('alias'),
                'user_id' => $request->input('user_id'),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}