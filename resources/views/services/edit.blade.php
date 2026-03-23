@extends('layouts.app')

@section('title', 'Edit Service')

@section('content')
<div class="mb-4">
    <h1 class="h3 fw-bold text-dark">Edit Service</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('services.update', $service) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="client_id" class="form-label fw-medium">Client <span class="text-danger">*</span></label>
                    <select name="client_id" id="client_id" class="form-select" required>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client_id', $service->client_id) == $client->id ? 'selected' : '' }}>{{ $client->company_name }} ({{ $client->contact_person }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="service_type_id" class="form-label fw-medium">Service Type <span class="text-danger">*</span></label>
                    <select name="service_type_id" id="service_type_id" class="form-select" required>
                        @foreach($serviceTypes as $st)
                        <option value="{{ $st->id }}" data-form-section="{{ $st->form_section }}" {{ old('service_type_id', $service->service_type_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="service_name" class="form-label fw-medium">Service Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="service_name" name="service_name" value="{{ old('service_name', $service->service_name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="currency" class="form-label fw-medium">Contract Currency</label>
                    @include('partials.currency-select', ['name' => 'currency', 'value' => old('currency', $service->currency ?? 'GBP')])
                </div>
                <div class="col-md-6">
                    <label for="total_amount" class="form-label fw-medium">Total Amount <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" class="form-control" id="total_amount" name="total_amount" value="{{ old('total_amount', $service->total_amount) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="paid_amount" class="form-label fw-medium">Paid Amount</label>
                    <input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" value="{{ old('paid_amount', $service->paid_amount) }}">
                </div>
                <div class="col-md-6">
                    <label for="start_date" class="form-label fw-medium">Start Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $service->start_date->format('Y-m-d')) }}" required>
                </div>
                <div class="col-12">
                    <div class="form-check"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $service->is_active) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Active</label></div>
                </div>

                <div id="domain_fields" class="col-12 d-none">
                    <hr class="my-3">
                    <h6 class="fw-semibold mb-3">Domain & Hosting Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><label for="domain_name" class="form-label">Domain Name</label><input type="text" class="form-control" id="domain_name" name="domain_name" value="{{ old('domain_name', $service->domain_name) }}"></div>
                        <div class="col-md-6"><label for="hosting_package" class="form-label">Hosting Package</label><input type="text" class="form-control" id="hosting_package" name="hosting_package" value="{{ old('hosting_package', $service->hosting_package) }}"></div>
                        <div class="col-md-6"><label for="expiration_date" class="form-label">Expiration Date</label><input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ old('expiration_date', $service->expiration_date?->format('Y-m-d')) }}"></div>
                        <div class="col-md-6"><label for="provider_name" class="form-label">Provider</label><input type="text" class="form-control" id="provider_name" name="provider_name" value="{{ old('provider_name', $service->provider_name) }}"></div>
                        <div class="col-12"><label for="credentials" class="form-label">Credentials / Notes</label><textarea class="form-control" id="credentials" name="credentials" rows="2">{{ old('credentials', $service->credentials) }}</textarea></div>
                    </div>
                </div>

                <div id="web_mobile_fields" class="col-12 d-none">
                    <hr class="my-3">
                    <h6 class="fw-semibold mb-3">Project & Contract Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6"><label for="project_type_id" class="form-label">Project Type</label>
                            <select name="project_type_id" id="project_type_id" class="form-select">
                                <option value="">Select</option>
                                @foreach($projectTypes as $pt)
                                <option value="{{ $pt->id }}" {{ old('project_type_id', $service->project_type_id) == $pt->id ? 'selected' : '' }}>{{ $pt->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label for="delivery_date" class="form-label">Delivery Date</label><input type="date" class="form-control" id="delivery_date" name="delivery_date" value="{{ old('delivery_date', $service->delivery_date?->format('Y-m-d')) }}"></div>
                        <div class="col-md-6"><label for="contract_start_date" class="form-label">Contract Start</label><input type="date" class="form-control" id="contract_start_date" name="contract_start_date" value="{{ old('contract_start_date', $service->contract_start_date?->format('Y-m-d')) }}"></div>
                        <div class="col-md-6"><label for="contract_end_date" class="form-label">Contract End</label><input type="date" class="form-control" id="contract_end_date" name="contract_end_date" value="{{ old('contract_end_date', $service->contract_end_date?->format('Y-m-d')) }}"></div>
                    </div>
                </div>

                <div id="custom_fields" class="col-12 d-none">
                    <hr class="my-3">
                    <h6 class="fw-semibold mb-3">Custom Service Details</h6>
                    <div><label for="custom_service_type" class="form-label">Service Type</label><input type="text" class="form-control" id="custom_service_type" name="custom_service_type" value="{{ old('custom_service_type', $service->custom_service_type) }}"></div>
                </div>

                <div class="col-12">
                    <label for="notes" class="form-label fw-medium">General Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="2">{{ old('notes', $service->notes) }}</textarea>
                </div>

                <div class="col-12">
                    <hr class="my-3">
                    <h6 class="fw-semibold mb-3">Team Members Assigned to This Service</h6>
                    <p class="text-muted small mb-3">Assign team members and the agreed amount you'll pay them</p>
                    <div id="team_assignments">
                        @php $assignments = old('team_assignments', $service->teamAssignments->map(fn($a) => ['team_member_id' => $a->team_member_id, 'agreed_amount' => $a->agreed_amount, 'currency' => $a->currency ?? 'USD', 'notes' => $a->notes])->values()->toArray()); @endphp
                        @forelse($assignments as $idx => $ta)
                        <div class="row g-2 mb-2 team-assignment-row">
                            <div class="col-md-4">
                                <select name="team_assignments[{{ $idx }}][team_member_id]" class="form-select form-select-sm">
                                    <option value="">Select team member</option>
                                    @foreach($teamMembers as $tm)
                                    <option value="{{ $tm->id }}" {{ ($ta['team_member_id'] ?? '') == $tm->id ? 'selected' : '' }}>{{ $tm->name }}{{ $tm->role ? ' (' . $tm->role . ')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="0.01" name="team_assignments[{{ $idx }}][agreed_amount]" class="form-control form-control-sm" value="{{ $ta['agreed_amount'] ?? 0 }}">
                            </div>
                            <div class="col-md-2">
                                @include('partials.currency-select', ['name' => 'team_assignments[' . $idx . '][currency]', 'value' => $ta['currency'] ?? 'USD', 'class' => 'form-select-sm'])
                            </div>
                            <div class="col-md-2">
                                <input type="text" name="team_assignments[{{ $idx }}][notes]" class="form-control form-control-sm" placeholder="Notes" value="{{ $ta['notes'] ?? '' }}">
                            </div>
                            <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></div>
                        </div>
                        @empty
                        <div class="row g-2 mb-2 team-assignment-row">
                            <div class="col-md-4">
                                <select name="team_assignments[0][team_member_id]" class="form-select form-select-sm">
                                    <option value="">Select team member</option>
                                    @foreach($teamMembers as $tm)
                                    <option value="{{ $tm->id }}">{{ $tm->name }}{{ $tm->role ? ' (' . $tm->role . ')' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3"><input type="number" step="0.01" name="team_assignments[0][agreed_amount]" class="form-control form-control-sm" value="0"></div>
                            <div class="col-md-2">@include('partials.currency-select', ['name' => 'team_assignments[0][currency]', 'value' => 'USD', 'class' => 'form-select-sm'])</div>
                            <div class="col-md-2"><input type="text" name="team_assignments[0][notes]" class="form-control form-control-sm" placeholder="Notes"></div>
                            <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-row" style="visibility:hidden">×</button></div>
                        </div>
                        @endforelse
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add_team_row">+ Add Team Member</button>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <a href="{{ route('services.show', $service) }}" class="btn btn-outline-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Service</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('service_type_id').addEventListener('change', function() {
    ['domain_fields','web_mobile_fields','custom_fields'].forEach(id => document.getElementById(id).classList.add('d-none'));
    const opt = this.options[this.selectedIndex];
    const formSection = opt ? opt.dataset.formSection : '';
    if (formSection === 'domain_hosting') document.getElementById('domain_fields').classList.remove('d-none');
    else if (formSection === 'project_based') document.getElementById('web_mobile_fields').classList.remove('d-none');
    else if (formSection === 'custom') document.getElementById('custom_fields').classList.remove('d-none');
});
document.addEventListener('DOMContentLoaded', () => document.getElementById('service_type_id').dispatchEvent(new Event('change')));

const teamMembers = @json($teamMembers->map(fn($t) => ['id' => $t->id, 'name' => $t->name . ($t->role ? ' (' . $t->role . ')' : '')]));
let rowIdx = {{ count($assignments) ?: 1 }};
document.getElementById('add_team_row').addEventListener('click', function() {
    const row = document.createElement('div');
    row.className = 'row g-2 mb-2 team-assignment-row';
    row.innerHTML = '<div class="col-md-4"><select name="team_assignments[' + rowIdx + '][team_member_id]" class="form-select form-select-sm"><option value="">Select</option>' + teamMembers.map(t => '<option value="' + t.id + '">' + t.name + '</option>').join('') + '</select></div><div class="col-md-3"><input type="number" step="0.01" name="team_assignments[' + rowIdx + '][agreed_amount]" class="form-control form-control-sm" value="0"></div><div class="col-md-2"><select name="team_assignments[' + rowIdx + '][currency]" class="form-select form-select-sm"><option value="GBP">£ GBP</option><option value="USD" selected>$ USD</option><option value="EUR">€ EUR</option><option value="BDT">৳ BDT</option></select></div><div class="col-md-2"><input type="text" name="team_assignments[' + rowIdx + '][notes]" class="form-control form-control-sm"></div><div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger remove-row">×</button></div>';
    document.getElementById('team_assignments').appendChild(row);
    row.querySelector('.remove-row').onclick = () => row.remove();
    rowIdx++;
});
document.getElementById('team_assignments').addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-row')) e.target.closest('.team-assignment-row').remove();
});
</script>
@endsection
