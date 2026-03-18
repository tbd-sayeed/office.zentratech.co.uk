@extends('layouts.app')

@section('title', 'Create Service')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900">Create New Service</h1>
</div>

<form method="POST" action="{{ route('services.store') }}" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
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
            <label class="block text-gray-700 text-sm font-bold mb-2" for="service_type">Service Type *</label>
            <select name="service_type" id="service_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                <option value="domain_hosting" {{ old('service_type') == 'domain_hosting' ? 'selected' : '' }}>Domain & Hosting</option>
                <option value="web_mobile_dev" {{ old('service_type') == 'web_mobile_dev' ? 'selected' : '' }}>Web/Mobile Development</option>
                <option value="custom" {{ old('service_type') == 'custom' ? 'selected' : '' }}>Custom Service</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="service_name">Service Name *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="service_name" name="service_name" type="text" value="{{ old('service_name') }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="total_amount">Total Amount *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="total_amount" name="total_amount" type="number" step="0.01" value="{{ old('total_amount') }}" required>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="paid_amount">Paid Amount</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="paid_amount" name="paid_amount" type="number" step="0.01" value="{{ old('paid_amount', 0) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="start_date">Start Date *</label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="start_date" name="start_date" type="date" value="{{ old('start_date') }}" required>
        </div>

        <!-- Domain & Hosting Fields -->
        <div id="domain_fields" class="md:col-span-2" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Domain & Hosting Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="domain_name">Domain Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="domain_name" name="domain_name" type="text" value="{{ old('domain_name') }}" placeholder="example.com">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="hosting_package">Hosting Package</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="hosting_package" name="hosting_package" type="text" value="{{ old('hosting_package') }}" placeholder="Basic, Premium, etc.">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="expiration_date">Expiration Date</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="expiration_date" name="expiration_date" type="date" value="{{ old('expiration_date') }}">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="provider_name">Provider Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="provider_name" name="provider_name" type="text" value="{{ old('provider_name') }}" placeholder="Namecheap, GoDaddy, cPanel, Cloudways, etc.">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="credentials">Credentials / Notes</label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="credentials" name="credentials" rows="3" placeholder="Store login credentials or important notes here">{{ old('credentials') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Note: Store credentials securely. Consider encrypting sensitive information.</p>
                </div>
            </div>
        </div>

        <!-- Web/Mobile Development Fields -->
        <div id="web_mobile_fields" class="md:col-span-2" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Web/Mobile Development Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="project_type">Project Type</label>
                    <select name="project_type" id="project_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">Select Type</option>
                        <option value="website" {{ old('project_type') == 'website' ? 'selected' : '' }}>Website</option>
                        <option value="mobile_app" {{ old('project_type') == 'mobile_app' ? 'selected' : '' }}>Mobile App</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="delivery_date">Delivery Date</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="delivery_date" name="delivery_date" type="date" value="{{ old('delivery_date') }}">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contract_start_date">Contract Start Date</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="contract_start_date" name="contract_start_date" type="date" value="{{ old('contract_start_date') }}">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="contract_end_date">Contract End Date</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="contract_end_date" name="contract_end_date" type="date" value="{{ old('contract_end_date') }}">
                </div>
            </div>
        </div>

        <!-- Custom Service Fields -->
        <div id="custom_fields" class="md:col-span-2" style="display: none;">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Custom Service Details</h3>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="custom_service_type">Service Type</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="custom_service_type" name="custom_service_type" type="text" value="{{ old('custom_service_type') }}" placeholder="SEO, Maintenance, Retainer, Branding, etc.">
            </div>
        </div>

        <div class="md:col-span-2">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="notes">General Notes</label>
            <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="notes" name="notes" rows="3" placeholder="Additional notes about this service">{{ old('notes') }}</textarea>
        </div>
    </div>
    <div class="mt-6 flex items-center justify-end">
        <a href="{{ route('services.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-4">Cancel</a>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create Service</button>
    </div>
</form>

<script>
document.getElementById('service_type').addEventListener('change', function() {
    const domainFields = document.getElementById('domain_fields');
    const webMobileFields = document.getElementById('web_mobile_fields');
    const customFields = document.getElementById('custom_fields');
    
    // Hide all fields first
    domainFields.style.display = 'none';
    webMobileFields.style.display = 'none';
    customFields.style.display = 'none';
    
    // Show relevant fields based on selection
    if (this.value === 'domain_hosting') {
        domainFields.style.display = 'block';
    } else if (this.value === 'web_mobile_dev') {
        webMobileFields.style.display = 'block';
    } else if (this.value === 'custom') {
        customFields.style.display = 'block';
    }
});

// Trigger on page load if service_type is already selected
document.addEventListener('DOMContentLoaded', function() {
    const serviceType = document.getElementById('service_type');
    if (serviceType.value) {
        serviceType.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

