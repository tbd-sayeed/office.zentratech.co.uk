<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index()
    {
        $teamMembers = TeamMember::withCount(['assignments', 'payments'])
            ->with(['assignments.service.teamAssignments', 'payments'])
            ->orderBy('name')
            ->paginate(15);
        return view('team-members.index', compact('teamMembers'));
    }

    public function create()
    {
        return view('team-members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:100',
            'bank_details' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);

        TeamMember::create($validated);
        return redirect()->route('team-members.index')
            ->with('success', 'Team member created successfully.');
    }

    public function show(TeamMember $teamMember)
    {
        $teamMember->load([
            'assignments.service.client',
            'assignments.service.serviceType',
            'assignments.service.teamAssignments',
            'payments' => fn($q) => $q->with('service')->orderBy('payment_date', 'desc'),
        ]);
        $agreedTotal = $teamMember->assignments->sum('agreed_amount');
        $paidTotal = $teamMember->payments->sum('amount');
        return view('team-members.show', compact('teamMember', 'agreedTotal', 'paidTotal'));
    }

    public function edit(TeamMember $teamMember)
    {
        return view('team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'role' => 'nullable|string|max:100',
            'bank_details' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', $teamMember->is_active);

        $teamMember->update($validated);
        return redirect()->route('team-members.show', $teamMember)
            ->with('success', 'Team member updated successfully.');
    }

    public function destroy(TeamMember $teamMember)
    {
        $teamMember->delete();
        return redirect()->route('team-members.index')
            ->with('success', 'Team member deleted successfully.');
    }
}
