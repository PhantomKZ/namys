<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(): View
    {
        $collections = Collection::all();
        $title = "Look Collection";
        return view('collections.index', ['title' => $title, 'collections' => $collections]);
    }

    public function show($id): View
    {
        $collection = Collection::with(['products'])
            ->findOrFail($id);
        $products = $collection->products;
        $title = "Комплект $collection->name";

        return view('collections.show', compact('collection', 'products', 'title'));
    }
}
