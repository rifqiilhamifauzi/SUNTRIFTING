<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\SupplierPayout;
use App\Services\SupplierPayoutService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierPayoutController extends Controller
{
    protected SupplierPayoutService $payoutService;

    public function __construct(SupplierPayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    public function index(Request $request)
    {
        $supplierId = $request->input('supplier_id');

        $payouts = SupplierPayout::with(['supplier', 'processor', 'items'])
            ->when($supplierId, fn ($q) => $q->where('supplier_id', $supplierId))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $suppliers = Supplier::withCount('products')->get();

        return view('admin.payouts.index', compact('payouts', 'suppliers', 'supplierId'));
    }

    public function create(Request $request)
    {
        $selectedSupplierId = $request->input('supplier_id');
        $suppliers = Supplier::where('status', 'active')->get();
        $selectedSupplier = $selectedSupplierId ? Supplier::find($selectedSupplierId) : null;

        return view('admin.payouts.create', compact('suppliers', 'selectedSupplier'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'amount' => ['required', 'numeric', 'min:1000'],
            'payment_method' => ['required', 'string', 'max:50'],
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_holder' => ['required', 'string', 'max:100'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'proof_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $supplier = Supplier::findOrFail($validated['supplier_id']);

        try {
            $payout = $this->payoutService->createPayout(
                $supplier,
                (float) $validated['amount'],
                $validated,
                Auth::user(),
                $request->file('proof_image')
            );

            return redirect()->route('admin.payouts.index')
                ->with('success', "Pencairan dana sebesar Rp" . number_format($payout->amount, 0, ',', '.') . " kepada supplier {$supplier->name} berhasil dicatat.");
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(int $id)
    {
        $payout = SupplierPayout::with(['supplier', 'processor', 'items.profitShare.product', 'items.profitShare.order'])
            ->findOrFail($id);

        return view('admin.payouts.show', compact('payout'));
    }
}

