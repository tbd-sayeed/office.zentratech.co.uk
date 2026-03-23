@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-primary" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.325 6.325 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1h4.216z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-muted small mb-0">Total Clients</p>
                    <p class="fs-4 fw-bold mb-0">{{ $totalClients }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 16 16">
                        <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm4.5 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm0 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm0 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-muted small mb-0">Active Services</p>
                    <p class="fs-4 fw-bold mb-0">{{ $totalServices }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-warning" viewBox="0 0 16 16">
                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm1-13A1 1 0 0 0 8 4a1 1 0 0 0-1 1v8a1 1 0 0 0 2 0V4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-muted small mb-0">Upcoming Renewals</p>
                    <p class="fs-4 fw-bold mb-0">{{ $upcomingRenewals }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-danger" viewBox="0 0 16 16">
                        <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.431c-1.194-.17-1.98-.75-2.032-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.788-2.151 1.95V6.175l.349.087z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-muted small mb-0">Payments Due</p>
                    <p class="fs-4 fw-bold mb-0">{{ currency_format($paymentsDue, 'GBP') }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3 me-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="text-success" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-muted small mb-0">ZentraTech Profit</p>
                    <p class="fs-4 fw-bold mb-0 text-success">{{ currency_format($totalProfit ?? 0, 'GBP') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="mb-0 fw-semibold">Upcoming Renewals</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Client</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($renewalsList as $service)
                            <tr>
                                <td class="fw-medium">{{ $service->service_name }}</td>
                                <td>{{ $service->client?->company_name ?? '—' }}</td>
                                <td class="text-muted">{{ $service->expiration_date->format('M d, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No upcoming renewals</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="mb-0 fw-semibold">Recent Services</h5>
            </div>
            <div class="card-body">
                @forelse($recentServices as $service)
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom border-light">
                    <div>
                        <p class="mb-0 fw-medium">{{ $service->service_name }}</p>
                        <p class="mb-0 small text-muted">{{ $service->client?->company_name ?? 'Unassigned' }}</p>
                    </div>
                    <span class="small text-muted">{{ $service->created_at->format('M d') }}</span>
                </div>
                @empty
                <p class="text-muted mb-0 py-3">No recent services</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
