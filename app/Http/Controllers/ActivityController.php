<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('view-activities');

        $activities = Activity::with('user:id,name,role')
            ->when($request->filled('action'), fn ($q) => $q->where('action', $request->query('action')))
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->query('user_id')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('activities.index', [
            'activities' => $activities,
            'filters' => $request->only(['action', 'user_id']),
        ]);
    }
}
