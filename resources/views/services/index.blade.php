@extends('layouts.app')

@section('title', 'Services')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Services</h1>
    <a href="{{ route('services.create') }}" class="btn btn-primary">Add New Service</a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('services.index') }}" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label for="client_id" class="form-label small mb-0">Filter by Client</label>
                <select name="client_id" id="client_id" class="form-select form-select-sm">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="service_type_id" class="form-label small mb-0">Filter by Service Type</label>
                <select name="service_type_id" id="service_type_id" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach($serviceTypes as $st)
                    <option value="{{ $st->id }}" {{ request('service_type_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
            @if(request()->hasAny(['client_id', 'service_type_id']))
            <div class="col-md-2">
                <a href="{{ route('services.index') }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
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
                        <th>Service</th>
                        <th>Client</th>
                        <th>Type</th>
                        <th>Net Amount</th>
                        <th>Due</th>
                        <th>Profit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td class="fw-medium">{{ $service->service_name }}</td>
                        <td>{{ $service->client?->company_name ?? '—' }}</td>
                        <td>{{ $service->serviceType?->name ?? '—' }}</td>
                        <td>{{ currency_format($service->net_amount, $service->currency ?? 'GBP') }}</td>
                        <td>{{ currency_format($service->due_amount, $service->currency ?? 'GBP') }}</td>
                        <td class="fw-medium text-success">{{ currency_format($service->profit_in_service_currency, $service->currency ?? 'GBP') }}</td>
                        <td><a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No services found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $services->links() }}
</div>
@endsection
