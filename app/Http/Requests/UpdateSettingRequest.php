<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-settings');
    }

    public function rules(): array
    {
        return [
            'app_name' => ['required', 'string', 'max:100'],
            'school_name' => ['required', 'string', 'max:255'],
            'school_info' => ['nullable', 'string', 'max:2000'],
            'max_upload_mb' => ['required', 'integer', 'min:1', 'max:50'],
            'allowed_ext' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
        ];
    }
}
