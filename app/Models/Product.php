<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'category_id',
        'name',
        'code',
        'description',
        'condition',
        'agreed_price',
        'selling_price',
        'admin_commission_pct',
        'supplier_commission_pct',
        'size',
        'brand',
        'color',
        'weight_grams',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'agreed_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'admin_commission_pct' => 'decimal:2',
            'supplier_commission_pct' => 'decimal:2',
            'weight_grams' => 'integer',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function getImageUrlAttribute(): string
    {
        $primary = $this->primaryImage ?: $this->images->first();
        if ($primary && $primary->path) {
            return asset('storage/' . $primary->path);
        }
        return 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?w=600&auto=format&fit=crop&q=80';
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'like_new' => 'Seperti Baru (Like New)',
            'good' => 'Kondisi Baik (Good)',
            'fair' => 'Cukup Baik (Fair)',
            default => ucfirst($this->condition),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Tersedia</span>',
            'reserved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Dipesan</span>',
            'sold' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Terjual</span>',
            'draft' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Draft</span>',
            'archived' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Diarsipkan</span>',
            default => ucfirst($this->status),
        };
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', 'available');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'] ?? null, function ($q, $slug) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->when($filters['condition'] ?? null, function ($q, $cond) {
                $q->where('condition', $cond);
            })
            ->when($filters['min_price'] ?? null, function ($q, $min) {
                $q->where('selling_price', '>=', $min);
            })
            ->when($filters['max_price'] ?? null, function ($q, $max) {
                $q->where('selling_price', '<=', $max);
            })
            ->when($filters['sort'] ?? null, function ($q, $sort) {
                match ($sort) {
                    'price_low' => $q->orderBy('selling_price', 'asc'),
                    'price_high' => $q->orderBy('selling_price', 'desc'),
                    'oldest' => $q->orderBy('created_at', 'asc'),
                    default => $q->orderBy('created_at', 'desc'),
                };
            }, function ($q) {
                $q->orderBy('created_at', 'desc');
            });
    }
}

