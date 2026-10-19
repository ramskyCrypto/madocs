<?php

namespace App\Http\Requests;

use App\Models\Document;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', Document::class);
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
            'file' => [
                'required',
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
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'document_date.required' => 'Tanggal dokumen wajib diisi.',
            'status.required' => 'Status dokumen wajib dipilih.',
            'file.required' => 'File dokumen wajib diunggah.',
            'file.mimes' => 'Format file tidak didukung. Gunakan PDF, DOC, DOCX, XLS, XLSX, PPT, atau PPTX.',
            'file.max' => 'Ukuran file terlalu besar. Maksimal '.config('app.madocs.max_upload_mb').' MB.',
        ];
    }
}
