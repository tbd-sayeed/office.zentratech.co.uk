<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Client;
use App\Models\ServiceType;
use App\Models\ProjectType;
use App\Models\TeamMember;
use App\Models\ServiceTeamAssignment;
use App\Mail\ServiceWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailLog;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['client', 'serviceType', 'teamAssignments']);

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('service_type_id')) {
            $query->where('service_type_id', $request->service_type_id);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15);
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();
        $serviceTypes = ServiceType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('services.index', compact('services', 'clients', 'serviceTypes'));
    }

    public function create(Request $request)
    {
        $clientId = $request->get('client_id');
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();
        $serviceTypes = ServiceType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $projectTypes = ProjectType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $teamMembers = TeamMember::where('is_active', true)->orderBy('name')->get();

        return view('services.create', compact('clientId', 'clients', 'serviceTypes', 'projectTypes', 'teamMembers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type_id' => 'required|exists:service_types,id',
            'project_type_id' => 'nullable|exists:project_types,id',
            'service_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',

            'domain_name' => 'nullable|string|max:255',
            'hosting_package' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'provider_name' => 'nullable|string|max:255',
            'credentials' => 'nullable|string',

            'delivery_date' => 'nullable|date',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',

            'custom_service_type' => 'nullable|string|max:255',
            'currency' => 'nullable|string|in:GBP,USD,EUR,BDT',
        ]);
        $validated['currency'] = $validated['currency'] ?? 'GBP';

        $service = Service::create($validated);

        if ($request->has('team_assignments') && is_array($request->team_assignments)) {
            foreach ($request->team_assignments as $ta) {
                if (!empty($ta['team_member_id']) && isset($ta['agreed_amount']) && $ta['agreed_amount'] >= 0) {
                    ServiceTeamAssignment::create([
                        'service_id' => $service->id,
                        'team_member_id' => $ta['team_member_id'],
                        'agreed_amount' => $ta['agreed_amount'],
                        'currency' => $ta['currency'] ?? 'USD',
                        'notes' => $ta['notes'] ?? null,
                    ]);
                }
            }
        }

        try {
            $client = $service->client;
            Mail::to($client->email)->send(new ServiceWelcomeMail($service, $client));

            EmailLog::create([
                'client_id' => $client->id,
                'service_id' => $service->id,
                'email_type' => 'welcome_service',
                'recipient_email' => $client->email,
                'subject' => 'Service Activated - ' . $service->service_name,
                'body' => 'Service welcome email sent',
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            EmailLog::create([
                'client_id' => $service->client_id,
                'service_id' => $service->id,
                'email_type' => 'welcome_service',
                'recipient_email' => $service->client->email,
                'subject' => 'Service Activated - ' . $service->service_name,
                'body' => 'Service welcome email failed',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('services.show', $service)
            ->with('success', 'Service created successfully and welcome email sent.');
    }

    public function show(Service $service)
    {
        $service->load(['client', 'serviceType', 'projectType', 'payments', 'emailLogs', 'teamAssignments.teamMember']);
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        $service->load('teamAssignments.teamMember');
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();
        $serviceTypes = ServiceType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $projectTypes = ProjectType::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $teamMembers = TeamMember::where('is_active', true)->orderBy('name')->get();

        return view('services.edit', compact('service', 'clients', 'serviceTypes', 'projectTypes', 'teamMembers'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type_id' => 'required|exists:service_types,id',
            'project_type_id' => 'nullable|exists:project_types,id',
            'service_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',

            'domain_name' => 'nullable|string|max:255',
            'hosting_package' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'provider_name' => 'nullable|string|max:255',
            'credentials' => 'nullable|string',

            'delivery_date' => 'nullable|date',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',

            'custom_service_type' => 'nullable|string|max:255',
            'currency' => 'nullable|string|in:GBP,USD,EUR,BDT',
        ]);
        $validated['currency'] = $validated['currency'] ?? $service->currency ?? 'GBP';
        $validated['is_active'] = $request->boolean('is_active', $service->is_active);

        $service->update($validated);

        if ($request->has('team_assignments') && is_array($request->team_assignments)) {
            $service->teamAssignments()->delete();
            foreach ($request->team_assignments as $ta) {
                if (!empty($ta['team_member_id']) && isset($ta['agreed_amount']) && $ta['agreed_amount'] >= 0) {
                    ServiceTeamAssignment::create([
                        'service_id' => $service->id,
                        'team_member_id' => $ta['team_member_id'],
                        'agreed_amount' => $ta['agreed_amount'],
                        'currency' => $ta['currency'] ?? 'USD',
                        'notes' => $ta['notes'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $clientId = $service->client_id;
        $service->delete();

        if ($clientId) {
            return redirect()->route('clients.show', $clientId)
                ->with('success', 'Service deleted successfully.');
        }
        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}
