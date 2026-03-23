<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    public function index()
    {
        $serviceTypes = ServiceType::orderBy('sort_order')->orderBy('name')->paginate(15);
        return view('service-types.index', compact('serviceTypes'));
    }

    public function create()
    {
        return view('service-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name',
            'form_section' => 'required|in:domain_hosting,project_based,custom',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        ServiceType::create($validated);
        return redirect()->route('service-types.index')
            ->with('success', 'Service type created successfully.');
    }

    public function edit(ServiceType $serviceType)
    {
        return view('service-types.edit', compact('serviceType'));
    }

    public function update(Request $request, ServiceType $serviceType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:service_types,name,' . $serviceType->id,
            'form_section' => 'required|in:domain_hosting,project_based,custom',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $serviceType->update($validated);
        return redirect()->route('service-types.index')
            ->with('success', 'Service type updated successfully.');
    }

    public function destroy(ServiceType $serviceType)
    {
        if ($serviceType->services()->exists()) {
            return redirect()->route('service-types.index')
                ->with('error', 'Cannot delete: this service type is in use by one or more services.');
        }
        $serviceType->delete();
        return redirect()->route('service-types.index')
            ->with('success', 'Service type deleted successfully.');
    }
}
