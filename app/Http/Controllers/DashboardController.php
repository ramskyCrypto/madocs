<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $documentsQuery = Document::query()->visibleTo($user);

        $stats = [
            'total_dokumen' => (clone $documentsQuery)->count(),
            'dokumen_aktif' => (clone $documentsQuery)->where('status', Document::STATUS_AKTIF)->count(),
            'dokumen_arsip' => (clone $documentsQuery)->where('status', Document::STATUS_ARSIP)->count(),
            'dokumen_baru' => (clone $documentsQuery)->where('created_at', '>=', now()->subDays(7))->count(),
            'total_kategori' => Category::count(),
            'total_pengguna' => $user->isAdmin() ? User::count() : null,
        ];

        $dokumenPerKategori = Category::query()
            ->withCount(['documents' => function ($q) use ($user) {
                $q->visibleTo($user);
            }])
            ->orderByDesc('documents_count')
            ->get(['id', 'name']);

        $dokumenPerBulan = (clone $documentsQuery)
            ->select(DB::raw("DATE_FORMAT(document_date, '%Y-%m') as bulan"), DB::raw('count(*) as total'))
            ->where('document_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $dokumenTerbaru = (clone $documentsQuery)
            ->with(['category:id,name', 'user:id,name'])
            ->latest()
            ->limit(6)
            ->get(['id', 'name', 'category_id', 'user_id', 'created_at', 'status', 'file_extension']);

        $aktivitasTerbaru = $user->isAdmin()
            ? Activity::with('user:id,name,role')->latest()->limit(8)->get()
            : collect();

        return view('dashboard.index', [
            'stats' => $stats,
            'dokumenPerKategori' => $dokumenPerKategori,
            'dokumenPerBulan' => $dokumenPerBulan,
            'dokumenTerbaru' => $dokumenTerbaru,
            'aktivitasTerbaru' => $aktivitasTerbaru,
        ]);
    }
}
