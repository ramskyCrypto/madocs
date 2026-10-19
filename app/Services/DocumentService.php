<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    protected string $disk = 'documents';

    /**
     * Simpan file baru ke storage dengan nama yang aman (tidak pernah
     * mempercayai nama file asli dari user) dan kembalikan metadata-nya.
     */
    public function storeFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $safeName = Str::uuid()->toString().'.'.$extension;
        $path = $file->storeAs('', $safeName, $this->disk);

        return [
            'file_path' => $path,
            'file_name' => $this->sanitizeOriginalName($file->getClientOriginalName()),
            'file_mime' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'file_extension' => $extension,
        ];
    }

    /**
     * Ganti file dokumen: simpan file baru lebih dulu, baru hapus file lama
     * setelah record berhasil diperbarui, supaya tidak ada window di mana
     * dokumen kehilangan file jika terjadi kegagalan di tengah proses.
     */
    public function replaceFile(Document $document, UploadedFile $newFile): array
    {
        $oldPath = $document->file_path;
        $meta = $this->storeFile($newFile);

        if ($oldPath && Storage::disk($this->disk)->exists($oldPath)) {
            Storage::disk($this->disk)->delete($oldPath);
        }

        return $meta;
    }

    public function deleteFile(Document $document): void
    {
        if ($document->file_path && Storage::disk($this->disk)->exists($document->file_path)) {
            Storage::disk($this->disk)->delete($document->file_path);
        }
    }

    protected function sanitizeOriginalName(string $name): string
    {
        $name = basename($name);
        $name = preg_replace('/[^A-Za-z0-9\.\-_ ]/', '', $name) ?? $name;

        return Str::limit($name, 180, '');
    }
}
