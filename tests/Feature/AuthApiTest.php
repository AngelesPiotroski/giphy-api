<?php
namespace Tests\Feature;

use App\Models\User;
use App\Services\ErrorLogger;
use Laravel\Passport\Passport;
use Mockery;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    private function authenticate(): User
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        return $user;
    }

    public function test_user_can_login()
    {
        $password = 'password123';
        $user     = User::factory()->create([
            'email'    => 'usuario@example.com',
            'password' => bcrypt($password),
        ]);

        $this->app->instance(ErrorLogger::class, Mockery::mock(ErrorLogger::class));

        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonStructure(['success', 'token', 'expires', 'user']);
    }

    public function test_user_can_logout()
    {
        $this->authenticate();

        $this->app->instance(ErrorLogger::class, Mockery::mock(ErrorLogger::class));

        $response = $this->postJson('/api/logout');

        $response->assertOk()
            ->assertJson(['success' => true]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}