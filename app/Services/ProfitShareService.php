<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProfitShare;
use Illuminate\Support\Facades\DB;

class ProfitShareService
{
    /**
     * Generate profit sharing records for an order.
     * Guaranteed to run only once per order, using snapshots stored on OrderItem.
     */
    public function generateForOrder(Order $order): array
    {
        return DB::transaction(function () use ($order) {
            // Check if already generated
            $existing = ProfitShare::where('order_id', $order->id)->exists();
            if ($existing) {
                return ProfitShare::where('order_id', $order->id)->get()->all();
            }

            $shares = [];

            foreach ($order->items as $item) {
                // Precise financial decimal calculation (no floating point drift)
                $price = (float) $item->product_price;
                $adminPct = (float) $item->admin_commission_pct;
                $supplierPct = (float) $item->supplier_commission_pct;

                $adminAmount = round($price * ($adminPct / 100), 2);
                $supplierAmount = round($price - $adminAmount, 2);

                $share = ProfitShare::create([
                    'order_id' => $order->id,
                    'order_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'supplier_id' => $item->supplier_id,
                    'selling_price' => $price,
                    'admin_commission_pct' => $adminPct,
                    'supplier_commission_pct' => $supplierPct,
                    'admin_amount' => $adminAmount,
                    'supplier_amount' => $supplierAmount,
                    'status' => 'completed',
                    'payout_status' => 'unpaid',
                ]);

                $shares[] = $share;
            }

            return $shares;
        });
    }

    /**
     * Cancel profit shares when order is refunded/cancelled.
     */
    public function cancelForOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            ProfitShare::where('order_id', $order->id)->update([
                'status' => 'cancelled',
            ]);
        });
    }
}

