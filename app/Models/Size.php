<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Size extends Model
{
    protected $fillable = ['name'];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function availableQuantity(Product $product): int
    {
        $pivot = $this->products()
            ->where('product_id', $product->id)
            ->first()?->pivot;

        $total = $pivot?->quantity ?? 0;

        $ordered = DB::table('order_product')
            ->where('product_id', $product->id)
            ->where('size_id', $this->id)
            ->sum('quantity');

        $inCart = 0;

        if (auth()->check()) {
            $inCart = \App\Models\CartItem::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->where('size_id', $this->id)
                ->sum('quantity');
        } else {
            $sessionCart = session('cart', []);
            $inCart = $sessionCart[$product->id][$this->id]['quantity'] ?? 0;
        }

        return max($total - $ordered - $inCart, 0);
    }

}
