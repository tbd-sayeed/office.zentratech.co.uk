@extends('layouts.app')

@section('title', 'Create Payment')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Record Payment</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('payments.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="client_id" class="form-label fw-medium">Client <span class="text-danger">*</span></label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $clientId ?? '') == $client->id ? 'selected' : '' }}>{{ $client->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="service_id" class="form-label fw-medium">Service <span class="text-danger">*</span></label>
                    <select name="service_id" id="service_id" class="form-select" required>
                        <option value="">Select Service</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" data-client-id="{{ $service->client_id }}" data-currency="{{ $service->currency ?? 'GBP' }}" data-due-amount="{{ $service->due_amount }}" {{ old('service_id', $serviceId ?? '') == $service->id ? 'selected' : '' }}>{{ $service->service_name }} - {{ $service->client->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="currency" class="form-label fw-medium">Currency</label>
                    @include('partials.currency-select', ['name' => 'currency', 'id' => 'payment_currency', 'value' => old('currency')])
                    <small class="text-muted">Defaults to service currency when selected</small>
                </div>
                <div class="col-md-6">
                    <label for="amount" class="form-label fw-medium">Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                </div>
                <div class="col-md-6">
                    <label for="discount" class="form-label fw-medium">Discount</label>
                    <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="{{ old('discount', 0) }}" placeholder="Amount waived when client pays less">
                    <small class="text-muted">Adjust shortfall when client doesn't pay full amount</small>
                </div>
                <div id="due-amount-display" class="col-12 d-none">
                    <small class="text-muted">Due amount: <strong id="due-amount-value" class="text-primary">—</strong></small>
                    <small class="text-muted ms-2">(Amount + Discount cannot exceed due)</small>
                    <div id="amount-exceeds-error" class="text-danger small mt-1 d-none">Amount + Discount cannot exceed due amount.</div>
                </div>
                <div class="col-md-6">
                    <label for="payment_date" class="form-label fw-medium">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="payment_method" class="form-label fw-medium">Payment Method</label>
                    <input type="text" class="form-control" id="payment_method" name="payment_method" value="{{ old('payment_method') }}" placeholder="Bank Transfer, Card, etc.">
                </div>
                <div class="col-md-6">
                    <label for="transaction_reference" class="form-label fw-medium">Transaction Reference</label>
                    <input type="text" class="form-control" id="transaction_reference" name="transaction_reference" value="{{ old('transaction_reference') }}">
                </div>
                <div class="col-12">
                    <label for="notes" class="form-label fw-medium">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Record Payment</button>
            </div>
        </form>
    </div>
</div>
<script>
const CURRENCY_SYMBOLS = { 'GBP': '£', 'USD': '$', 'EUR': '€', 'BDT': '৳' };

function updateDueAmount() {
    const serviceSel = document.getElementById('service_id');
    const opt = serviceSel.options[serviceSel.selectedIndex];
    const dueDisplay = document.getElementById('due-amount-display');
    const dueValue = document.getElementById('due-amount-value');

    if (opt && opt.value) {
        const due = parseFloat(opt.dataset.dueAmount || 0);
        const cur = opt.dataset.currency || 'GBP';
        const sym = CURRENCY_SYMBOLS[cur] || cur + ' ';
        dueDisplay.classList.remove('d-none');
        dueValue.textContent = sym + due.toFixed(2);
        return due;
    } else {
        dueDisplay.classList.add('d-none');
        return 0;
    }
}

function validateAmount() {
    const amountEl = document.getElementById('amount');
    const discountEl = document.getElementById('discount');
    const serviceSel = document.getElementById('service_id');
    const opt = serviceSel.options[serviceSel.selectedIndex];
    const errEl = document.getElementById('amount-exceeds-error');

    if (!opt || !opt.value) return true;
    const maxDue = parseFloat(opt.dataset.dueAmount || 0);
    const amount = parseFloat(amountEl.value) || 0;
    const discount = parseFloat(discountEl.value) || 0;

    if (amount + discount > maxDue) {
        errEl.classList.remove('d-none');
        amountEl.classList.add('is-invalid');
        if (discount > 0) discountEl.classList.add('is-invalid');
        return false;
    }
    errEl.classList.add('d-none');
    amountEl.classList.remove('is-invalid');
    discountEl.classList.remove('is-invalid');
    return true;
}

document.getElementById('service_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (opt && opt.value) {
        const cur = opt.dataset.currency || 'GBP';
        const sel = document.getElementById('payment_currency');
        if (sel) sel.value = cur;
    }
    updateDueAmount();
    validateAmount();
});

document.getElementById('amount').addEventListener('input', validateAmount);
document.getElementById('amount').addEventListener('blur', validateAmount);
document.getElementById('discount').addEventListener('input', validateAmount);
document.getElementById('discount').addEventListener('blur', validateAmount);

document.querySelector('form').addEventListener('submit', function(e) {
    if (!validateAmount()) {
        e.preventDefault();
        document.getElementById('amount').focus();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('service_id').dispatchEvent(new Event('change'));
});
</script>
@endsection
