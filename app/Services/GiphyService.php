<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class GiphyService
{
    protected string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.giphy.key');
        $this->baseUrl = 'https://api.giphy.com/v1/gifs/search';
    }

    public function searchGifs(string $query, int $limit = 25, int $offset = 0): array
    {
        $response = Http::get($this->baseUrl, [
            'api_key' => $this->apiKey,
            'q'       => $query,
            'limit'   => $limit,
            'offset'  => $offset,
        ]);

        if ($response->failed()) {
            throw new \Exception('Giphy API error: ' . $response->body());
        }

        return $response->json();
    }

    public function getGifById(string $id): array
    {

        $response = Http::get("https://api.giphy.com/v1/gifs/{$id}", [
            'api_key' => $this->apiKey,
        ]);
        if ($response->failed()) {
            throw new \Exception('Error al consultar el GIF por ID: ' . $response->body());
        }

        return $response->json();
    }
    public function getGifOrFail(string $gifId): array
    {
        $response = Http::get("https://api.giphy.com/v1/gifs/{$gifId}", [
            'api_key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            throw new \Exception('Error al consultar el GIF por ID: ' . $response->body());
        }

        $data = $response->json();

        if (! isset($data['data']) || empty($data['data'])) {
            throw new \Exception("GIF no encontrado en Giphy para el ID: {$gifId}");
        }

        return $data['data'];
    }

}