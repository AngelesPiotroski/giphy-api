<?php
namespace Database\Factories;

use App\Models\ServiceLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceLogFactory extends Factory
{
    protected $model = ServiceLog::class;

    public function definition(): array
    {
        return [
            'user_id'       => User::factory(),
            'service'       => $this->faker->word,
            'request_body'  => json_encode(['query' => 'test']),
            'http_code'     => 200,
            'response_body' => json_encode(['status' => 'ok']),
            'ip_address'    => $this->faker->ipv4,
        ];
    }
}