<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_cannot_be_deleted_when_still_used_by_documents(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        Document::factory()->create(['category_id' => $category->id]);

        $response = $this->actingAs($admin)->delete("/categories/{$category->id}");

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_admin_can_delete_unused_category(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->delete("/categories/{$category->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_guru_cannot_manage_categories(): void
    {
        $guru = User::factory()->guru()->create();

        $response = $this->actingAs($guru)->post('/categories', [
            'name' => 'Kategori Baru',
        ]);

        $response->assertForbidden();
    }
}
