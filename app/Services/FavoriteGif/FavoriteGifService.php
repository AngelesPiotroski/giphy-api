<?php
namespace App\Services\FavoriteGif;

use App\Models\FavoriteGif;
use Exception;

class FavoriteGifService
{
    protected string $baseUrl = 'https://api.giphy.com/v1/gifs';

    public function __construct(
        protected string $apiKey = ''
    ) {
        $this->apiKey = config('services.giphy.key');
    }
    public function storeFavorite(array $data): FavoriteGif
    {
        try {
            return FavoriteGif::create($data);
        } catch (Exception $e) {
            throw new \RuntimeException('Error al guardar el favorito: ' . $e->getMessage());
        }
    }
    public function findFavoriteGifByIdAndUser(string $favoriteId, int $userId): ?FavoriteGif
    {
        return FavoriteGif::where('id', $favoriteId)
            ->where('user_id', $userId)
            ->first();
    }
}