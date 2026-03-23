@extends('layouts.app')

@section('title', 'Record Payment to Team Member')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Record Payment to Team Member</h1>
    <p class="text-muted mb-0">Log a payment made to a team member (full payment history is tracked)</p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('team-member-payments.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="team_member_id" class="form-label fw-medium">Team Member <span class="text-danger">*</span></label>
                    <select name="team_member_id" id="team_member_id" class="form-select" required>
                        <option value="">Select</option>
                        @foreach($teamMembers as $tm)
                        <option value="{{ $tm->id }}" {{ old('team_member_id', $teamMemberId) == $tm->id ? 'selected' : '' }}>{{ $tm->name }}{{ $tm->role ? ' (' . $tm->role . ')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="service_id" class="form-label fw-medium">Service (optional)</label>
                    <select name="service_id" id="service_id" class="form-select">
                        <option value="">— Not linked to a service —</option>
                        @foreach($services as $s)
                        <option value="{{ $s->id }}" {{ old('service_id', $serviceId) == $s->id ? 'selected' : '' }}>{{ $s->service_name }} — {{ $s->client->company_name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted">Link this payment to a specific service/task if applicable</small>
                </div>
                <div class="col-md-6">
                    <label for="currency" class="form-label fw-medium">Currency</label>
                    @include('partials.currency-select', ['name' => 'currency', 'value' => old('currency', 'USD')])
                    <small class="text-muted">Payments to team members typically in USD</small>
                </div>
                <div class="col-md-6">
                    <label for="amount" class="form-label fw-medium">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="payment_date" class="form-label fw-medium">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="payment_method" class="form-label fw-medium">Payment Method</label>
                    <input type="text" class="form-control" id="payment_method" name="payment_method" value="{{ old('payment_method') }}" placeholder="Bank Transfer, PayPal, Cash, etc.">
                </div>
                <div class="col-md-6">
                    <label for="transaction_reference" class="form-label fw-medium">Transaction Reference</label>
                    <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" value="{{ old('transaction_reference') }}" placeholder="Ref/ID for tracking">
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label fw-medium">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('team-member-payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Record Payment</button>
            </div>
        </form>
    </div>
</div>
@endsection
