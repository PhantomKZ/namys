<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'thumbnail'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = self::generateSlug($category->name);

            if (request()->hasFile('thumbnail')) {
                $category->thumbnail = request()->file('thumbnail')->store('categories/thumbnails', 'public');
            }
        });

        static::updating(function ($category) {
            if (request()->hasFile('thumbnail')) {
                $category->thumbnail = request()->file('thumbnail')->store('categories/thumbnails', 'public');
            }
        });
    }

    public function types()
    {
        return $this->belongsToMany(Type::class, 'category_type');
    }

    public static function generateSlug($name)
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function getThumbnailAttribute()
    {
        $thumbnail = $this->attributes['thumbnail'] ?? null;
        return $thumbnail ? 'storage/' . $thumbnail : '';
    }
}
