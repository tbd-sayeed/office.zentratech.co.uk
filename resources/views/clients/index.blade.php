@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Clients</h1>
    <a href="{{ route('clients.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-1" viewBox="0 0 16 16">
            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
        </svg>
        Add New Client
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="list-group list-group-flush">
            @forelse($clients as $client)
            <a href="{{ route('clients.show', $client) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-4">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px">
                        <span class="fw-semibold">{{ substr($client->company_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="mb-0 fw-semibold">{{ $client->company_name }}</p>
                        <p class="mb-0 small text-muted">{{ $client->contact_person }} &bull; {{ $client->email }}</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @php
                        $agreedByCur = $client->services->groupBy(fn($s) => $s->currency ?? 'GBP')->map(fn($g) => $g->sum(fn($s) => $s->total_amount - ($s->discount ?? 0)));
                        $paidByCur = $client->services->groupBy(fn($s) => $s->currency ?? 'GBP')->map(fn($g) => $g->sum('paid_amount'));
                        $dueByCur = collect();
                        foreach ($agreedByCur as $cur => $agreed) {
                            $dueByCur[$cur] = max(0, $agreed - ($paidByCur[$cur] ?? 0));
                        }
                        $clientProfit = $client->services->sum(fn($s) => $s->profit);
                    @endphp
                    <span class="badge bg-primary">{{ $agreedByCur->isEmpty() ? currency_format(0,'GBP') : $agreedByCur->map(fn($a,$c)=>currency_format($a,$c))->implode(' + ') }} agreed</span>
                    <span class="badge bg-success">{{ $paidByCur->isEmpty() ? currency_format(0,'GBP') : $paidByCur->map(fn($a,$c)=>currency_format($a,$c))->implode(' + ') }} paid</span>
                    <span class="badge {{ $dueByCur->sum() > 0 ? 'bg-warning text-dark' : 'bg-secondary' }}">{{ $dueByCur->isEmpty() ? currency_format(0,'GBP') : ($dueByCur->filter(fn($v)=>$v>0)->map(fn($a,$c)=>currency_format($a,$c))->implode(' + ') ?: currency_format(0,'GBP')) }} due</span>
                    <span class="badge bg-info text-white">{{ currency_format($clientProfit, 'GBP') }} profit</span>
                    <span class="badge {{ $client->is_active ? 'bg-success' : 'bg-secondary' }}">{{ $client->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </a>
            @empty
            <li class="list-group-item text-center py-5 text-muted">
                No clients found. <a href="{{ route('clients.create') }}">Create your first client</a>
            </li>
            @endforelse
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $clients->links() }}
</div>
@endsection
