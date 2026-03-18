@extends('layouts.app')

@section('title', 'Create Client')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Create New Client</h1>
</div>

<form method="POST" action="{{ route('clients.store') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="company_name">Company Name *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="company_name" name="company_name" type="text" value="{{ old('company_name') }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="contact_person">Contact Person *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="contact_person" name="contact_person" type="text" value="{{ old('contact_person') }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" type="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="phone">Phone</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="phone" name="phone" type="text" value="{{ old('phone') }}">
        </div>
        <div class="md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="address">Address</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="address" name="address" rows="3">{{ old('address') }}</textarea>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="city">City</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="city" name="city" type="text" value="{{ old('city') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="state">State</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="state" name="state" type="text" value="{{ old('state') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="postal_code">Postal Code</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="postal_code" name="postal_code" type="text" value="{{ old('postal_code') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="country">Country</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="country" name="country" type="text" value="{{ old('country') }}">
        </div>
        <div class="md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">Notes</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="mt-6 flex items-center justify-end">
        <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-4">Cancel</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Client</button>
    </div>
</form>
@endsection

