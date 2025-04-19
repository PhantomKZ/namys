<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = [
        'name',
        'brand_id',
        'type_id',
        'material_id',
        'color_id',
        'description',
        'price',
        'is_limited'
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    public function hoverImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_hover', true);
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    public function usersFavorited(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function sizeStocks()
    {
        return $this->belongsToMany(Size::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getAvailableSizesAttribute()
    {
        $ordered = DB::table('order_product')
            ->select('size_id', DB::raw('SUM(quantity) as ordered_quantity'))
            ->where('product_id', $this->id)
            ->groupBy('size_id')
            ->pluck('ordered_quantity', 'size_id');

        return $this->sizeStocks->map(function ($size) use ($ordered) {
            $orderedQty = $ordered[$size->id] ?? 0;
            $available = $size->pivot->quantity - $orderedQty;

            return [
                'id' => $size->id,
                'name' => $size->name,
                'available_quantity' => max($available, 0),
            ];
        });
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->attributes['price'], 0, '.', ' ') . " ₸" ?? null;
    }

    public function getTypeAttribute()
    {
        return $this->getRelationValue('type')?->name;
    }

    public function getTitleAttribute()
    {
        return $this->type . ' ' . $this->attributes['name'];
    }

    public function getColorAttribute()
    {
        return $this->getRelationValue('color')?->name;
    }

    public function getBrandAttribute()
    {
        return $this->getRelationValue('brand')?->name;
    }

    public function getMaterialAttribute()
    {
        return $this->getRelationValue('material')?->name;
    }

    public function getAvailableQuantityAttribute()
    {
        $ordered = DB::table('order_products')
            ->where('product_id', $this->id)
            ->sum('quantity');

        return $this->quantity - $ordered;
    }


    public function getMainImageAttribute()
    {
        $image = $this->mainImage()->first();
        return $image ? $image->path : '';
    }

    public function getHoverImageAttribute()
    {
        $image = $this->hoverImage()->first();
        return $image ? $image->path : '';
    }

    public function getImagesWithFlagsAttribute()
    {
        $images = $this->getRelationValue('images') ?? $this->images()->get();
        return $images->map(function ($img) {
            return [
                'url' => $img->path,
                'is_main' => (bool) $img->is_main,
                'is_hover' => (bool) $img->is_hover,
            ];
        })->toArray();
    }

}
