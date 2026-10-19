<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Surat Masuk', 'description' => 'Surat resmi yang diterima sekolah dari pihak eksternal.'],
            ['name' => 'Surat Keluar', 'description' => 'Surat resmi yang dikirimkan oleh sekolah.'],
            ['name' => 'Akademik', 'description' => 'Dokumen terkait proses belajar mengajar dan penilaian.'],
            ['name' => 'Kesiswaan', 'description' => 'Dokumen terkait data dan kegiatan kesiswaan.'],
            ['name' => 'Kurikulum', 'description' => 'Dokumen kurikulum, silabus, dan RPP.'],
            ['name' => 'Kepegawaian', 'description' => 'Dokumen administrasi guru dan tenaga kependidikan.'],
            ['name' => 'Keuangan', 'description' => 'Dokumen anggaran, laporan, dan bukti transaksi sekolah.'],
            ['name' => 'Sarana & Prasarana', 'description' => 'Dokumen inventaris dan pemeliharaan aset sekolah.'],
            ['name' => 'Administrasi', 'description' => 'Dokumen administrasi umum sekolah.'],
            ['name' => 'Peraturan Sekolah', 'description' => 'Dokumen tata tertib dan kebijakan internal sekolah.'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'slug' => Str::slug($category['name']),
                    'description' => $category['description'],
                ]
            );
        }
    }
}
