<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProfitShare;
use App\Models\Supplier;
use App\Services\ReportService;

class DashboardController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $metrics = $this->reportService->getFinancialMetrics();

        $stats = [
            'total_products' => Product::count(),
            'available_products' => Product::where('status', 'available')->count(),
            'sold_products' => Product::where('status', 'sold')->count(),
            'total_suppliers' => Supplier::count(),
            'total_orders' => Order::count(),
            'pending_verifications' => Payment::where('status', 'pending')->whereNotNull('proof_image')->count(),
            'pending_shipments' => Order::where('order_status', 'processing')->count(),
        ];

        $recentOrders = Order::with(['user', 'latestPayment', 'shipment'])
            ->latest()
            ->take(6)
            ->get();

        $pendingPayments = Payment::with(['order.user'])
            ->where('status', 'pending')
            ->whereNotNull('proof_image')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('metrics', 'stats', 'recentOrders', 'pendingPayments'));
    }
}

