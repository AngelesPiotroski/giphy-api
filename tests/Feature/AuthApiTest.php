<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Logging\ErrorLogger;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $mock = Mockery::mock(ErrorLogger::class);
        $mock->shouldReceive('log')->andReturnNull();
        $this->app->instance(ErrorLogger::class, $mock);
    }

    private function authenticate(): User
    {
        $user = User::factory()->create();
        Passport::actingAs($user);
        return $user;
    }

    public function test_user_can_login()
    {
        $password = 'password123';

        $user = User::factory()->create([
            'email' => 'usuario@example.com',
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        if (! $response->isOk()) {
            dump($response->json());
            $this->fail('Error en login. Código: ' . $response->getStatusCode());
        }
        
        $response->assertOk();
    }

    public function test_user_can_logout()
    {
        $this->authenticate();

        $response = $this->postJson('/api/logout');
        if (! $response->isOk()) {
            dump($response->json());
            $this->fail('Error en login. Código: ' . $response->getStatusCode());
        }
        $response->assertOk();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
