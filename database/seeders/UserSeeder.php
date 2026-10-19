<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $demoPassword = 'password123';

        $accounts = [
            ['name' => 'Administrator Madocs', 'username' => 'admin', 'email' => 'admin@madocs.sch.id', 'role' => User::ROLE_ADMIN],
            ['name' => 'Dra. Siti Rahayu, M.Pd.', 'username' => 'kepsek', 'email' => 'kepsek@madocs.sch.id', 'role' => User::ROLE_KEPALA_SEKOLAH],
            ['name' => 'Budi Santoso, S.Pd.', 'username' => 'guru', 'email' => 'guru@madocs.sch.id', 'role' => User::ROLE_GURU],
            ['name' => 'Rina Wulandari', 'username' => 'staff', 'email' => 'staff@madocs.sch.id', 'role' => User::ROLE_STAFF],
            ['name' => 'Ahmad Fauzi', 'username' => 'siswa', 'email' => 'siswa@madocs.sch.id', 'role' => User::ROLE_SISWA],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['username' => $account['username']],
                [
                    'name' => $account['name'],
                    'email' => $account['email'],
                    'password' => Hash::make($demoPassword),
                    'role' => $account['role'],
                    'status' => User::STATUS_AKTIF,
                    'email_verified_at' => now(),
                ]
            );
        }

        // Beberapa guru & siswa tambahan untuk data yang lebih realistis.
        User::factory()->guru()->count(4)->create();
        User::factory()->siswa()->count(6)->create();
        User::factory()->staff()->count(2)->create();
        User::factory()->nonaktif()->guru()->count(1)->create();
    }
}
