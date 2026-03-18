<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Mail\ClientWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailLog;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::with(['services', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $client = Client::create($validated);

        // Send welcome email
        try {
            Mail::to($client->email)->send(new ClientWelcomeMail($client));
            
            EmailLog::create([
                'client_id' => $client->id,
                'email_type' => 'welcome_client',
                'recipient_email' => $client->email,
                'subject' => 'Welcome to ZentraTech',
                'body' => 'Welcome email sent',
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            EmailLog::create([
                'client_id' => $client->id,
                'email_type' => 'welcome_client',
                'recipient_email' => $client->email,
                'subject' => 'Welcome to ZentraTech',
                'body' => 'Welcome email failed',
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client created successfully and welcome email sent.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['services' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'payments' => function($query) {
            $query->orderBy('payment_date', 'desc');
        }, 'emailLogs' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $client->update($validated);

        return redirect()->route('clients.show', $client)
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}
