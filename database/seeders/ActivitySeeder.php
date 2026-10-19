<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $samples = [
            ['action' => 'login', 'description' => 'Berhasil login ke sistem Madocs.'],
            ['action' => 'upload_document', 'description' => 'Mengunggah dokumen baru.'],
            ['action' => 'edit_document', 'description' => 'Mengedit metadata dokumen.'],
            ['action' => 'download_document', 'description' => 'Mengunduh dokumen.'],
            ['action' => 'create_category', 'description' => 'Menambahkan kategori dokumen baru.'],
            ['action' => 'delete_document', 'description' => 'Menghapus dokumen yang sudah tidak relevan.'],
            ['action' => 'logout', 'description' => 'Logout dari sistem Madocs.'],
        ];

        for ($i = 0; $i < 25; $i++) {
            $sample = $samples[array_rand($samples)];

            Activity::create([
                'user_id' => $users->random()->id,
                'action' => $sample['action'],
                'description' => $sample['description'],
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subMinutes($i * 47),
            ]);
        }
    }
}
