<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('document'));
    }

    public function rules(): array
    {
        $allowedExt = implode(',', config('app.madocs.allowed_ext'));
        $maxKb = config('app.madocs.max_upload_mb') * 1024;

        return [
            'name' => ['required', 'string', 'max:255'],
            'document_number' => ['nullable', 'string', 'max:100'],
            'category_id' => ['required', 'exists:categories,id'],
            'document_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:aktif,arsip'],
            'visibility' => ['required', 'in:publik,internal,privat'],
            // File bersifat opsional ketika edit — jika tidak dikirim,
            // file lama tetap dipakai (ditangani di controller/service).
            'file' => [
                'nullable',
                'file',
                "mimes:{$allowedExt}",
                "max:{$maxKb}",
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama dokumen wajib diisi.',
            'category_id.required' => 'Kategori wajib dipilih.',
            'document_date.required' => 'Tanggal dokumen wajib diisi.',
            'file.mimes' => 'Format file tidak didukung. Gunakan PDF, DOC, DOCX, XLS, XLSX, PPT, atau PPTX.',
            'file.max' => 'Ukuran file terlalu besar. Maksimal '.config('app.madocs.max_upload_mb').' MB.',
        ];
    }
}
