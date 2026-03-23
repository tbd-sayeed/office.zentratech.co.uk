@extends('layouts.app')

@section('title', $service->service_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">{{ $service->service_name }}</h1>
        <p class="text-muted mb-0">{{ $service->client->company_name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('services.edit', $service) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('payments.create', ['service_id' => $service->id, 'client_id' => $service->client_id]) }}" class="btn btn-primary">Add Payment</a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="mb-0 fw-semibold">Service Details</h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Service Type</p>
                <p class="mb-0 fw-medium">{{ $service->serviceType?->name ?? '—' }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Start Date</p>
                <p class="mb-0">{{ $service->start_date->format('M d, Y') }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Total Amount</p>
                <p class="mb-0 fw-medium">{{ currency_format($service->total_amount, $service->currency ?? 'GBP') }}</p>
            </div>
            @if(($service->discount ?? 0) > 0)
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Discount</p>
                <p class="mb-0 text-success">−{{ currency_format($service->discount, $service->currency ?? 'GBP') }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Net Amount</p>
                <p class="mb-0 fw-medium">{{ currency_format($service->net_amount, $service->currency ?? 'GBP') }}</p>
            </div>
            @endif
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Paid Amount</p>
                <p class="mb-0">{{ currency_format($service->paid_amount, $service->currency ?? 'GBP') }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Due Amount</p>
                <p class="mb-0 fw-medium">{{ currency_format($service->due_amount, $service->currency ?? 'GBP') }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Status</p>
                <span class="badge {{ $service->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $service->is_active ? 'Active' : 'Inactive' }}</span>
            </div>
        </div>

        <hr class="my-4">
        <h6 class="fw-semibold mb-3">ZentraTech Profit</h6>
        <div class="row g-3">
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Client Agreed (Net)</p>
                <p class="mb-0 fw-medium">{{ currency_format($service->net_amount, $service->currency ?? 'GBP') }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Team Cost</p>
                <p class="mb-0">
                    @if($service->teamAssignments->count())
                        @php $byCur = $service->teamAssignments->groupBy(fn($a) => $a->currency ?? 'USD')->map(fn($g) => $g->sum('agreed_amount')); @endphp
                        {{ $byCur->map(fn($a,$c) => currency_format($a,$c))->implode(' + ') }}
                    @else
                        <span class="text-muted">— No team assigned</span>
                    @endif
                </p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Profit</p>
                <p class="mb-0 fs-5 fw-bold text-success">{{ currency_format($service->profit_in_service_currency, $service->currency ?? 'GBP') }}</p>
                @if(!$service->teamAssignments->count())
                    <small class="text-muted">Full amount (project done by ZentraTech)</small>
                @endif
            </div>
        </div>

        @if($service->serviceType?->form_section === 'domain_hosting')
        <hr class="my-4">
        <h6 class="fw-semibold mb-3">Domain & Hosting Details</h6>
        <div class="row g-3">
            @if($service->domain_name)
            <div class="col-sm-6"><p class="text-muted small mb-0">Domain</p><p class="mb-0">{{ $service->domain_name }}</p></div>
            @endif
            @if($service->hosting_package)
            <div class="col-sm-6"><p class="text-muted small mb-0">Hosting</p><p class="mb-0">{{ $service->hosting_package }}</p></div>
            @endif
            @if($service->expiration_date)
            <div class="col-sm-6"><p class="text-muted small mb-0">Expires</p><p class="mb-0">{{ $service->expiration_date->format('M d, Y') }}</p></div>
            @endif
            @if($service->provider_name)
            <div class="col-sm-6"><p class="text-muted small mb-0">Provider</p><p class="mb-0">{{ $service->provider_name }}</p></div>
            @endif
            @if($service->credentials)
            <div class="col-12"><p class="text-muted small mb-0">Credentials</p><p class="mb-0">{{ $service->credentials }}</p></div>
            @endif
        </div>
        @endif

        @if($service->serviceType?->form_section === 'project_based' && ($service->projectType || $service->delivery_date || $service->contract_start_date || $service->contract_end_date))
        <hr class="my-4">
        <h6 class="fw-semibold mb-3">Project & Contract Details</h6>
        <div class="row g-3">
            @if($service->projectType)<div class="col-sm-6"><p class="text-muted small mb-0">Project Type</p><p class="mb-0">{{ $service->projectType->name }}</p></div>@endif
            @if($service->delivery_date)<div class="col-sm-6"><p class="text-muted small mb-0">Delivery Date</p><p class="mb-0">{{ $service->delivery_date->format('M d, Y') }}</p></div>@endif
            @if($service->contract_start_date)<div class="col-sm-6"><p class="text-muted small mb-0">Contract Start</p><p class="mb-0">{{ $service->contract_start_date->format('M d, Y') }}</p></div>@endif
            @if($service->contract_end_date)<div class="col-sm-6"><p class="text-muted small mb-0">Contract End</p><p class="mb-0">{{ $service->contract_end_date->format('M d, Y') }}</p></div>@endif
        </div>
        @endif

        @if($service->custom_service_type)
        <hr class="my-4">
        <h6 class="fw-semibold mb-3">Custom Service</h6>
        <p class="mb-0">{{ $service->custom_service_type }}</p>
        @endif

        @if($service->notes)
        <hr class="my-4">
        <h6 class="fw-semibold mb-2">Notes</h6>
        <p class="mb-0">{{ $service->notes }}</p>
        @endif
    </div>
</div>

@if($service->teamAssignments->count())
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold">Team Members Assigned</h5>
        <a href="{{ route('team-member-payments.create', ['service_id' => $service->id]) }}" class="btn btn-sm btn-primary">Record Payment to Team</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Team Member</th>
                        <th>Note</th>
                        <th>Agreed Amount</th>
                        <th>Paid</th>
                        <th>Due</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($service->teamAssignments as $a)
                    @php $assignCur = $a->currency ?? 'USD'; $paid = \App\Models\TeamMemberPayment::where('team_member_id', $a->team_member_id)->where('service_id', $service->id)->where('currency', $assignCur)->sum('amount'); $due = max(0, $a->agreed_amount - $paid); @endphp
                    <tr>
                        <td><a href="{{ route('team-members.show', $a->teamMember) }}">{{ $a->teamMember->name }}</a></td>
                        <td class="text-muted small">{{ $a->notes ?? '—' }}</td>
                        <td>{{ currency_format($a->agreed_amount, $a->currency ?? 'USD') }}</td>
                        <td>{{ currency_format($paid, $assignCur) }}</td>
                        <td class="{{ $due > 0 ? 'fw-semibold text-warning' : '' }}">{{ currency_format($due, $assignCur) }}</td>
                        <td><a href="{{ route('team-member-payments.create', ['team_member_id' => $a->team_member_id, 'service_id' => $service->id]) }}" class="btn btn-sm btn-outline-primary">Pay</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="mb-0 fw-semibold">Payments</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr><th>Date</th><th>Amount</th><th>Method</th></tr>
                </thead>
                <tbody>
                    @forelse($service->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td class="fw-medium">
                            {{ currency_format($payment->amount, $payment->currency ?? 'GBP') }}
                            @if(($payment->discount ?? 0) > 0)
                                <span class="text-success small">(−{{ currency_format($payment->discount, $payment->currency ?? 'GBP') }} disc)</span>
                            @endif
                        </td>
                        <td>{{ $payment->payment_method ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No payments found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
