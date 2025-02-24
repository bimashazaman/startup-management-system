<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['teamLead', 'members'])
            ->withCount('members')
            ->paginate(10);

        return view('teams.index', compact('teams'));
    }

    public function create()
    {
        $users = User::all();
        return view('teams.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'required|string|max:255',
            'team_lead_id' => 'required|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'department' => $validated['department'],
            'team_lead_id' => $validated['team_lead_id'],
        ]);

        if (!empty($validated['members'])) {
            $team->members()->attach($validated['members']);
        }

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Team created successfully.');
    }

    public function show(Team $team)
    {
        $team->load(['teamLead', 'members', 'projects']);
        return view('teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $users = User::all();
        return view('teams.edit', compact('team', 'users'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'department' => 'required|string|max:255',
            'team_lead_id' => 'required|exists:users,id',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id',
        ]);

        $team->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'department' => $validated['department'],
            'team_lead_id' => $validated['team_lead_id'],
        ]);

        if (isset($validated['members'])) {
            $team->members()->sync($validated['members']);
        }

        return redirect()
            ->route('teams.show', $team)
            ->with('success', 'Team updated successfully.');
    }

    public function destroy(Team $team)
    {
        $team->members()->detach();
        $team->delete();

        return redirect()
            ->route('teams.index')
            ->with('success', 'Team deleted successfully.');
    }

    public function addMember(Request $request, Team $team)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'nullable|string|max:255',
        ]);

        $team->members()->attach($validated['user_id'], [
            'role' => $validated['role'] ?? null,
        ]);

        return back()->with('success', 'Team member added successfully.');
    }

    public function removeMember(Team $team, User $user)
    {
        $team->members()->detach($user->id);
        return back()->with('success', 'Team member removed successfully.');
    }
}
