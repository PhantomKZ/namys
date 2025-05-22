<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = User::with('favorites', 'orders')->findOrFail(auth()->id());
        return view('profile.show', compact('user'));
    }

    public function edit(Request $request): View
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }


    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'avatar' => 'nullable|image|max:2048',
            'phone'  => 'nullable|regex:/^\+7\d{10}$/',
        ]);

        $user->phone = $request->filled('phone') ? $request->phone : null;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;
        $user->save();

        return redirect()
            ->route('profile.show')
            ->with('success', 'Профиль обновлён!');
    }


    public function orders(): View
    {
        $orders = auth()->user()->orders()
            ->with('products', 'products.sizes')
            ->latest()
            ->get();

        foreach ($orders as $order) {
            foreach ($order->products as $product) {
                $size = $product->sizes->firstWhere('id', $product->pivot->size_id);
                $product->pivot->size_name = $size?->name;
                $product->pivot->total_price = $product->pivot->price * $product->pivot->quantity;
            }
        }

        return view('profile.orders', compact('orders'));
    }

}
