<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfitShare;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProfitShareController extends Controller
{
    public function index(Request $request)
    {
        $supplierId = $request->input('supplier_id');
        $status = $request->input('status');
        $payoutStatus = $request->input('payout_status');

        $shares = ProfitShare::with(['order', 'product', 'supplier'])
            ->when($supplierId, fn ($q) => $q->where('supplier_id', $supplierId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($payoutStatus, fn ($q) => $q->where('payout_status', $payoutStatus))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalSales = (float) ProfitShare::where('status', 'completed')->sum('selling_price');
        $totalAdminProfit = (float) ProfitShare::where('status', 'completed')->sum('admin_amount');
        $totalSupplierProfit = (float) ProfitShare::where('status', 'completed')->sum('supplier_amount');
        $unpaidSupplierProfit = (float) ProfitShare::where('status', 'completed')->where('payout_status', 'unpaid')->sum('supplier_amount');

        $suppliers = Supplier::all();

        return view('admin.profit_sharing.index', compact(
            'shares',
            'suppliers',
            'supplierId',
            'status',
            'payoutStatus',
            'totalSales',
            'totalAdminProfit',
            'totalSupplierProfit',
            'unpaidSupplierProfit'
        ));
    }
}

