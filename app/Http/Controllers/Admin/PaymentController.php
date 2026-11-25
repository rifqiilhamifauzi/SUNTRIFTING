<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        // Kategori antrean pembayaran:
        // 1. 'needs_verification' (sudah transfer & upload bukti, siap dicek admin) -> default
        // 2. 'unpaid' (menunggu transfer / belum upload bukti)
        // 3. 'rejected' (pembayaran ditolak)
        $status = $request->input('status', 'needs_verification');
        if (!in_array($status, ['needs_verification', 'unpaid', 'rejected'])) {
            $status = 'needs_verification';
        }

        $search = $request->input('search');

        $query = Payment::with(['order.user', 'verifier']);

        if ($search) {
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('recipient_name', 'like', "%{$search}%")
                    ->orWhere('recipient_phone', 'like', "%{$search}%");
            });
        }

        if ($status === 'needs_verification') {
            // Customer sudah bayar & upload bukti transfer
            $query->where('status', 'pending')->whereNotNull('proof_image');
        } elseif ($status === 'unpaid') {
            // Belum bayar / belum upload bukti transfer
            $query->where('status', 'pending')->whereNull('proof_image');
        } elseif ($status === 'rejected') {
            // Bukti transfer ditolak
            $query->where('status', 'rejected');
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        // Hitung statistik untuk badge
        $counts = [
            'needs_verification' => Payment::where('status', 'pending')->whereNotNull('proof_image')->count(),
            'unpaid' => Payment::where('status', 'pending')->whereNull('proof_image')->count(),
            'rejected' => Payment::where('status', 'rejected')->count(),
            'verified_orders' => Order::where('payment_status', 'paid')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'status', 'search', 'counts'));
    }

    public function verify(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->paymentService->verifyPayment($payment, Auth::user(), $validated['notes'] ?? null);

            $orderNumber = $payment->order ? $payment->order->order_number : '#' . $payment->order_id;

            if ($request->input('redirect') === 'payments') {
                return back()->with('success', "Pembayaran untuk order {$orderNumber} berhasil diverifikasi! Pesanan telah berpindah ke Daftar Pesanan Customer.");
            }

            return redirect()->route('admin.orders.index')->with('success', "Pembayaran untuk order {$orderNumber} berhasil diverifikasi! Pesanan kini telah berpindah ke Daftar Pesanan Customer (Siap Diproses/Dikemas).");
        } catch (Exception $e) {
            return back()->with('error', 'Gagal memverifikasi: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ], [
            'reason.required' => 'Alasan penolakan pembayaran wajib diisi.',
        ]);

        try {
            $this->paymentService->rejectPayment($payment, Auth::user(), $validated['reason']);

            return back()->with('success', 'Pembayaran telah ditolak dengan alasan yang tercatat.');
        } catch (Exception $e) {
            return back()->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }
}
