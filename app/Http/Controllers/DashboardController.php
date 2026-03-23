<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalClients = Client::where('is_active', true)->count();
        $totalServices = Service::where('is_active', true)->count();
        
        // Upcoming renewals (next 18 months – domain renewals are often annual)
        $renewalsWindowDays = 540;
        $upcomingRenewals = Service::where('is_active', true)
            ->whereHas('serviceType', fn($q) => $q->where('form_section', 'domain_hosting'))
            ->whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [now()->startOfDay(), now()->addDays($renewalsWindowDays)])
            ->count();

        // Payments received this month
        $paymentsReceived = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Payments due (outstanding, after discount)
        $paymentsDue = Service::where('is_active', true)
            ->selectRaw('SUM(total_amount - COALESCE(discount, 0) - paid_amount) as total_due')
            ->first()
            ->total_due ?? 0;

        // Total profit (client net - team cost), in GBP
        $totalProfit = Service::where('is_active', true)
            ->with('teamAssignments')
            ->get()
            ->sum(fn ($s) => $s->profit);

        // Contracts expiring soon (30 days) - development & project-based services
        $contractsExpiring = Service::where('is_active', true)
            ->whereHas('serviceType', fn($q) => $q->where('form_section', 'project_based'))
            ->whereNotNull('contract_end_date')
            ->whereBetween('contract_end_date', [now(), now()->addDays(30)])
            ->count();

        // Recent clients
        $recentClients = Client::orderBy('created_at', 'desc')->limit(5)->get();

        // Recent services
        $recentServices = Service::with('client')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Upcoming renewals list (same window as above)
        $renewalsList = Service::where('is_active', true)
            ->whereHas('serviceType', fn($q) => $q->where('form_section', 'domain_hosting'))
            ->whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [now()->startOfDay(), now()->addDays($renewalsWindowDays)])
            ->with('client')
            ->orderBy('expiration_date', 'asc')
            ->limit(10)
            ->get();

        // Monthly revenue (last 6 months)
        $monthlyRevenue = Payment::select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('payment_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        return view('dashboard.index', compact(
            'totalClients',
            'totalServices',
            'upcomingRenewals',
            'paymentsReceived',
            'paymentsDue',
            'totalProfit',
            'contractsExpiring',
            'recentClients',
            'recentServices',
            'renewalsList',
            'monthlyRevenue'
        ));
    }
}
