<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeEntryController extends Controller
{
    public function index(Request $request)
    {
        $query = TimeEntry::with(['user', 'project', 'task'])
            ->where('user_id', auth()->id());

        // Apply date range filter
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('started_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        // Apply project filter
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Apply task filter
        if ($request->filled('task_id')) {
            $query->where('task_id', $request->task_id);
        }

        // Apply billable filter
        if ($request->filled('is_billable')) {
            $query->where('is_billable', $request->is_billable);
        }

        $timeEntries = $query->latest()->get();
        $projects = Project::all();
        $tasks = Task::whereIn('project_id', $projects->pluck('id'))->get();

        // Calculate summary
        $summary = [
            'total_duration' => $timeEntries->sum('duration_minutes'),
            'billable_duration' => $timeEntries->where('is_billable', true)->sum('duration_minutes'),
            'billable_amount' => $timeEntries->where('is_billable', true)
                ->sum(function ($entry) {
                    return ($entry->duration_minutes / 60) * $entry->hourly_rate;
                }),
        ];

        // Paginate after calculations
        $timeEntries = $query->latest()->paginate(15);

        return view('time-entries.index', compact('timeEntries', 'projects', 'tasks', 'summary'));
    }

    public function create()
    {
        $projects = Project::all();
        $tasks = Task::whereIn('project_id', $projects->pluck('id'))->get();

        return view('time-entries.create', compact('projects', 'tasks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'required|string',
            'started_at' => 'nullable|date',
            'ended_at' => 'nullable|date|after:started_at',
            'is_billable' => 'boolean',
            'hourly_rate' => 'nullable|required_if:is_billable,true|numeric|min:0',
        ]);

        // Set started_at to now if not provided
        if (!isset($validated['started_at'])) {
            $validated['started_at'] = now();
        }

        // Calculate duration only if ended_at is provided
        $duration = null;
        if (isset($validated['ended_at'])) {
            $duration = Carbon::parse($validated['started_at'])->diffInMinutes(Carbon::parse($validated['ended_at']));
        }

        $timeEntry = TimeEntry::create([
            ...$validated,
            'user_id' => auth()->id(),
            'duration_minutes' => $duration,
        ]);

        return redirect()
            ->route('time-entries.index')
            ->with('success', 'Time entry created successfully.');
    }

    public function edit(TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);

        $projects = Project::all();
        $tasks = Task::whereIn('project_id', $projects->pluck('id'))->get();

        return view('time-entries.edit', compact('timeEntry', 'projects', 'tasks'));
    }

    public function update(Request $request, TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'task_id' => 'nullable|exists:tasks,id',
            'description' => 'required|string',
            'started_at' => 'required|date',
            'ended_at' => 'nullable|date|after:started_at',
            'is_billable' => 'boolean',
            'hourly_rate' => 'nullable|required_if:is_billable,true|numeric|min:0',
        ]);

        $timeEntry->update([
            ...$validated,
            'duration_minutes' => $validated['ended_at']
                ? Carbon::parse($validated['started_at'])->diffInMinutes(Carbon::parse($validated['ended_at']))
                : null,
        ]);

        return redirect()
            ->route('time-entries.index')
            ->with('success', 'Time entry updated successfully.');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $this->authorize('delete', $timeEntry);

        $timeEntry->delete();

        return redirect()
            ->route('time-entries.index')
            ->with('success', 'Time entry deleted successfully.');
    }

    public function stop(TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);

        if (!$timeEntry->ended_at) {
            $timeEntry->update([
                'ended_at' => now(),
                'duration_minutes' => Carbon::parse($timeEntry->started_at)->diffInMinutes(now()),
            ]);
        }

        return back()->with('success', 'Timer stopped successfully.');
    }
}
