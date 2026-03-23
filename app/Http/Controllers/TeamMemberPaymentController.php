<?php

namespace App\Http\Controllers;

use App\Models\TeamMember;
use App\Models\TeamMemberPayment;
use App\Models\Service;
use Illuminate\Http\Request;

class TeamMemberPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = TeamMemberPayment::with(['teamMember', 'service.client']);

        if ($request->filled('team_member_id')) {
            $query->where('team_member_id', $request->team_member_id);
        }
        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(20);
        $teamMembers = TeamMember::where('is_active', true)->orderBy('name')->get();
        $services = Service::with('client')->orderBy('created_at', 'desc')->limit(200)->get();

        return view('team-member-payments.index', compact('payments', 'teamMembers', 'services'));
    }

    public function create(Request $request)
    {
        $teamMemberId = $request->get('team_member_id');
        $serviceId = $request->get('service_id');
        $teamMembers = TeamMember::where('is_active', true)->orderBy('name')->get();
        $services = Service::with('client')->orderBy('created_at', 'desc')->get();
        return view('team-member-payments.create', compact('teamMembers', 'services', 'teamMemberId', 'serviceId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_member_id' => 'required|exists:team_members,id',
            'service_id' => 'nullable|exists:services,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string|in:GBP,USD,EUR,BDT',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $validated['currency'] = $validated['currency'] ?? 'USD';

        TeamMemberPayment::create($validated);
        return redirect()->route('team-member-payments.index')
            ->with('success', 'Payment to team member recorded successfully.');
    }

    public function destroy(TeamMemberPayment $teamMemberPayment)
    {
        $teamMemberPayment->delete();
        return redirect()->back()->with('success', 'Payment record deleted.');
    }
}
