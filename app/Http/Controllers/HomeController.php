<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscriptions,email',
        ]);

        Subscription::create([
            'email' => $request->email,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => 'Вы успешно подписались на рассылку!']);
        }

        return back()->with('success', 'Вы успешно подписались на рассылку!');
    }}
