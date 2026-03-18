@extends('layouts.app')

@section('title', $service->service_name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $service->service_name }}</h1>
        <p class="text-gray-600 mt-1">{{ $service->client->company_name }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('services.edit', $service) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <a href="{{ route('payments.create', ['service_id' => $service->id, 'client_id' => $service->client_id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Payment</a>
    </div>
</div>

<div class="bg-white shadow rounded-lg p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Service Details</h2>
    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
        <div>
            <dt class="text-sm font-medium text-gray-500">Service Type</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $service->service_type)) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Start Date</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $service->start_date->format('M d, Y') }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Total Amount</dt>
            <dd class="mt-1 text-sm text-gray-900">${{ number_format($service->total_amount, 2) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Paid Amount</dt>
            <dd class="mt-1 text-sm text-gray-900">${{ number_format($service->paid_amount, 2) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Due Amount</dt>
            <dd class="mt-1 text-sm text-gray-900">${{ number_format($service->due_amount, 2) }}</dd>
        </div>
        <div>
            <dt class="text-sm font-medium text-gray-500">Status</dt>
            <dd class="mt-1">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                </span>
            </dd>
        </div>
    </dl>

    @if($service->service_type == 'domain_hosting')
    <div class="mt-6 pt-6 border-t">
        <h3 class="text-lg font-semibold mb-4">Domain & Hosting Details</h3>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            @if($service->domain_name)
            <div>
                <dt class="text-sm font-medium text-gray-500">Domain Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->domain_name }}</dd>
            </div>
            @endif
            @if($service->hosting_package)
            <div>
                <dt class="text-sm font-medium text-gray-500">Hosting Package</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->hosting_package }}</dd>
            </div>
            @endif
            @if($service->expiration_date)
            <div>
                <dt class="text-sm font-medium text-gray-500">Expiration Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->expiration_date->format('M d, Y') }}</dd>
            </div>
            @endif
            @if($service->provider_name)
            <div>
                <dt class="text-sm font-medium text-gray-500">Provider</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->provider_name }}</dd>
            </div>
            @endif
            @if($service->credentials)
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Credentials / Notes</dt>
                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $service->credentials }}</dd>
            </div>
            @endif
        </dl>
    </div>
    @endif

    @if($service->service_type == 'web_mobile_dev')
    <div class="mt-6 pt-6 border-t">
        <h3 class="text-lg font-semibold mb-4">Web/Mobile Development Details</h3>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            @if($service->project_type)
            <div>
                <dt class="text-sm font-medium text-gray-500">Project Type</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $service->project_type)) }}</dd>
            </div>
            @endif
            @if($service->delivery_date)
            <div>
                <dt class="text-sm font-medium text-gray-500">Delivery Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->delivery_date->format('M d, Y') }}</dd>
            </div>
            @endif
            @if($service->contract_start_date)
            <div>
                <dt class="text-sm font-medium text-gray-500">Contract Start Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->contract_start_date->format('M d, Y') }}</dd>
            </div>
            @endif
            @if($service->contract_end_date)
            <div>
                <dt class="text-sm font-medium text-gray-500">Contract End Date</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->contract_end_date->format('M d, Y') }}</dd>
            </div>
            @endif
        </dl>
    </div>
    @endif

    @if($service->service_type == 'custom' && $service->custom_service_type)
    <div class="mt-6 pt-6 border-t">
        <h3 class="text-lg font-semibold mb-4">Custom Service Details</h3>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Service Type</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $service->custom_service_type }}</dd>
            </div>
        </dl>
    </div>
    @endif

    @if($service->notes)
    <div class="mt-6 pt-6 border-t">
        <h3 class="text-lg font-semibold mb-4">General Notes</h3>
        <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $service->notes }}</p>
    </div>
    @endif
</div>

<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h2 class="text-xl font-semibold mb-4">Payments</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($service->payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $payment->payment_date->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->payment_method ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No payments found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

