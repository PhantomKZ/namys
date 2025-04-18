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
        $request->validate([
            'total_price' => 'required|numeric|min:1',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|numeric|min:1',
            'products.*.size_id' => 'nullable|exists:sizes,id',
        ]);

        $user = auth()->user();
        $order = Order::create([
            'user_id' => $user->id ?? null,
            'total_price' => $request->input('total_price'),
            'status' => 'В обработке',
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

        if ($user) {
            CartItem::where('user_id', $user->id)->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('profile.orders')->with('success', 'Заказ оформлен!');
    }
}
