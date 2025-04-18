<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CartItem;

class CartCountMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $cartItems = CartItem::where('user_id', auth()->id())->get();
            $cartCount = $cartItems->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            $cartCount = 0;
            foreach ($cart as $productId => $sizes) {
                foreach ($sizes as $sizeId => $item) {
                    $cartCount += $item['quantity'];
                }
            }
        }

        // Передаем переменную в представления
        view()->share('cartCount', $cartCount);

        return $next($request);
    }
}
