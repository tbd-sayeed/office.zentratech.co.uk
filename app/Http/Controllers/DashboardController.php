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
        
        // Upcoming renewals (30 days)
        $upcomingRenewals = Service::where('is_active', true)
            ->where('service_type', 'domain_hosting')
            ->whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [now(), now()->addDays(30)])
            ->count();

        // Payments received this month
        $paymentsReceived = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Payments due (outstanding)
        $paymentsDue = Service::where('is_active', true)
            ->selectRaw('SUM(total_amount - paid_amount) as total_due')
            ->first()
            ->total_due ?? 0;

        // Contracts expiring soon (30 days)
        $contractsExpiring = Service::where('is_active', true)
            ->where('service_type', 'web_mobile_dev')
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

        // Upcoming renewals list
        $renewalsList = Service::where('is_active', true)
            ->where('service_type', 'domain_hosting')
            ->whereNotNull('expiration_date')
            ->whereBetween('expiration_date', [now(), now()->addDays(30)])
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
            'contractsExpiring',
            'recentClients',
            'recentServices',
            'renewalsList',
            'monthlyRevenue'
        ));
    }
}
