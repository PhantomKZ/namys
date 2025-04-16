<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id): View
    {
        $product = Product::with(['type', 'brand', 'material', 'color', 'images', 'sizes'])
            ->findOrFail($id);

        return view('products.show', compact('product'));
    }

}
