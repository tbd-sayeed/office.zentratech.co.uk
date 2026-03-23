@extends('layouts.app')

@section('title', 'Edit Project Type')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Edit Project Type</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('project-types.update', $projectType) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $projectType->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="sort_order" class="form-label fw-medium">Sort Order</label>
                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $projectType->sort_order) }}" min="0">
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $projectType->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('project-types.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Project Type</button>
            </div>
        </form>
    </div>
</div>
@endsection
