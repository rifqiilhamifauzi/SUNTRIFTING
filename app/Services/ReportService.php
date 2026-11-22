<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProfitShare;
use App\Models\Supplier;
use App\Models\SupplierPayout;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getFinancialMetrics(?string $startDate = null, ?string $endDate = null): array
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subMonths(3)->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        // Total sales from completed/processing/shipped/paid orders
        $totalSales = (float) Order::whereIn('payment_status', ['paid'])
            ->whereBetween('created_at', [$start, $end])
            ->sum('subtotal');

        // Total admin profit from completed profit shares
        $totalAdminProfit = (float) ProfitShare::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('admin_amount');

        // Total supplier share
        $totalSupplierProfit = (float) ProfitShare::where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->sum('supplier_amount');

        // Total supplier payouts actually paid
        $totalSupplierPaid = (float) SupplierPayout::where('status', 'paid')
            ->whereBetween('paid_at', [$start, $end])
            ->sum('amount');

        // Overall unpaid supplier liabilities (all time unpaid balance)
        $unpaidSupplierLiabilities = (float) ProfitShare::where('status', 'completed')
            ->where('payout_status', 'unpaid')
            ->sum('supplier_amount');

        // Successful transaction count
        $successfulOrdersCount = Order::whereIn('payment_status', ['paid'])
            ->whereBetween('created_at', [$start, $end])
            ->count();

        // 7-day sales trend for Chart.js
        $chartDates = [];
        $chartSales = [];
        $chartAdminProfits = [];

        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $dayStr = $day->format('Y-m-d');
            $dayLabel = $day->translatedFormat('d M');

            $daySale = (float) Order::whereIn('payment_status', ['paid'])
                ->whereDate('created_at', $dayStr)
                ->sum('subtotal');

            $dayAdminProfit = (float) ProfitShare::where('status', 'completed')
                ->whereDate('created_at', $dayStr)
                ->sum('admin_amount');

            $chartDates[] = $dayLabel;
            $chartSales[] = $daySale;
            $chartAdminProfits[] = $dayAdminProfit;
        }

        return [
            'total_sales' => $totalSales,
            'total_admin_profit' => $totalAdminProfit,
            'total_supplier_profit' => $totalSupplierProfit,
            'total_supplier_paid' => $totalSupplierPaid,
            'unpaid_supplier_liabilities' => $unpaidSupplierLiabilities,
            'successful_orders_count' => $successfulOrdersCount,
            'chart_dates' => $chartDates,
            'chart_sales' => $chartSales,
            'chart_admin_profits' => $chartAdminProfits,
            'start_date' => $start->format('Y-m-d'),
            'end_date' => $end->format('Y-m-d'),
        ];
    }

    public function getSalesReportQuery(?string $startDate = null, ?string $endDate = null, ?int $supplierId = null)
    {
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : Carbon::now()->subMonth()->startOfDay();
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : Carbon::now()->endOfDay();

        return ProfitShare::with(['order', 'product', 'supplier'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [$start, $end])
            ->when($supplierId, fn ($q) => $q->where('supplier_id', $supplierId))
            ->orderBy('created_at', 'desc');
    }

    public function generatePdfReport(array $data, string $title)
    {
        $pdf = Pdf::loadView('admin.reports.pdf_template', [
            'records' => $data,
            'title' => $title,
            'generated_at' => now()->translatedFormat('d F Y H:i'),
        ]);

        return $pdf->download(str_replace(' ', '_', strtolower($title)) . '_' . date('Ymd_His') . '.pdf');
    }
}

