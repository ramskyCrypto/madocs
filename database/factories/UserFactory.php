<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $name = fake()->name();

        return [
            'name' => $name,
            'username' => Str::slug($name, '').fake()->unique()->numberBetween(1, 9999),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => User::ROLE_GURU,
            'status' => User::STATUS_AKTIF,
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_ADMIN]);
    }

    public function kepalaSekolah(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_KEPALA_SEKOLAH]);
    }

    public function guru(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_GURU]);
    }

    public function staff(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_STAFF]);
    }

    public function siswa(): static
    {
        return $this->state(fn () => ['role' => User::ROLE_SISWA]);
    }

    public function nonaktif(): static
    {
        return $this->state(fn () => ['status' => User::STATUS_NONAKTIF]);
    }
}
