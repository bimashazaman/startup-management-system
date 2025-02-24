<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\Expense;
use App\Models\ProjectUpdate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Get projects overview
        $projects = Project::withCount(['tasks', 'timeEntries', 'updates'])
            ->with(['tasks' => function ($query) {
                $query->selectRaw('project_id, count(*) as total, sum(case when status = "done" then 1 else 0 end) as completed')
                    ->groupBy('project_id');
            }])
            ->latest()
            ->take(5)
            ->get();

        // Calculate project statistics
        $stats = [
            'total_projects' => Project::count(),
            'ongoing_projects' => Project::where('status', '!=', 'completed')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'total_updates' => ProjectUpdate::count(),
        ];

        // Get tasks statistics
        $tasks = [
            'total' => Task::count(),
            'completed' => Task::where('status', 'done')->count(),
            'in_progress' => Task::where('status', 'in_progress')->count(),
            'overdue' => Task::where('due_date', '<', now())
                ->where('status', '!=', 'done')
                ->count(),
        ];

        // Get time tracking summary for the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $timeTracking = [
            'total_hours' => TimeEntry::whereBetween('started_at', [$startOfMonth, $endOfMonth])
                ->sum('duration_minutes') / 60,
            'billable_hours' => TimeEntry::whereBetween('started_at', [$startOfMonth, $endOfMonth])
                ->where('is_billable', true)
                ->sum('duration_minutes') / 60,
            'running_timers' => TimeEntry::whereNull('ended_at')->count(),
        ];

        // Get expenses overview for the current month
        $expenses = [
            'total' => Expense::whereBetween('date', [$startOfMonth, $endOfMonth])->sum('amount'),
            'pending' => Expense::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'pending')
                ->sum('amount'),
            'approved' => Expense::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'approved')
                ->sum('amount'),
            'reimbursed' => Expense::whereBetween('date', [$startOfMonth, $endOfMonth])
                ->where('status', 'reimbursed')
                ->sum('amount'),
        ];

        // Get recent activities
        $recentTasks = Task::with(['project', 'assignee'])
            ->latest()
            ->take(5)
            ->get();

        $recentExpenses = Expense::with(['user', 'project'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent project updates
        $recentUpdates = ProjectUpdate::with('project')
            ->latest()
            ->take(5)
            ->get();

        // Get task completion trend
        $taskTrend = Task::where('created_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, count(*) as total, sum(case when status = "done" then 1 else 0 end) as completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Get time tracking trend
        $timeTrend = TimeEntry::where('started_at', '>=', Carbon::now()->subDays(30))
            ->selectRaw('DATE(started_at) as date, sum(duration_minutes) as total_minutes')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($entry) {
                return [
                    'date' => $entry->date,
                    'hours' => round($entry->total_minutes / 60, 2),
                ];
            });

        return view('dashboard', compact(
            'projects',
            'stats',
            'tasks',
            'timeTracking',
            'expenses',
            'recentTasks',
            'recentExpenses',
            'recentUpdates',
            'taskTrend',
            'timeTrend'
        ));
    }
}
