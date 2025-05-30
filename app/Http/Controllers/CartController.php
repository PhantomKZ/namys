<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;

class CartController extends Controller
{
    public function index()
    {
        $formattedTotalPrice = 0;
        $items = collect();

        if (auth()->check()) {
            // Для авторизованных пользователей
            $cartItems = CartItem::with('product', 'size')->where('user_id', auth()->id())->get();

            $totalPrice = $cartItems->reduce(function ($carry, $item) {
                return $carry + ($item->product->price * $item->quantity);
            }, 0);

            $items = $cartItems->map(function ($cartItem) {
                $cartItem->size->available_quantity = $cartItem->size->availableQuantity($cartItem->product);
                return $cartItem;
            });
        } else {
            // Для неавторизованных пользователей
            $cart = session('cart', []);
            $products = Product::with('sizes')->whereIn('id', array_keys($cart))->get();

            foreach ($products as $product) {
                foreach ($cart[$product->id] as $sizeId => $cartData) {
                    $quantity = $cartData['quantity'] ?? 1;
                    $size = $product->sizes->firstWhere('id', $sizeId);

                    // Вычисляем доступное количество для каждого размера
                    $size->available_quantity = $size->availableQuantity($product);

                    // Добавляем товар в корзину
                    $items->push((object)[
                        'product' => $product,
                        'quantity' => $quantity,
                        'size' => $size,
                    ]);
                }
            }

            $cartCount = $items->sum(fn($item) => $item->quantity);

            $totalPrice = $items->reduce(function ($carry, $item) {
                return $carry + ($item->product->price * $item->quantity);
            }, 0);
        }
        $formattedTotalPrice = number_format($totalPrice, 0, '.', ' ') . ' ₸';
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
            if (isset($cart[$productId][$sizeId])) {
                $cart[$productId][$sizeId]['quantity'] += $quantity;
            } else {
                $cart[$productId][$sizeId] = ['quantity' => $quantity];
            }

            session()->put('cart', $cart);
        }

        return redirect()->back()->withInput()->with('success', 'Товар добавлен в корзину');
    }

    public function addAll(Request $request)
    {
        $messages = [
            'sizes.required' => 'Пожалуйста, выберите размер для каждого товара.',
            'sizes.array' => 'Неверный формат размеров.',
            'sizes.*.required' => 'Пожалуйста, выберите размер для каждого товара.',
            'sizes.*.exists' => 'Выбранный размер не существует.',
            'product_ids.required' => 'Не найдены товары для добавления.',
            'product_ids.array' => 'Неверный формат списка товаров.',
            'product_ids.*.exists' => 'Один из товаров не существует.',
        ];

        $validated = $request->validate([
            'sizes' => 'required|array',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ], $messages);

        foreach ($validated['product_ids'] as $productId) {
            if (empty($request->input('sizes.' . $productId))) {
                return back()->withErrors(['sizes' => 'Пожалуйста, выберите размер для каждого товара.'])->withInput();
            }
        }

        $quantity = $request->input('quantity', 1);
        $sizes = $request->input('sizes');

        if (auth()->check()) {
            foreach ($validated['product_ids'] as $productId) {
                $sizeId = $sizes[$productId];
                $item = CartItem::firstOrNew([
                    'user_id' => auth()->id(),
                    'product_id' => $productId,
                    'size_id' => $sizeId,
                ]);
                $item->quantity += $quantity;
                $item->save();
            }
        } else {
            $cart = session()->get('cart', []);
            foreach ($validated['product_ids'] as $productId) {
                $sizeId = $sizes[$productId];
                if (isset($cart[$productId][$sizeId])) {
                    $cart[$productId][$sizeId]['quantity'] += $quantity;
                } else {
                    $cart[$productId][$sizeId] = ['quantity' => $quantity];
                }
            }
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Комплект добавлен в корзину.');
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
