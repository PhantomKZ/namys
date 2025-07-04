<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Size;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $totalPrice = preg_replace('/\D/', '', $request->input('total_price'));
        $request->merge(['total_price' => $totalPrice]);
        $request->validate([
            '_token' => 'nullable',
            'total_price' => 'required|numeric|min:1',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.size_id' => 'nullable|exists:sizes,id',
        ]);

        // Проверка авторизации
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему для оформления заказа.');
        }

        // Если пользователь авторизован, продолжаем
        $user = auth()->user();
        $order = Order::create([
            'user_id' => $user->id,
            'total_price' => $request->input('total_price'),
            'status' => 'pending',
        ]);

        $items = collect($request->input('products'))->map(function ($item) {
            return (object)[
                'product' => Product::find($item['id']),
                'quantity' => $item['quantity'],
                'size' => Size::find($item['size_id']),
            ];
        });

        foreach ($items as $item) {
            $order->products()->attach($item->product->id, [
                'quantity' => $item->quantity,
                'size_id' => $item->size->id ?? null,
                'price' => $item->product->price,
            ]);
        }

        // Очищаем корзину после оформления заказа
        if ($user) {
            CartItem::where('user_id', $user->id)->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('profile.orders')->with('success', 'Заказ оформлен!');
    }

}
