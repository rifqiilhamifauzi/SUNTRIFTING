<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfitShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'supplier_id',
        'selling_price',
        'admin_commission_pct',
        'supplier_commission_pct',
        'admin_amount',
        'supplier_amount',
        'status',
        'payout_status',
    ];

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:2',
            'admin_commission_pct' => 'decimal:2',
            'supplier_commission_pct' => 'decimal:2',
            'admin_amount' => 'decimal:2',
            'supplier_amount' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}

