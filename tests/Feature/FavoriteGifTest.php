<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class FavoriteGifTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(): User
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        return $user;
    }
    public function test_user_can_favorite_a_gif()
    {
        $user = $this->authenticate();

        $payload = [
            'gif_id'  => 'abc123',
            'alias'   => 'Mi gif favorito',
            'user_id' => $user->id,
        ];

        $response = $this->postJson('/api/gifs/favorite', $payload);

        $response->assertCreated()
            ->assertJsonStructure(['success', 'data']);
    }
}