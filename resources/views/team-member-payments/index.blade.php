@extends('layouts.app')

@section('title', 'Payments to Team Members')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Payments to Team Members</h1>
    <a href="{{ route('team-member-payments.create') }}" class="btn btn-primary">Record Payment</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('team-member-payments.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="team_member_id" class="form-label small mb-0">Filter by Team Member</label>
                <select name="team_member_id" id="team_member_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($teamMembers as $tm)
                    <option value="{{ $tm->id }}" {{ request('team_member_id') == $tm->id ? 'selected' : '' }}>{{ $tm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="service_id" class="form-label small mb-0">Filter by Service</label>
                <select name="service_id" id="service_id" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($services as $s)
                    <option value="{{ $s->id }}" {{ request('service_id') == $s->id ? 'selected' : '' }}>{{ $s->service_name }} ({{ $s->client->company_name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
            @if(request()->hasAny(['team_member_id', 'service_id']))
            <div class="col-md-2">
                <a href="{{ route('team-member-payments.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
            @endif
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Team Member</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th width="80"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                    <tr>
                        <td>{{ $p->payment_date->format('M d, Y') }}</td>
                        <td><a href="{{ route('team-members.show', $p->teamMember) }}">{{ $p->teamMember->name }}</a></td>
                        <td>{{ $p->service ? $p->service->service_name . ' (' . $p->service->client->company_name . ')' : '—' }}</td>
                        <td class="fw-medium">{{ currency_format($p->amount, $p->currency ?? 'USD') }}</td>
                        <td>{{ $p->payment_method ?? '—' }}</td>
                        <td>{{ $p->transaction_reference ?? '—' }}</td>
                        <td>
                            <form method="POST" action="{{ route('team-member-payments.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete this payment record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">{{ $payments->links() }}</div>
@endsection
