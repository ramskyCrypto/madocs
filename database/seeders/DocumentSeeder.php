<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id')->all();
        $uploaders = User::whereIn('role', [User::ROLE_ADMIN, User::ROLE_GURU, User::ROLE_STAFF])->pluck('id')->all();

        $titles = [
            'Surat Undangan Rapat Wali Murid Semester Ganjil',
            'Surat Keputusan Pembagian Tugas Mengajar',
            'Silabus Mata Pelajaran Matematika Kelas X',
            'Rencana Pelaksanaan Pembelajaran Bahasa Indonesia',
            'Data Rekapitulasi Nilai Ujian Tengah Semester',
            'Laporan Kegiatan Ekstrakurikuler Pramuka',
            'Rencana Anggaran Belanja Sekolah Tahun 2026',
            'Berita Acara Serah Terima Inventaris Laboratorium',
            'Surat Edaran Libur Hari Raya',
            'Panduan Tata Tertib Siswa Tahun Ajaran Baru',
            'Formulir Pendaftaran Peserta Didik Baru',
            'Notulen Rapat Koordinasi Guru Bulanan',
            'Sertifikat Akreditasi Sekolah',
            'Daftar Hadir Pelatihan Kurikulum Merdeka',
            'Proposal Kegiatan Perayaan Hari Kemerdekaan',
            'Laporan Keuangan Bulanan Kantin Sekolah',
            'Surat Tugas Pengawas Ujian Akhir Sekolah',
            'Data Inventaris Ruang Kelas',
        ];

        foreach ($titles as $i => $title) {
            Document::factory()->create([
                'name' => $title,
                'category_id' => $categories[array_rand($categories)],
                'user_id' => $uploaders[array_rand($uploaders)],
                'visibility' => match (true) {
                    str_contains($title, 'Edaran') || str_contains($title, 'Tata Tertib') || str_contains($title, 'Pendaftaran') => Document::VISIBILITY_PUBLIK,
                    str_contains($title, 'Keuangan') || str_contains($title, 'Rapat') => Document::VISIBILITY_PRIVAT,
                    default => Document::VISIBILITY_INTERNAL,
                },
            ]);
        }
    }
}
