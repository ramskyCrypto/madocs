<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        $actions = ['login', 'logout', 'upload_document', 'edit_document', 'download_document', 'create_category'];

        return [
            'user_id' => User::factory(),
            'action' => fake()->randomElement($actions),
            'description' => fake()->sentence(8),
            'ip_address' => fake()->ipv4(),
            'created_at' => fake()->dateTimeBetween('-2 months', 'now'),
        ];
    }
}
