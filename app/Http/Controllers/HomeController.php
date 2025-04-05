<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|email:rfc,dns',
        ]);

        $existing = Subscription::where('email', $request->email)->first();

        if ($existing) {
            return response()->json(['success' => 'Вы уже подписаны на рассылку!']);
        } else {
            Subscription::create([
                'email' => $request->email,
            ]);
            return response()->json(['success' => 'Вы успешно подписались на рассылку!']);
        }
    }
}
