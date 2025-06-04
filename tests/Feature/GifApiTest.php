<?php
namespace Tests\Feature;

use App\Models\User;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GifApiTest extends TestCase
{
    private function authenticate(): User
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        return $user;
    }

    public function test_user_can_search_gifs()
    {
        $this->authenticate();

        $response = $this->getJson('/api/gifs/search?query=cat');

        $response->assertOk();
    }

    public function test_user_can_view_a_gif()
    {
        $this->authenticate();

        $response = $this->getJson("/api/gifs/tRoH9EYLs3lok");

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'meta']);
    }
}