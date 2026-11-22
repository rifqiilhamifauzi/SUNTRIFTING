<?php

namespace App\Services;

use App\Models\PayoutItem;
use App\Models\ProfitShare;
use App\Models\Supplier;
use App\Models\SupplierPayout;
use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SupplierPayoutService
{
    /**
     * Create a supplier payout transaction.
     */
    public function createPayout(
        Supplier $supplier,
        float $amount,
        array $payoutData,
        User $admin,
        ?UploadedFile $proofFile = null
    ): SupplierPayout {
        return DB::transaction(function () use ($supplier, $amount, $payoutData, $admin, $proofFile) {
            // Lock supplier profit shares to compute available balance accurately
            $unpaidShares = ProfitShare::where('supplier_id', $supplier->id)
                ->where('status', 'completed')
                ->where('payout_status', 'unpaid')
                ->lockForUpdate()
                ->get();

            $availableBalance = (float) $unpaidShares->sum('supplier_amount');

            if ($amount <= 0) {
                throw new Exception('Nominal pencairan harus lebih besar dari 0.');
            }

            if ($amount > $availableBalance) {
                throw new Exception("Nominal pencairan (Rp" . number_format($amount, 0, ',', '.') . ") melebihi saldo yang tersedia (Rp" . number_format($availableBalance, 0, ',', '.') . ").");
            }

            $proofPath = null;
            if ($proofFile) {
                $proofPath = $proofFile->store('payout_proofs', 'public');
            }

            $payoutNumber = 'PAYOUT-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $payout = SupplierPayout::create([
                'supplier_id' => $supplier->id,
                'payout_number' => $payoutNumber,
                'amount' => $amount,
                'payment_method' => $payoutData['payment_method'] ?? 'bank_transfer',
                'bank_name' => $payoutData['bank_name'] ?? $supplier->bank_name,
                'bank_account_number' => $payoutData['bank_account_number'] ?? $supplier->bank_account_number,
                'bank_account_holder' => $payoutData['bank_account_holder'] ?? $supplier->bank_account_holder,
                'reference_number' => $payoutData['reference_number'] ?? null,
                'proof_image' => $proofPath,
                'notes' => $payoutData['notes'] ?? null,
                'status' => 'paid',
                'paid_at' => now(),
                'processed_by' => $admin->id,
            ]);

            // Mark and link profit shares until $amount is covered
            $remaining = $amount;
            foreach ($unpaidShares as $share) {
                if ($remaining <= 0) {
                    break;
                }

                $shareAmount = (float) $share->supplier_amount;
                $allocated = min($remaining, $shareAmount);

                PayoutItem::create([
                    'supplier_payout_id' => $payout->id,
                    'profit_share_id' => $share->id,
                    'amount' => $allocated,
                ]);

                // If fully covered
                if ($allocated >= $shareAmount) {
                    $share->update(['payout_status' => 'paid']);
                }

                $remaining -= $allocated;
            }

            return $payout;
        });
    }
}

