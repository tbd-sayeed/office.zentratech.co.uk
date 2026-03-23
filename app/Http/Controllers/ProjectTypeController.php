<?php

namespace App\Http\Controllers;

use App\Models\ProjectType;
use Illuminate\Http\Request;

class ProjectTypeController extends Controller
{
    public function index()
    {
        $projectTypes = ProjectType::orderBy('sort_order')->orderBy('name')->paginate(15);
        return view('project-types.index', compact('projectTypes'));
    }

    public function create()
    {
        return view('project-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:project_types,name',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ProjectType::create($validated);
        return redirect()->route('project-types.index')
            ->with('success', 'Project type created successfully.');
    }

    public function edit(ProjectType $projectType)
    {
        return view('project-types.edit', compact('projectType'));
    }

    public function update(Request $request, ProjectType $projectType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:project_types,name,' . $projectType->id,
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $projectType->update($validated);
        return redirect()->route('project-types.index')
            ->with('success', 'Project type updated successfully.');
    }

    public function destroy(ProjectType $projectType)
    {
        if ($projectType->services()->exists()) {
            return redirect()->route('project-types.index')
                ->with('error', 'Cannot delete: this project type is in use by one or more services.');
        }
        $projectType->delete();
        return redirect()->route('project-types.index')
            ->with('success', 'Project type deleted successfully.');
    }
}
