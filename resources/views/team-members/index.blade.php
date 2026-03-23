@extends('layouts.app')

@section('title', 'Team Members')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Team Members</h1>
    <a href="{{ route('team-members.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add Team Member
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($teamMembers as $member)
            <a href="{{ route('team-members.show', $member) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px">
                        <span class="fw-semibold">{{ substr($member->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold">{{ $member->name }}</p>
                        <p class="mb-0 small text-muted">{{ $member->role ?? '—' }} @if($member->email)&bull; {{ $member->email }}@endif</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-secondary">{{ $member->assignments_count }} tasks</span>
                    @php
                        $agreedByCur = $member->assignments->groupBy('currency')->map(fn($g) => $g->sum('agreed_amount'));
                        $paidByCur = $member->payments->groupBy('currency')->map(fn($g) => $g->sum('amount'));
                    @endphp
                    <span class="badge bg-primary">{{ $agreedByCur->isEmpty() ? currency_format(0, 'GBP') : $agreedByCur->map(fn($a,$c) => currency_format($a,$c))->implode(' + ') }} agreed</span>
                    <span class="badge bg-success">{{ $paidByCur->isEmpty() ? currency_format(0, 'USD') : $paidByCur->map(fn($a,$c) => currency_format($a,$c))->implode(' + ') }} paid</span>
                    @php
                        $dueByCur = collect();
                        foreach ($agreedByCur as $cur => $agreed) {
                            $paid = $paidByCur[$cur] ?? 0;
                            $dueByCur[$cur] = max(0, $agreed - $paid);
                        }
                        foreach ($paidByCur as $cur => $paid) {
                            if (!isset($dueByCur[$cur]) && $paid > 0) $dueByCur[$cur] = 0;
                        }
                    @endphp
                    <span class="badge {{ $dueByCur->sum() > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">{{ $dueByCur->isEmpty() ? currency_format(0, 'GBP') : ($dueByCur->filter(fn($v)=>$v>0)->map(fn($a,$c) => currency_format($a,$c))->implode(' + ') ?: currency_format(0,'GBP')) }} due</span>
                    <span class="badge bg-info text-white">{{ currency_format($member->profit_in_primary_currency, $member->primary_currency) }} profit</span>
                    <span class="badge {{ $member->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $member->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </a>
            @empty
            <li class="list-group-item text-center py-5 text-muted">
                No team members yet. <a href="{{ route('team-members.create') }}">Add your first team member</a>
            </li>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-4">{{ $teamMembers->links() }}</div>
@endsection
