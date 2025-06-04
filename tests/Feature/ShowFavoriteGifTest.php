<?php
namespace Tests\Feature;

use App\Models\FavoriteGif;
use App\Models\User;
use App\Services\ErrorLogger;
use App\Services\FavoriteGifService;
use App\Services\GiphyService;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class ShowFavoriteGifTest extends TestCase
{
    public function test_returns_404_if_favorite_not_found()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        DB::table('favorite_gifs')->insert([
            'id'      => 123,
            'gif_id'  => 'irrelevant',
            'user_id' => $user->id,
            'alias'   => 'test',
        ]);
        $favoriteGifService = Mockery::mock(FavoriteGifService::class);
        $favoriteGifService->shouldReceive('findFavoriteGifByIdAndUser')
            ->once()
            ->with('123', $user->id)
            ->andReturn(null);

        $giphyService = Mockery::mock(GiphyService::class);
        $logger       = Mockery::mock(ErrorLogger::class);

        $this->app->instance(FavoriteGifService::class, $favoriteGifService);
        $this->app->instance(GiphyService::class, $giphyService);
        $this->app->instance(ErrorLogger::class, $logger);

        $response = $this->getJson('/api/gifs/favorite/123');

        $response->assertStatus(404);
        $response->assertJson(['error' => 'GIF no encontrado o no pertenece al usuario.']);
    }

    public function test_returns_gif_data_if_favorite_found()
    {
        $user = User::factory()->create();
        Passport::actingAs($user);

        $favorite = new FavoriteGif([
            'id'      => 123,
            'gif_id'  => 'tRoH9EYLs3lok',
            'user_id' => $user->id,
        ]);

        $favoriteGifService = Mockery::mock(FavoriteGifService::class);
        $favoriteGifService->shouldReceive('findFavoriteGifByIdAndUser')
            ->once()
            ->with('123', $user->id)
            ->andReturn($favorite);

        $giphyService = Mockery::mock(GiphyService::class);
        $giphyService->shouldReceive('getGifOrFail')
            ->once()
            ->with('tRoH9EYLs3lok')
            ->andReturn(['id' => 'tRoH9EYLs3lok', 'title' => 'Test GIF']);

        $logger = Mockery::mock(ErrorLogger::class);

        $this->app->instance(FavoriteGifService::class, $favoriteGifService);
        $this->app->instance(GiphyService::class, $giphyService);
        $this->app->instance(ErrorLogger::class, $logger);

        $response = $this->getJson('/api/gifs/favorite/123');

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data'    => [
                'id'    => 'tRoH9EYLs3lok',
                'title' => 'Test GIF',
            ],
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}