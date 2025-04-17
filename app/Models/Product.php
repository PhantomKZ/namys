<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function getFormattedPriceAttribute()
    {
        return number_format($this->attributes['price'], 0, '.', ' ') ?? null;
    }

    public function getTypeAttribute()
    {
        return $this->getRelationValue('type')?->name;
    }

    public function getColorAttribute()
    {
        return $this->getRelationValue('color')?->name;
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
