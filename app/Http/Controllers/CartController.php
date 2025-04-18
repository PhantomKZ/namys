<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            $items = CartItem::with('product', 'size')->where('user_id', auth()->id())->get();

            $totalPrice = $items->reduce(function ($carry, $item) {
                return $carry + ($item->product->price * $item->quantity);
            }, 0);
            $formattedTotalPrice = number_format($totalPrice, 0, '.', ' ') . ' ₸';
        } else {
            $cart = session('cart', []);
            $products = Product::with('sizes')->whereIn('id', array_keys($cart))->get();

            $items = $products->map(function ($product) use ($cart) {
                $sizeId = $cart[$product->id]['size_id'] ?? null;
                $quantity = $cart[$product->id]['quantity'] ?? 1;
                $size = $product->sizes->firstWhere('id', $sizeId);

                return (object)[
                    'product' => $product,
                    'quantity' => $quantity,
                    'size' => $size,
                ];
            });

            $cartCount = $items->sum(fn($item) => $item->quantity);

            $totalPrice = $items->reduce(function ($carry, $item) {
                return $carry + ($item->product->price * $item->quantity);
            }, 0);
        }

        return view('cart.index', compact('items',  'formattedTotalPrice'));
    }

    public function add(Request $request)
    {
        $messages = [
            'size_id.required' => 'Пожалуйста, выберите размер товара.',
            'size_id.exists' => 'Выбранный размер не существует.',
        ];

        $request->validate([
            'size_id' => 'required|exists:sizes,id', // Проверка на обязательность и существование
        ], $messages);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);
        $sizeId = $request->input('size_id');

        if (auth()->check()) {
            $item = CartItem::firstOrNew([
                'user_id' => auth()->id(),
                'product_id' => $productId,
                'size_id' => $sizeId,
            ]);
            $item->quantity += $quantity;
            $item->save();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId][$size])) {
                $cart[$productId][$size]['quantity'] += $quantity;
            } else {
                $cart[$productId][$size] = ['quantity' => $quantity];
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->withInput()->with('success', 'Товар добавлен в корзину');
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $sizeId = $request->input('size_id');

        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())
                ->where('product_id', $productId)
                ->where('size_id', $sizeId)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$productId][$sizeId]);
            if (empty($cart[$productId])) {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->withInput()->with('success', 'Товар удалён из корзины');
    }

    public function clear()
    {
        if (auth()->check()) {
            CartItem::where('user_id', auth()->id())->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->back()->with('success', 'Корзина очищена');
    }

}
