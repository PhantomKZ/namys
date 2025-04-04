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
            'email' => 'required|email|unique:subscriptions,email',
        ]);

        $subscribe = Subscription::create([
            'email' => $request->email,
        ]);

        if ($subscribe) {
            return response()->json(['success' => 'Вы успешно подписались на рассылку!']);
        } else {
            return response()->json(['error' => 'Произошла ошибка при подписке. Попробуйте позже.'], 500);
        }
    }
}
