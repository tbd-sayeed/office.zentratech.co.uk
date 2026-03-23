@extends('layouts.app')

@section('title', 'Edit Team Member')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Edit Team Member</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('team-members.update', $teamMember) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $teamMember->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label fw-medium">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $teamMember->email) }}">
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label fw-medium">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $teamMember->phone) }}">
                </div>
                <div class="col-md-6">
                    <label for="role" class="form-label fw-medium">Role</label>
                    <input type="text" class="form-control" id="role" name="role" value="{{ old('role', $teamMember->role) }}">
                </div>
                <div class="col-12">
                    <label for="bank_details" class="form-label fw-medium">Bank / Payment Details</label>
                    <textarea class="form-control" id="bank_details" name="bank_details" rows="2">{{ old('bank_details', $teamMember->bank_details) }}</textarea>
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label fw-medium">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $teamMember->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $teamMember->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('team-members.show', $teamMember) }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Team Member</button>
            </div>
        </form>
    </div>
</div>
@endsection
