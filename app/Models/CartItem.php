<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'size_id',
        'quantity',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getAvailableQuantityAttribute()
    {
        $product = $this->product; // связь с Product
        $sizeId = $this->size_id;

        $size = $product->sizes()->where('size_id', $sizeId)->first();

        return $size ? $size->pivot->quantity : 0;
    }


    public function getCartCount()
    {
        $count = CartItem::where('user_id', auth()->id())->sum('quantity');
        return $count;
    }

    public function getCartSessionCount()
    {
        $cart = session('cart', []);
        return collect($cart)->sum('quantity');
    }



}
