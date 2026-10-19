<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('documents')
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', ['categories' => $categories]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        ActivityLogger::log('create_category', "Menambahkan kategori \"{$category->name}\".");

        return back()->with('success', 'Kategori berhasil dibuat.');
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        ActivityLogger::log('edit_category', "Mengedit kategori \"{$category->name}\".");

        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        // Kategori tidak boleh dihapus jika masih dipakai oleh dokumen —
        // foreign key documents.category_id juga di-set restrictOnDelete()
        // di migration sebagai lapisan pengaman kedua di level database.
        if ($category->documents()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh dokumen.');
        }

        $name = $category->name;
        $category->delete();

        ActivityLogger::log('delete_category', "Menghapus kategori \"{$name}\".");

        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
