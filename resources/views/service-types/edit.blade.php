@extends('layouts.app')

@section('title', 'Edit Service Type')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Edit Service Type</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('service-types.update', $serviceType) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $serviceType->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="form_section" class="form-label fw-medium">Form Section <span class="text-danger">*</span></label>
                    <select name="form_section" id="form_section" class="form-select" required>
                        <option value="project_based" {{ old('form_section', $serviceType->form_section) == 'project_based' ? 'selected' : '' }}>Project Based</option>
                        <option value="domain_hosting" {{ old('form_section', $serviceType->form_section) == 'domain_hosting' ? 'selected' : '' }}>Domain & Hosting</option>
                        <option value="custom" {{ old('form_section', $serviceType->form_section) == 'custom' ? 'selected' : '' }}>Custom</option>
                    </select>
                    <small class="text-muted">Controls which extra fields appear when creating a service.</small>
                </div>
                <div class="col-md-6">
                    <label for="sort_order" class="form-label fw-medium">Sort Order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $serviceType->sort_order) }}" min="0">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $serviceType->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('service-types.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Service Type</button>
            </div>
        </form>
    </div>
</div>
@endsection
