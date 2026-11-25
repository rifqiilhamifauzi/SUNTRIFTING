<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Shipment;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $orderStatus = $request->input('order_status');

        // LOGIKA BARU: Daftar Pesanan Customer HANYA berisikan pesanan yang SUDAH TERVERIFIKASI (sudah bayar)
        $orders = Order::with(['user', 'latestPayment', 'shipment', 'items'])
            ->where('payment_status', 'paid')
            ->when($search, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('order_number', 'like', "%{$search}%")
                        ->orWhere('recipient_name', 'like', "%{$search}%")
                        ->orWhere('recipient_phone', 'like', "%{$search}%");
                });
            })
            ->when($orderStatus, fn ($q) => $q->where('order_status', $orderStatus))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        // Hitung statistik untuk tab & badge
        $counts = [
            'all_verified' => Order::where('payment_status', 'paid')->count(),
            'processing' => Order::where('payment_status', 'paid')->where('order_status', 'processing')->count(),
            'shipped' => Order::where('payment_status', 'paid')->where('order_status', 'shipped')->count(),
            'completed' => Order::where('payment_status', 'paid')->where('order_status', 'completed')->count(),
            'needs_verification' => \App\Models\Payment::where('status', 'pending')->whereNotNull('proof_image')->count(),
            'unpaid' => \App\Models\Payment::where('status', 'pending')->whereNull('proof_image')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'search', 'orderStatus', 'counts'));
    }

    public function show(int $id)
    {
        $order = Order::with([
            'user',
            'items.product.primaryImage',
            'items.supplier',
            'payments.verifier',
            'latestPayment',
            'shipment',
            'statusHistories.changer',
            'profitShares.supplier',
        ])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_status' => ['required', 'in:pending_payment,waiting_verification,processing,shipped,completed,cancelled,refunded'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $oldStatus = $order->order_status;
        $order->update(['order_status' => $validated['order_status']]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => $validated['order_status'],
            'notes' => $validated['notes'] ?: 'Status pesanan diubah menjadi: ' . $validated['order_status'],
            'changed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function updateShipping(Request $request, int $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'courier' => ['required', 'string', 'max:100'],
            'tracking_number' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $shipment = $order->shipment ?: new Shipment(['order_id' => $order->id]);
        $shipment->fill([
            'courier' => $validated['courier'],
            'tracking_number' => $validated['tracking_number'],
            'status' => 'shipped',
            'shipped_at' => now(),
            'notes' => $validated['notes'] ?? null,
        ]);
        $shipment->save();

        $oldStatus = $order->order_status;
        $order->update(['order_status' => 'shipped']);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => $oldStatus,
            'new_status' => 'shipped',
            'notes' => "Pesanan telah dikirim via {$validated['courier']} dengan Resi: {$validated['tracking_number']}.",
            'changed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Nomor resi pengiriman berhasil disimpan dan status pesanan diubah ke Shipped.');
    }
}

