<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfitShare;
use App\Models\Supplier;
use App\Models\SupplierPayout;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $supplierId = $request->input('supplier_id');

        $metrics = $this->reportService->getFinancialMetrics($startDate, $endDate);

        $salesQuery = $this->reportService->getSalesReportQuery($startDate, $endDate, $supplierId);
        $salesRecords = $salesQuery->paginate(15)->withQueryString();

        $suppliers = Supplier::all();

        return view('admin.reports.index', compact('metrics', 'salesRecords', 'suppliers', 'startDate', 'endDate', 'supplierId'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $supplierId = $request->input('supplier_id');

        $metrics = $this->reportService->getFinancialMetrics($startDate, $endDate);
        $records = $this->reportService->getSalesReportQuery($startDate, $endDate, $supplierId)->get();
        $supplier = $supplierId ? Supplier::find($supplierId) : null;

        $pdf = Pdf::loadView('admin.reports.pdf_template', [
            'records' => $records,
            'metrics' => $metrics,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'supplier' => $supplier,
            'title' => 'Laporan Penjualan & Pembagian Hasil Konsinyasi',
            'generated_at' => now()->translatedFormat('d F Y H:i'),
        ]);

        return $pdf->download('laporan_konsinyasi_' . date('Ymd_His') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $supplierId = $request->input('supplier_id');

        $records = $this->reportService->getSalesReportQuery($startDate, $endDate, $supplierId)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_penjualan_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'No',
                'No Order',
                'Tanggal',
                'Kode Produk',
                'Nama Produk',
                'Supplier',
                'Harga Jual (Rp)',
                '% Admin',
                'Keuntungan Admin (Rp)',
                '% Supplier',
                'Hak Supplier (Rp)',
                'Status Payout',
            ]);

            $i = 1;
            foreach ($records as $r) {
                fputcsv($handle, [
                    $i++,
                    $r->order?->order_number ?? '-',
                    $r->created_at->format('Y-m-d H:i'),
                    $r->product?->code ?? '-',
                    $r->product?->name ?? '-',
                    $r->supplier?->name ?? '-',
                    $r->selling_price,
                    $r->admin_commission_pct,
                    $r->admin_amount,
                    $r->supplier_commission_pct,
                    $r->supplier_amount,
                    $r->payout_status === 'paid' ? 'Sudah Dicairkan' : 'Belum Dicairkan',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}

