@extends('layouts.app')

@section('title', $client->company_name)

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ $client->company_name }}</h1>
        <p class="text-gray-600 mt-1">{{ $client->contact_person }} • {{ $client->email }}</p>
    </div>
    <div class="flex space-x-2">
        <a href="{{ route('clients.edit', $client) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
        <a href="{{ route('services.create', ['client_id' => $client->id]) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Add Service</a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Client Information</h2>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
            <div>
                <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->company_name }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Contact Person</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->contact_person }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->email }}</dd>
            </div>
            <div>
                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->phone ?? 'N/A' }}</dd>
            </div>
            @if($client->address)
            <div class="sm:col-span-2">
                <dt class="text-sm font-medium text-gray-500">Address</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $client->address }}</dd>
            </div>
            @endif
        </dl>
        @if($client->notes)
        <div class="mt-6">
            <dt class="text-sm font-medium text-gray-500">Notes</dt>
            <dd class="mt-1 text-sm text-gray-900">{{ $client->notes }}</dd>
        </div>
        @endif
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Quick Stats</h2>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-500">Total Services</p>
                <p class="text-2xl font-bold">{{ $client->services->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Payments</p>
                <p class="text-2xl font-bold">${{ number_format($client->payments->sum('amount'), 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="bg-white shadow rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <h2 class="text-xl font-semibold mb-4">Services</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($client->services as $service)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $service->service_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $service->service_type)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($service->total_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($service->due_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:text-blue-900">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">No services found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

