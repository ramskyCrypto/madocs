<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'Madocs']);
        Setting::updateOrCreate(['key' => 'school_name'], ['value' => 'SMA Negeri 1 Contoh']);
        Setting::updateOrCreate(['key' => 'school_info'], ['value' => 'Jl. Pendidikan No. 1, Purwodadi, Jawa Tengah.']);
        Setting::updateOrCreate(['key' => 'max_upload_mb'], ['value' => '10']);
        Setting::updateOrCreate(['key' => 'allowed_ext'], ['value' => 'pdf,doc,docx,xls,xlsx,ppt,pptx']);
    }
}
