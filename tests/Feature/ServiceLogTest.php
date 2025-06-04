<?php
namespace Tests\Feature;

use App\Models\ServiceLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ServiceLogTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(): User
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        return $user;
    }

    public function test_user_can_fetch_service_logs()
    {
        $this->authenticate();

        ServiceLog::factory()->count(3)->create();

        $response = $this->getJson('/api/logs');

        $response->assertOk()
            ->assertJsonStructure(['data', 'links', 'total', 'current_page']);
    }
}