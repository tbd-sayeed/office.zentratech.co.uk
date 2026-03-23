@extends('layouts.app')

@section('title', 'Edit Client')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Edit Client</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('clients.update', $client) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="company_name" class="form-label fw-medium">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $client->company_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="contact_person" class="form-label fw-medium">Contact Person <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person', $client->contact_person) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $client->email) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label fw-medium">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $client->phone) }}">
                </div>
                <div class="col-12">
                    <label for="address" class="form-label fw-medium">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $client->address) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label for="city" class="form-label fw-medium">City</label>
                    <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $client->city) }}">
                </div>
                <div class="col-md-4">
                    <label for="state" class="form-label fw-medium">State</label>
                    <input type="text" class="form-control" id="state" name="state" value="{{ old('state', $client->state) }}">
                </div>
                <div class="col-md-4">
                    <label for="postal_code" class="form-label fw-medium">Postal Code</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', $client->postal_code) }}">
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label fw-medium">Country</label>
                    <input type="text" class="form-control" id="country" name="country" value="{{ old('country', $client->country) }}">
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label fw-medium">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $client->notes) }}</textarea>
                </div>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $client->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('clients.show', $client) }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Client</button>
            </div>
        </form>
    </div>
</div>
@endsection
