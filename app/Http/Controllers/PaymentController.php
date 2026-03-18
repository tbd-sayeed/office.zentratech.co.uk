<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use App\Models\Client;
use App\Mail\PaymentConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\EmailLog;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['client', 'service']);

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(15);
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();

        return view('payments.index', compact('payments', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $serviceId = $request->get('service_id');
        $clientId = $request->get('client_id');
        
        $services = Service::where('is_active', true)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();

        return view('payments.create', compact('services', 'clients', 'serviceId', 'clientId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $payment = Payment::create($validated);

            // Update service paid amount
            $service = Service::findOrFail($validated['service_id']);
            $service->increment('paid_amount', $validated['amount']);

            // Send payment confirmation email
            try {
                $client = $service->client;
                Mail::to($client->email)->send(new PaymentConfirmationMail($payment, $service, $client));
                
                $payment->update(['email_sent' => true]);
                
                EmailLog::create([
                    'client_id' => $client->id,
                    'service_id' => $service->id,
                    'email_type' => 'payment_confirmation',
                    'recipient_email' => $client->email,
                    'subject' => 'Payment Confirmation - ' . $service->service_name,
                    'body' => 'Payment confirmation email sent',
                    'status' => 'sent',
                    'sent_at' => now(),
                ]);
            } catch (\Exception $e) {
                EmailLog::create([
                    'client_id' => $client->id,
                    'service_id' => $service->id,
                    'email_type' => 'payment_confirmation',
                    'recipient_email' => $client->email,
                    'subject' => 'Payment Confirmation - ' . $service->service_name,
                    'body' => 'Payment confirmation email failed',
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }
        });

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully and confirmation email sent.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['client', 'service']);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $services = Service::where('is_active', true)
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $clients = Client::where('is_active', true)->orderBy('company_name')->get();

        return view('payments.edit', compact('payment', 'services', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $payment) {
            $oldAmount = $payment->amount;
            $oldServiceId = $payment->service_id;
            
            $payment->update($validated);

            // Update old service paid amount
            if ($oldServiceId != $validated['service_id']) {
                $oldService = Service::findOrFail($oldServiceId);
                $oldService->decrement('paid_amount', $oldAmount);
            } else {
                $oldService = Service::findOrFail($oldServiceId);
                $oldService->decrement('paid_amount', $oldAmount);
            }

            // Update new service paid amount
            $service = Service::findOrFail($validated['service_id']);
            $service->increment('paid_amount', $validated['amount']);
        });

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        DB::transaction(function () use ($payment) {
            $service = $payment->service;
            $service->decrement('paid_amount', $payment->amount);
            $payment->delete();
        });

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}
