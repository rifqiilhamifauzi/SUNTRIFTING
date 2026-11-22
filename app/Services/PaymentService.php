<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentService
{
    protected ProfitShareService $profitShareService;

    public function __construct(ProfitShareService $profitShareService)
    {
        $this->profitShareService = $profitShareService;
    }

    /**
     * Customer uploads manual bank transfer proof.
     */
    public function uploadManualProof(Order $order, UploadedFile $file, array $bankInfo, User $customer): Payment
    {
        return DB::transaction(function () use ($order, $file, $bankInfo, $customer) {
            $path = $file->store('proofs', 'public');

            $payment = $order->latestPayment;
            if (!$payment) {
                $payment = new Payment(['order_id' => $order->id]);
            }

            $payment->fill([
                'payment_method' => 'manual_bank_transfer',
                'amount' => $order->total_amount,
                'proof_image' => $path,
                'bank_name' => $bankInfo['bank_name'] ?? null,
                'account_name' => $bankInfo['account_name'] ?? null,
                'account_number' => $bankInfo['account_number'] ?? null,
                'status' => 'pending',
            ]);
            $payment->save();

            $oldStatus = $order->order_status;
            $order->update([
                'order_status' => 'waiting_verification',
                'payment_status' => 'pending_verification',
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'waiting_verification',
                'notes' => 'Bukti transfer diunggah oleh pembeli. Menunggu pengecekan admin.',
                'changed_by' => $customer->id,
            ]);

            return $payment;
        });
    }

    /**
     * Admin verifies payment and triggers profit sharing.
     */
    public function verifyPayment(Payment $payment, User $admin, ?string $notes = null): Order
    {
        return DB::transaction(function () use ($payment, $admin, $notes) {
            $order = $payment->order;

            $payment->update([
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'admin_notes' => $notes ?: 'Pembayaran telah valid dan diterima di rekening.',
            ]);

            $oldStatus = $order->order_status;
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'processing',
            ]);

            // Finalize product status to 'sold'
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->update(['status' => 'sold']);
                }
            }

            // Trigger Profit Sharing calculation & snapshot
            $this->profitShareService->generateForOrder($order);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'processing',
                'notes' => 'Pembayaran berhasil diverifikasi oleh Admin (' . $admin->name . '). Pesanan siap dipacking.',
                'changed_by' => $admin->id,
            ]);

            return $order;
        });
    }

    /**
     * Admin rejects payment proof.
     */
    public function rejectPayment(Payment $payment, User $admin, string $reason): Order
    {
        return DB::transaction(function () use ($payment, $admin, $reason) {
            $order = $payment->order;

            $payment->update([
                'status' => 'rejected',
                'verified_by' => $admin->id,
                'verified_at' => now(),
                'admin_notes' => $reason,
            ]);

            $oldStatus = $order->order_status;
            $order->update([
                'payment_status' => 'rejected',
                'order_status' => 'pending_payment',
            ]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => 'pending_payment',
                'notes' => 'Bukti transfer ditolak oleh Admin: ' . $reason . '. Silakan upload bukti yang valid.',
                'changed_by' => $admin->id,
            ]);

            return $order;
        });
    }
}

