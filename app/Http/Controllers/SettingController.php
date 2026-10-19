<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $this->authorize('manage-settings');

        $settings = [
            'app_name' => Setting::get('app_name', 'Madocs'),
            'school_name' => Setting::get('school_name', 'Sekolah Menengah'),
            'school_info' => Setting::get('school_info', ''),
            'logo_path' => Setting::get('logo_path'),
            'max_upload_mb' => Setting::get('max_upload_mb', config('app.madocs.max_upload_mb')),
            'allowed_ext' => Setting::get('allowed_ext', implode(',', config('app.madocs.allowed_ext'))),
        ];

        return view('settings.index', ['settings' => $settings]);
    }

    public function update(UpdateSettingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Setting::set('app_name', $data['app_name']);
        Setting::set('school_name', $data['school_name']);
        Setting::set('school_info', $data['school_info'] ?? '');
        Setting::set('max_upload_mb', $data['max_upload_mb']);
        Setting::set('allowed_ext', $data['allowed_ext']);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logo', 'public');
            Setting::set('logo_path', $path);
        }

        ActivityLogger::log('edit_setting', 'Memperbarui pengaturan aplikasi Madocs.');

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}
