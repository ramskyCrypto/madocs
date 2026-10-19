<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('documents');
    }

    public function test_admin_can_create_document(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post('/documents', [
            'name' => 'Surat Edaran Ujian',
            'category_id' => $category->id,
            'document_date' => now()->format('Y-m-d'),
            'status' => 'aktif',
            'visibility' => 'internal',
            'file' => UploadedFile::fake()->create('surat.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', ['name' => 'Surat Edaran Ujian']);
    }

    public function test_siswa_cannot_upload_document(): void
    {
        $siswa = User::factory()->siswa()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($siswa)->post('/documents', [
            'name' => 'Dokumen Siswa',
            'category_id' => $category->id,
            'document_date' => now()->format('Y-m-d'),
            'status' => 'aktif',
            'visibility' => 'publik',
            'file' => UploadedFile::fake()->create('doc.pdf', 200, 'application/pdf'),
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('documents', ['name' => 'Dokumen Siswa']);
    }

    public function test_upload_rejects_file_too_large(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post('/documents', [
            'name' => 'File Besar',
            'category_id' => $category->id,
            'document_date' => now()->format('Y-m-d'),
            'status' => 'aktif',
            'visibility' => 'internal',
            'file' => UploadedFile::fake()->create('besar.pdf', 11 * 1024, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_upload_rejects_invalid_format(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post('/documents', [
            'name' => 'File Exe',
            'category_id' => $category->id,
            'document_date' => now()->format('Y-m-d'),
            'status' => 'aktif',
            'visibility' => 'internal',
            'file' => UploadedFile::fake()->create('virus.exe', 100),
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_user_without_permission_cannot_delete_document(): void
    {
        $guru = User::factory()->guru()->create();
        $otherGuru = User::factory()->guru()->create();
        $document = Document::factory()->create(['user_id' => $otherGuru->id]);

        $response = $this->actingAs($guru)->delete("/documents/{$document->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('documents', ['id' => $document->id]);
    }

    public function test_document_can_be_downloaded_by_authorized_user(): void
    {
        $admin = User::factory()->admin()->create();
        Storage::disk('documents')->put('sample.pdf', 'dummy-content');

        $document = Document::factory()->create([
            'file_path' => 'sample.pdf',
            'visibility' => 'internal',
        ]);

        $response = $this->actingAs($admin)->get("/documents/{$document->id}/download");

        $response->assertOk();
    }

    public function test_document_search_works(): void
    {
        $admin = User::factory()->admin()->create();
        Document::factory()->create(['name' => 'Laporan Keuangan Triwulan', 'visibility' => 'internal']);
        Document::factory()->create(['name' => 'Silabus Matematika', 'visibility' => 'internal']);

        $response = $this->actingAs($admin)->get('/documents?q=Keuangan');

        $response->assertOk();
        $response->assertSee('Laporan Keuangan Triwulan');
        $response->assertDontSee('Silabus Matematika');
    }
}
