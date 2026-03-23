@extends('layouts.app')

@section('title', $teamMember->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">{{ $teamMember->name }}</h1>
        <p class="text-muted mb-0">{{ $teamMember->role ?? '—' }} @if($teamMember->email)&bull; {{ $teamMember->email }}@endif</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('team-member-payments.create', ['team_member_id' => $teamMember->id]) }}" class="btn btn-primary">Record Payment</a>
        <a href="{{ route('team-members.edit', $teamMember) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('team-member-payments.index', ['team_member_id' => $teamMember->id]) }}" class="btn btn-outline-secondary">View All Payments</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-0">Total Agreed (Tasks)</p>
                <p class="fs-4 fw-bold text-primary mb-0">{{ format_amounts_by_currency($teamMember->assignments->map(fn($a) => ['amount' => $a->agreed_amount, 'currency' => $a->currency ?? 'USD'])) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-0">Total Paid</p>
                <p class="fs-4 fw-bold text-success mb-0">{{ format_amounts_by_currency($teamMember->payments->map(fn($p) => ['amount' => $p->amount, 'currency' => $p->currency ?? 'USD'])) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-0">Balance Due</p>
                @php
                    $dueItems = [];
                    foreach ($teamMember->assignments->groupBy('currency') as $cur => $assigns) {
                        $agreed = $assigns->sum('agreed_amount');
                        $paid = $teamMember->payments->where('currency', $cur)->sum('amount');
                        if (max(0, $agreed - $paid) > 0) $dueItems[] = ['amount' => max(0, $agreed - $paid), 'currency' => $cur];
                    }
                @endphp
                <p class="fs-4 fw-bold {{ count($dueItems) > 0 ? 'text-warning' : 'text-muted' }} mb-0">{{ count($dueItems) ? format_amounts_by_currency($dueItems) : currency_format(0, 'GBP') }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-0">ZentraTech Profit</p>
                <p class="fs-4 fw-bold text-success mb-0">{{ currency_format($teamMember->profit_in_primary_currency, $teamMember->primary_currency) }}</p>
                <small class="text-muted">Profit share from services they work on</small>
            </div>
        </div>
    </div>
</div>

@if($teamMember->bank_details || $teamMember->notes)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="mb-0 fw-semibold">Details</h5>
    </div>
    <div class="card-body">
        @if($teamMember->bank_details)
        <p class="mb-0"><strong>Bank/Payment:</strong> {{ $teamMember->bank_details }}</p>
        @endif
        @if($teamMember->notes)
        <p class="mb-0 mt-2"><strong>Notes:</strong> {{ $teamMember->notes }}</p>
        @endif
    </div>
</div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">Tasks / Service Assignments</h5>
    </div>
    <div class="card-body p-0">
        @if($teamMember->assignments->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Service</th>
                        <th>Client</th>
                        <th>Note</th>
                        <th>Agreed Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teamMember->assignments as $a)
                    @php $assignCur = $a->currency ?? 'USD'; $paid = \App\Models\TeamMemberPayment::where('team_member_id', $teamMember->id)->where('service_id', $a->service_id)->where('currency', $assignCur)->sum('amount'); $due = max(0, $a->agreed_amount - $paid); @endphp
                    <tr>
                        <td><a href="{{ route('services.show', $a->service) }}">{{ $a->service->service_name }}</a></td>
                        <td>{{ $a->service->client->company_name }}</td>
                        <td class="text-muted small">{{ $a->notes ?? '—' }}</td>
                        <td>{{ currency_format($a->agreed_amount, $a->currency ?? 'USD') }}</td>
                        <td>{{ currency_format($paid, $assignCur) }}</td>
                        <td class="{{ $due > 0 ? 'fw-semibold text-warning' : '' }}">{{ currency_format($due, $assignCur) }}</td>
                        <td><a href="{{ route('team-member-payments.create', ['team_member_id' => $teamMember->id, 'service_id' => $a->service_id]) }}" class="btn btn-sm btn-outline-primary">Pay</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center text-muted py-4 mb-0">No service assignments yet. Assign this team member to services when creating or editing a service.</p>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="mb-0 fw-semibold">Payment History</h5>
    </div>
    <div class="card-body p-0">
        @if($teamMember->payments->count())
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teamMember->payments as $p)
                    <tr>
                        <td>{{ $p->payment_date->format('M d, Y') }}</td>
                        <td>{{ $p->service?->service_name ?? '—' }}</td>
                        <td class="fw-medium">{{ currency_format($p->amount, $p->currency ?? 'USD') }}</td>
                        <td>{{ $p->payment_method ?? '—' }}</td>
                        <td>{{ $p->transaction_reference ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center text-muted py-4 mb-0">No payments recorded yet.</p>
        @endif
    </div>
</div>
@endsection
