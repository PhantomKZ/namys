<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id): View
    {
        $product = Product::with(['type', 'brand', 'material', 'color', 'images', 'sizes'])
            ->findOrFail($id);
        $recommendations = Product::with(['type', 'brand', 'material', 'color', 'images', 'sizes'])
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(5)
            ->get();
        $title = "{$product->type} {$product->name}";

        $cartItemsBySize = [];

        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->get();
            $cartItemsBySize = $cartItems->keyBy('size_id');
        } else {
            $sessionCart = session('cart', []);
            $cartItemsBySize = collect($sessionCart[$product->id] ?? []);
        }

        return view('products.show', compact('product', 'recommendations', 'title', 'cartItemsBySize'));
    }

    public function addToFavorites($id)
    {
        $user = auth()->user();
        $product = Product::findOrFail($id);
        if ($user->favorites->contains($product->id)) {
            return back()->with('error', 'Товар уже в избранном!');
        }
        $user->favorites()->attach($product);
        return back()->with('success', 'Товар добавлен в избранное!');
    }

    // Удаление товара из избранного
    public function removeFromFavorites($id)
    {
        $user = auth()->user();
        $product = Product::findOrFail($id);
        if (!$user->favorites->contains($product->id)) {
            return back()->with('error', 'Товар не был добавлен в избранное!');
        }
        $user->favorites()->detach($product);
        return back()->with('success', 'Товар удален из избранного!');
    }

}
