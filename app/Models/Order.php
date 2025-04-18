<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = ['user_id', 'total_price', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity', 'size_id', 'price')
            ->withTimestamps();
    }

    public function getFormattedPriceAttribute()
    {
        return number_format($this->attributes['total_price'], 0, '.', ' ') . " ₸" ?? null;
    }

}
