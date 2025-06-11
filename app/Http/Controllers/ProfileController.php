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

        $validator = \Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,tiff|max:20480',
            'phone'  => 'nullable|regex:/^\\+7\\d{10}$/',
        ]);

        if ($validator->fails()) {
            dd($validator->errors()->all(), \App::getLocale());
            return back()->withErrors($validator)->withInput();
        }

        $user->phone = $request->filled('phone') ? $request->phone : null;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->name = $request->name;

        // Смена пароля
        if ($request->filled('current_password') || $request->filled('new_password') || $request->filled('new_password_confirmation')) {
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ], [
                'current_password.required' => 'Введите текущий пароль для смены пароля',
                'new_password.required' => 'Введите новый пароль',
                'new_password.confirmed' => 'Подтверждение пароля не совпадает',
                'new_password.min' => 'Пароль должен быть не менее 8 символов',
            ]);
            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Текущий пароль введён неверно'])->withInput();
            }
            $user->password = bcrypt($request->new_password);
        }

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
