<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'bank_account_number',
        'bank_name',
        'bank_account_holder',
        'status',
        'notes',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function profitShares(): HasMany
    {
        return $this->hasMany(ProfitShare::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(SupplierPayout::class);
    }

    public function getTotalEarningsAttribute(): float
    {
        return (float) $this->profitShares()
            ->where('status', 'completed')
            ->sum('supplier_amount');
    }

    public function getTotalPaidAttribute(): float
    {
        return (float) $this->payouts()
            ->where('status', 'paid')
            ->sum('amount');
    }

    public function getBalanceAttribute(): float
    {
        $earned = $this->total_earnings;
        $paid = $this->total_paid;
        return max(0, $earned - $paid);
    }

    public function getSoldProductsCountAttribute(): int
    {
        return $this->products()->where('status', 'sold')->count();
    }
}

