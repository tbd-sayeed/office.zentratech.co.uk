@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold text-dark">Payments</h1>
    <a href="{{ route('payments.create') }}" class="btn btn-primary">Add New Payment</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td>{{ $payment->client->company_name }}</td>
                        <td>{{ $payment->service->service_name }}</td>
                        <td class="fw-medium">
                            {{ currency_format($payment->amount, $payment->currency ?? 'GBP') }}
                            @if(($payment->discount ?? 0) > 0)
                                <span class="text-success small">(−{{ currency_format($payment->discount, $payment->currency ?? 'GBP') }} disc)</span>
                            @endif
                        </td>
                        <td><a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No payments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $payments->links() }}
</div>
@endsection
