@extends('layouts.app')

@section('title', $client->company_name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">{{ $client->company_name }}</h1>
        <p class="text-muted mb-0">{{ $client->contact_person }} &bull; {{ $client->email }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('clients.edit', $client) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('services.create', ['client_id' => $client->id]) }}" class="btn btn-primary">Add Service</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-semibold">Client Information</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="text-muted small mb-0">Company Name</p>
                        <p class="mb-0 fw-medium">{{ $client->company_name }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-0">Contact Person</p>
                        <p class="mb-0 fw-medium">{{ $client->contact_person }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-0">Email</p>
                        <p class="mb-0">{{ $client->email }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-0">Phone</p>
                        <p class="mb-0">{{ $client->phone ?? 'N/A' }}</p>
                    </div>
                    @if($client->address)
                    <div class="col-12">
                        <p class="text-muted small mb-0">Address</p>
                        <p class="mb-0">{{ $client->address }}</p>
                    </div>
                    @endif
                    @if($client->notes)
                    <div class="col-12">
                        <p class="text-muted small mb-0">Notes</p>
                        <p class="mb-0">{{ $client->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4">
                <h5 class="mb-0 fw-semibold">Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <p class="text-muted small mb-0">Total Services</p>
                    <p class="fs-3 fw-bold mb-0">{{ $client->services->count() }}</p>
                </div>
                @php
                    $agreedByCur = $client->services->groupBy(fn($s) => $s->currency ?? 'GBP')->map(fn($g) => $g->sum(fn($s) => $s->total_amount - ($s->discount ?? 0)));
                    $paidByCur = $client->services->groupBy(fn($s) => $s->currency ?? 'GBP')->map(fn($g) => $g->sum('paid_amount'));
                    $dueByCur = $client->services->groupBy(fn($s) => $s->currency ?? 'GBP')->map(fn($g) => $g->sum(fn($s) => max(0, $s->total_amount - ($s->discount ?? 0) - $s->paid_amount)));
                    $totalProfit = $client->services->sum(fn($s) => $s->profit);
                @endphp
                <div class="row g-3">
                    <div class="col-4">
                        <p class="text-muted small mb-0">Total Agreed</p>
                        <p class="fs-5 fw-bold text-primary mb-0">{{ $agreedByCur->isEmpty() ? currency_format(0, 'GBP') : $agreedByCur->map(fn($a,$c) => currency_format($a,$c))->implode(' + ') }}</p>
                    </div>
                    <div class="col-4">
                        <p class="text-muted small mb-0">Total Paid</p>
                        <p class="fs-5 fw-bold text-success mb-0">{{ $paidByCur->isEmpty() ? currency_format(0, 'GBP') : $paidByCur->map(fn($a,$c) => currency_format($a,$c))->implode(' + ') }}</p>
                    </div>
                    <div class="col-4">
                        <p class="text-muted small mb-0">Balance Due</p>
                        <p class="fs-5 fw-bold {{ $dueByCur->sum() > 0 ? 'text-warning' : 'text-muted' }} mb-0">{{ $dueByCur->isEmpty() ? currency_format(0, 'GBP') : ($dueByCur->filter(fn($v)=>$v>0)->map(fn($a,$c) => currency_format($a,$c))->implode(' + ') ?: currency_format(0,'GBP')) }}</p>
                    </div>
                    <div class="col-12 pt-2 border-top">
                        <p class="text-muted small mb-0">ZentraTech Profit</p>
                        <p class="fs-5 fw-bold text-success mb-0">{{ currency_format($totalProfit, 'GBP') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 pt-4">
        <h5 class="mb-0 fw-semibold">Services</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Service Name</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Due</th>
                        <th>Profit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($client->services as $service)
                    <tr>
                        <td class="fw-medium">{{ $service->service_name }}</td>
                        <td>{{ $service->serviceType?->name ?? '—' }}</td>
                        <td>{{ currency_format($service->net_amount, $service->currency ?? 'GBP') }}</td>
                        <td>{{ currency_format($service->due_amount, $service->currency ?? 'GBP') }}</td>
                        <td class="fw-medium text-success">{{ currency_format($service->profit_in_service_currency, $service->currency ?? 'GBP') }}</td>
                        <td><a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No services found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
