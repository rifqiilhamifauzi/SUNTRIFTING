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
        'subcategory_id',
        'name',
        'code',
        'description',
        'condition',
        'agreed_price',
        'selling_price',
        'original_price',
        'admin_commission_pct',
        'supplier_commission_pct',
        'size',
        'brand',
        'color',
        'material',
        'gender',
        'location',
        'is_used',
        'defect_description',
        'specific_attributes',
        'weight_grams',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'agreed_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'admin_commission_pct' => 'decimal:2',
            'supplier_commission_pct' => 'decimal:2',
            'weight_grams' => 'integer',
            'is_used' => 'boolean',
            'specific_attributes' => 'array',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order')->orderBy('id');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function defectImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->where('is_defect', true);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isWishlistedBy(?User $user): bool
    {
        if (!$user) {
            return false;
        }
        return $this->wishlists()->where('user_id', $user->id)->exists();
    }

    public function getImageUrlAttribute(): string
    {
        $primary = $this->primaryImage ?: $this->images->first();
        if ($primary && $primary->path) {
            if (str_starts_with($primary->path, 'http://') || str_starts_with($primary->path, 'https://')) {
                return $primary->path;
            }
            return asset('storage/' . $primary->path);
        }
        return 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600&auto=format&fit=crop&q=80';
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'brand_new' => 'Brand New with Tag',
            'new_without_tag' => 'New Without Tag',
            'like_new' => 'Seperti Baru (Like New)',
            'good' => 'Kondisi Baik (Good)',
            'fair' => 'Kondisi Cukup (Fair)',
            default => ucfirst(str_replace('_', ' ', $this->condition)),
        };
    }

    public function getConditionBadgeClassAttribute(): string
    {
        return match ($this->condition) {
            'brand_new' => 'bg-[#174C3C]/10 text-[#174C3C] border-[#174C3C]/30',
            'new_without_tag' => 'bg-[#26745A]/10 text-[#26745A] border-[#26745A]/30',
            'like_new' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'good' => 'bg-amber-50 text-amber-700 border-amber-200',
            'fair' => 'bg-orange-50 text-orange-700 border-orange-200',
            default => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#174C3C]/10 text-[#174C3C]">Tersedia</span>',
            'reserved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">Dipesan</span>',
            'sold' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Terjual</span>',
            'draft' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Draft</span>',
            'archived' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Diarsipkan</span>',
            default => ucfirst($this->status),
        };
    }

    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            'women', 'wanita' => 'Wanita',
            'men', 'pria' => 'Pria',
            'kids', 'anak' => 'Anak-anak',
            'unisex' => 'Unisex',
            default => ucfirst($this->gender ?? 'Unisex'),
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
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('material', 'like', "%{$search}%")
                        ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('subcategory', fn ($c) => $c->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($filters['category'] ?? null, function ($q, $slug) {
                $q->whereHas('category', fn ($c) => $c->where('slug', $slug));
            })
            ->when($filters['subcategory'] ?? null, function ($q, $subSlug) {
                $q->whereHas('subcategory', fn ($c) => $c->where('slug', $subSlug));
            })
            ->when($filters['condition'] ?? null, function ($q, $cond) {
                if (is_array($cond)) {
                    $q->whereIn('condition', $cond);
                } else {
                    $q->where('condition', $cond);
                }
            })
            ->when($filters['gender'] ?? null, function ($q, $gender) {
                $q->where('gender', $gender);
            })
            ->when($filters['size'] ?? null, function ($q, $size) {
                $q->where('size', $size);
            })
            ->when($filters['color'] ?? null, function ($q, $color) {
                $q->where('color', 'like', "%{$color}%");
            })
            ->when($filters['brand'] ?? null, function ($q, $brand) {
                $q->where('brand', 'like', "%{$brand}%");
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
                    'popular' => $q->withCount('wishlists')->orderBy('wishlists_count', 'desc'),
                    'oldest' => $q->orderBy('created_at', 'asc'),
                    default => $q->orderBy('created_at', 'desc'),
                };
            }, function ($q) {
                $q->orderBy('created_at', 'desc');
            });
    }
}
