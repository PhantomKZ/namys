<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(): View
    {
        $categories = Category::all();
        $products = Product::take(8)->get();
        return view('index', compact('categories', 'products'));
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

    public function catalog(): View
    {
        $title = "Каталог одежды";
        return view('catalog', ['title' => $title]);
    }

    public function novelty(): View
    {
        $title = "Новинки";
        return view('novelty', ['title' => $title]);
    }

    public function limited(): View
    {
        $title = "Limited Edition";
        return view('limited', ['title' => $title]);
    }

    public function look(): View
    {
        $title = "Look Collection";
        return view('look', ['title' => $title]);
    }
}
