@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Payment Details</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="row g-3">
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Client</p>
                <p class="mb-0 fw-medium">{{ $payment->client->company_name }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Service</p>
                <p class="mb-0 fw-medium">{{ $payment->service->service_name }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Amount</p>
                <p class="mb-0 fs-5 fw-bold">{{ currency_format($payment->amount, $payment->currency ?? 'GBP') }}</p>
            </div>
            @if(($payment->discount ?? 0) > 0)
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Discount (waived)</p>
                <p class="mb-0 fw-medium text-success">−{{ currency_format($payment->discount, $payment->currency ?? 'GBP') }}</p>
            </div>
            @endif
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Payment Date</p>
                <p class="mb-0">{{ $payment->payment_date->format('M d, Y') }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Payment Method</p>
                <p class="mb-0">{{ $payment->payment_method ?? 'N/A' }}</p>
            </div>
            <div class="col-sm-6">
                <p class="text-muted small mb-0">Transaction Reference</p>
                <p class="mb-0">{{ $payment->transaction_reference ?? 'N/A' }}</p>
            </div>
            @if($payment->notes)
            <div class="col-12">
                <p class="text-muted small mb-0">Notes</p>
                <p class="mb-0">{{ $payment->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
