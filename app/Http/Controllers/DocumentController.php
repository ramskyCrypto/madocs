<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Document::query()
            ->visibleTo($user)
            ->with(['category:id,name', 'user:id,name'])
            ->search($request->query('q'));

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('format')) {
            $query->where('file_extension', $request->query('format'));
        }

        if ($request->filled('uploader_id')) {
            $query->where('user_id', $request->query('uploader_id'));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('document_date', '>=', $request->query('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('document_date', '<=', $request->query('date_to'));
        }

        $documents = $query->latest()->paginate(10)->withQueryString();

        return view('documents.index', [
            'documents' => $documents,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'uploaders' => User::orderBy('name')->get(['id', 'name']),
            'filters' => $request->only(['q', 'category_id', 'status', 'format', 'uploader_id', 'date_from', 'date_to']),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Document::class);

        return view('documents.create', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $meta = $this->documentService->storeFile($request->file('file'));

        $document = Document::create([
            ...$request->safe()->except('file'),
            ...$meta,
            'user_id' => $request->user()->id,
        ]);

        ActivityLogger::log('upload_document', "Mengunggah dokumen \"{$document->name}\".");

        return redirect()->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil diupload.');
    }

    public function show(Request $request, Document $document): View
    {
        $this->authorize('view', $document);

        $document->load(['category', 'user']);

        return view('documents.show', ['document' => $document]);
    }

    public function edit(Request $request, Document $document): View
    {
        $this->authorize('update', $document);

        return view('documents.edit', [
            'document' => $document,
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $data = $request->safe()->except('file');

        if ($request->hasFile('file')) {
            $meta = $this->documentService->replaceFile($document, $request->file('file'));
            $data = [...$data, ...$meta];
        }

        $document->update($data);

        ActivityLogger::log('edit_document', "Mengedit dokumen \"{$document->name}\".");

        return redirect()->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Request $request, Document $document): RedirectResponse
    {
        $this->authorize('delete', $document);

        $name = $document->name;

        $this->documentService->deleteFile($document);
        $document->delete();

        ActivityLogger::log('delete_document', "Menghapus dokumen \"{$name}\".");

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    /**
     * Download file dilayani lewat controller (bukan URL storage publik)
     * agar permission benar-benar diverifikasi di backend sebelum file
     * fisik diserahkan ke user.
     */
    public function download(Request $request, Document $document): StreamedResponse
    {
        $this->authorize('download', $document);

        if (! Storage::disk('documents')->exists($document->file_path)) {
            abort(404, 'File dokumen tidak ditemukan di storage.');
        }

        ActivityLogger::log('download_document', "Mengunduh dokumen \"{$document->name}\".");

        return Storage::disk('documents')->download($document->file_path, $document->file_name);
    }

    /**
     * Streaming preview untuk file PDF (dibuka inline, bukan didownload).
     */
    public function preview(Request $request, Document $document): StreamedResponse
    {
        $this->authorize('view', $document);

        abort_unless($document->isPdf(), 404);
        abort_unless(Storage::disk('documents')->exists($document->file_path), 404);

        return Storage::disk('documents')->response($document->file_path, $document->file_name, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$document->file_name.'"',
        ]);
    }
}
