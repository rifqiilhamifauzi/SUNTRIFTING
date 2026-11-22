<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $status = $request->input('status', 'pending');

        $payments = Payment::with(['order.user', 'verifier'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', compact('payments', 'status'));
    }

    public function verify(Request $request, int $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            $this->paymentService->verifyPayment($payment, Auth::user(), $validated['notes'] ?? null);

            return back()->with('success', 'Pembayaran berhasil diverifikasi! Profit sharing otomatis dibuat.');
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

