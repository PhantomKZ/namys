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
