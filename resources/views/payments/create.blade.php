@extends('layouts.app')

@section('title', 'Create Payment')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Record Payment</h1>
</div>

<form method="POST" action="{{ route('payments.store') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="client_id">Client *</label>
            <select name="client_id" id="client_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Client</option>
                @foreach($clients as $client)
                <option value="{{ $client->id }}" {{ old('client_id', $clientId) == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="service_id">Service *</label>
            <select name="service_id" id="service_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="">Select Service</option>
                @foreach($services as $service)
                <option value="{{ $service->id }}" data-client-id="{{ $service->client_id }}" {{ old('service_id', $serviceId) == $service->id ? 'selected' : '' }}>{{ $service->service_name }} - {{ $service->client->company_name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">Amount *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="amount" name="amount" type="number" step="0.01" value="{{ old('amount') }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_date">Payment Date *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="payment_date" name="payment_date" type="date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="payment_method">Payment Method</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="payment_method" name="payment_method" type="text" value="{{ old('payment_method') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="transaction_reference">Transaction Reference</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="transaction_reference" name="transaction_reference" type="text" value="{{ old('transaction_reference') }}">
        </div>
        <div class="md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">Notes</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="mt-6 flex items-center justify-end">
        <a href="{{ route('payments.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-4">Cancel</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Record Payment</button>
    </div>
</form>
@endsection

