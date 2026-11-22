<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Setting;
use App\Services\OrderService;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerOrderController extends Controller
{
    protected PaymentService $paymentService;
    protected OrderService $orderService;

    public function __construct(PaymentService $paymentService, OrderService $orderService)
    {
        $this->paymentService = $paymentService;
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        $status = $request->input('status');
        $user = Auth::user();

        $query = $user->orders()
            ->with(['items.product.primaryImage', 'latestPayment', 'shipment'])
            ->latest();

        if ($status === 'unpaid') {
            $query->where('order_status', 'pending_payment');
        } elseif ($status === 'verification') {
            $query->where('order_status', 'waiting_verification');
        } elseif ($status === 'processing') {
            $query->whereIn('order_status', ['processing', 'shipped']);
        } elseif ($status === 'completed') {
            $query->where('order_status', 'completed');
        } elseif ($status === 'cancelled') {
            $query->whereIn('order_status', ['cancelled', 'refunded']);
        }

        $orders = $query->paginate(8)->withQueryString();

        return view('customer.orders.index', compact('orders', 'status'));
    }

    public function show(string $orderNumber)
    {
        $order = Auth::user()->orders()
            ->with(['items.product.primaryImage', 'payments', 'latestPayment', 'shipment', 'statusHistories'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $bankBca = [
            'number' => Setting::get('bank_bca_number', '8271-9283-71'),
            'holder' => Setting::get('bank_bca_holder', 'PT Suntrifting Karya Mandiri'),
        ];
        $bankMandiri = [
            'number' => Setting::get('bank_mandiri_number', '137-00-1928371-0'),
            'holder' => Setting::get('bank_mandiri_holder', 'PT Suntrifting Karya Mandiri'),
        ];

        return view('customer.orders.show', compact('order', 'bankBca', 'bankMandiri'));
    }

    public function uploadProof(Request $request, string $orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $request->validate([
            'proof_image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // max 5MB
            'bank_name' => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
        ], [
            'proof_image.required' => 'Foto bukti transfer wajib diunggah.',
            'proof_image.image' => 'File harus berupa gambar (JPG, PNG, WEBP).',
            'proof_image.max' => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $this->paymentService->uploadManualProof(
                $order,
                $request->file('proof_image'),
                $request->only(['bank_name', 'account_name', 'account_number']),
                Auth::user()
            );

            return back()->with('success', 'Bukti transfer berhasil diunggah! Admin kami akan segera memverifikasi pesanan Anda.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal mengunggah bukti pembayaran: ' . $e->getMessage());
        }
    }

    public function confirmDelivery(string $orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->order_status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dalam status dikirim.');
        }

        $order->update(['order_status' => 'completed']);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'old_status' => 'shipped',
            'new_status' => 'completed',
            'notes' => 'Pesanan telah diterima oleh pembeli. Transaksi dinyatakan selesai.',
            'changed_by' => Auth::id(),
        ]);

        return back()->with('success', 'Terima kasih telah berbelanja di Suntrifting! Pesanan Anda telah selesai.');
    }

    public function cancel(Request $request, string $orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        if ($order->order_status !== 'pending_payment') {
            return back()->with('error', 'Pesanan yang sudah diproses atau dibayar tidak dapat dibatalkan langsung.');
        }

        $reason = $request->input('reason', 'Dibatalkan oleh pembeli');
        $this->orderService->cancelOrder($order, $reason, Auth::user());

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

