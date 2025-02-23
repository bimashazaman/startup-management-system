<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUpdate;
use Illuminate\Http\Request;

class ProjectUpdateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        $updates = $project->updates()->latest()->paginate(10);
        return view('project-updates.index', compact('project', 'updates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $this->authorize('update', $project);
        return view('project-updates.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'update_type' => 'required|in:general,milestone,blocker',
            'attachments' => 'nullable|array',
        ]);

        $project->updates()->create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Update posted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectUpdate  $projectUpdate
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, ProjectUpdate $update)
    {
        $this->authorize('view', $project);
        return view('project-updates.show', compact('project', 'update'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectUpdate  $projectUpdate
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, ProjectUpdate $update)
    {
        $this->authorize('update', $project);
        return view('project-updates.edit', compact('project', 'update'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectUpdate  $projectUpdate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project, ProjectUpdate $update)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'update_type' => 'required|in:general,milestone,blocker',
            'attachments' => 'nullable|array',
        ]);

        $update->update($validated);

        return redirect()->route('projects.updates.show', [$project, $update])
            ->with('success', 'Update modified successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectUpdate  $projectUpdate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, ProjectUpdate $update)
    {
        $this->authorize('update', $project);

        $update->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', 'Update deleted successfully.');
    }
}
