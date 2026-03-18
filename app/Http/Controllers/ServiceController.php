<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Client;
use App\Mail\ServiceWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailLog;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Service::with('client');

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        $services = $query->orderBy('created_at', 'desc')->paginate(15);
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();

        return view('services.index', compact('services', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clientId = $request->get('client_id');
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();
        
        return view('services.create', compact('clients', 'clientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type' => 'required|in:domain_hosting,web_mobile_dev,custom',
            'service_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
            
            // Domain & Hosting fields
            'domain_name' => 'nullable|string|max:255',
            'hosting_package' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'provider_name' => 'nullable|string|max:255',
            'credentials' => 'nullable|string',
            
            // Web/Mobile Dev fields
            'project_type' => 'nullable|in:website,mobile_app',
            'delivery_date' => 'nullable|date',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            
            // Custom service fields
            'custom_service_type' => 'nullable|string|max:255',
        ]);

        $service = Service::create($validated);

        // Send welcome email
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
                'client_id' => $client->id,
                'service_id' => $service->id,
                'email_type' => 'welcome_service',
                'recipient_email' => $client->email,
                'subject' => 'Service Activated - ' . $service->service_name,
                'body' => 'Service welcome email failed',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('services.show', $service)
            ->with('success', 'Service created successfully and welcome email sent.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        $service->load(['client', 'payments', 'emailLogs']);
        return view('services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();
        return view('services.edit', compact('service', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_type' => 'required|in:domain_hosting,web_mobile_dev,custom',
            'service_name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
            
            // Domain & Hosting fields
            'domain_name' => 'nullable|string|max:255',
            'hosting_package' => 'nullable|string|max:255',
            'expiration_date' => 'nullable|date',
            'provider_name' => 'nullable|string|max:255',
            'credentials' => 'nullable|string',
            
            // Web/Mobile Dev fields
            'project_type' => 'nullable|in:website,mobile_app',
            'delivery_date' => 'nullable|date',
            'contract_start_date' => 'nullable|date',
            'contract_end_date' => 'nullable|date',
            
            // Custom service fields
            'custom_service_type' => 'nullable|string|max:255',
        ]);

        $service->update($validated);

        return redirect()->route('services.show', $service)
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $clientId = $service->client_id;
        $service->delete();

        return redirect()->route('clients.show', $clientId)
            ->with('success', 'Service deleted successfully.');
    }
}
