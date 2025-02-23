<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $projects = $user->projects()
            ->withCount('updates')
            ->latest()
            ->take(5)
            ->get();

        $recentUpdates = $user->projects()
            ->with(['updates' => function ($query) {
                $query->latest()->take(5);
            }])
            ->get()
            ->pluck('updates')
            ->flatten()
            ->sortByDesc('created_at')
            ->take(5);

        $stats = [
            'total_projects' => $user->projects()->count(),
            'ongoing_projects' => $user->projects()->where('status', 'ongoing')->count(),
            'completed_projects' => $user->projects()->where('status', 'completed')->count(),
            'total_updates' => $user->projects()->withCount('updates')->get()->sum('updates_count'),
        ];

        return view('dashboard', compact('projects', 'recentUpdates', 'stats'));
    }
}
