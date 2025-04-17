<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function images()
    {
        return $this->hasMany(CollectionImage::class);
    }

    public function mainImage()
    {
        return $this->hasOne(CollectionImage::class)->where('is_main', true);
    }

    public function hoverImage()
    {
        return $this->hasOne(CollectionImage::class)->where('is_hover', true);
    }

    public function availableSizes()
    {
        $sizes = collect();

        foreach ($this->products as $product) {
            foreach ($product->sizes as $size) {
                $quantity = $size->pivot->quantity;
                if (!$sizes->has($size->id)) {
                    $sizes->put($size->id, $quantity);
                } else {
                    $sizes->put($size->id, min($sizes->get($size->id), $quantity));
                }
            }
        }

        return $sizes->map(function ($quantity, $sizeId) {
            return (object) [
                'id' => $sizeId,
                'quantity' => $quantity,
                'name' => Size::find($sizeId)->name,
            ];
        });
    }


    public function getMainImageAttribute()
    {
        $image = $this->mainImage()->first();
        return $image ? $image->path : null;
    }

    public function getHoverImageAttribute()
    {
        $image = $this->hoverImage()->first();
        return $image ? $image->path : null;
    }

    public function getPriceAttribute()
    {
        return $this->products->sum('price');
    }

    // Форматированная цена
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, '.', ' ') ?? null;
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
