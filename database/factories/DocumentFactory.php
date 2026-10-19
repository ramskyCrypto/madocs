<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        $extensions = ['pdf', 'docx', 'xlsx', 'pptx'];
        $ext = fake()->randomElement($extensions);
        $mimes = [
            'pdf' => 'application/pdf',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'name' => ucfirst(fake()->words(4, true)),
            'document_number' => strtoupper(fake()->bothify('###/MADOCS/??/####')),
            'document_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'description' => fake()->paragraph(),
            'file_path' => 'seed/'.Str::uuid().'.'.$ext,
            'file_name' => Str::slug(fake()->words(3, true)).'.'.$ext,
            'file_mime' => $mimes[$ext],
            'file_size' => fake()->numberBetween(50_000, 4_000_000),
            'file_extension' => $ext,
            'status' => fake()->randomElement([Document::STATUS_AKTIF, Document::STATUS_AKTIF, Document::STATUS_ARSIP]),
            'visibility' => fake()->randomElement([Document::VISIBILITY_INTERNAL, Document::VISIBILITY_PUBLIK, Document::VISIBILITY_PRIVAT]),
        ];
    }
}
