<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Exception;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $suppliers = Supplier::when($search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('bank_account_holder', 'like', "%{$search}%");
            })
            ->when($status, fn ($q) => $q->where('status', $status))
            ->withCount('products')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.suppliers.index', compact('suppliers', 'search', 'status'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['required', 'string'],
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_holder' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $supplier = Supplier::create($validated);

        return redirect()->route('admin.suppliers.show', $supplier->id)
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(int $id)
    {
        $supplier = Supplier::with(['products.primaryImage', 'payouts' => fn ($q) => $q->latest()])
            ->findOrFail($id);

        $profitShares = $supplier->profitShares()
            ->with(['order', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.suppliers.show', compact('supplier', 'profitShares'));
    }

    public function edit(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        return view('admin.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:25'],
            'email' => ['nullable', 'email', 'max:100'],
            'address' => ['required', 'string'],
            'bank_name' => ['required', 'string', 'max:50'],
            'bank_account_number' => ['required', 'string', 'max:50'],
            'bank_account_holder' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $supplier->update($validated);

        return redirect()->route('admin.suppliers.show', $supplier->id)
            ->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        // Safeguard historical financial integrity:
        $hasHistoricalTransactions = $supplier->profitShares()->exists() || $supplier->payouts()->exists();

        if ($hasHistoricalTransactions) {
            // Soft delete or set inactive rather than hard delete
            $supplier->update(['status' => 'inactive']);
            $supplier->delete(); // Uses SoftDeletes

            return redirect()->route('admin.suppliers.index')
                ->with('success', 'Supplier memiliki riwayat transaksi sehingga dinonaktifkan dan diarsipkan dengan aman (Soft Delete).');
        }

        $supplier->forceDelete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}

