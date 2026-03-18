@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Payment Details</h1>
</div>

<div class="bg-white shadow rounded-lg p-6">
    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-gray-500">Client</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $payment->client->company_name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Service</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $payment->service->service_name }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Amount</dt>
            <dd class="mt-1 text-sm text-gray-900">${{ number_format($payment->amount, 2) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Payment Date</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $payment->payment_method ?? 'N/A' }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Transaction Reference</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $payment->transaction_reference ?? 'N/A' }}</dd>
        </div>
        @if($payment->notes)
        <div class="sm:col-span-2">
            <dt class="text-sm font-medium text-gray-500">Notes</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $payment->notes }}</dd>
        </div>
        @endif
    </dl>
</div>
@endsection

