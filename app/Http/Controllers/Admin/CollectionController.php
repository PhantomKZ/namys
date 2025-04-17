<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{

    public function index()
    {
        $collections = Collection::withCount('products')->get();

        return view('admin.collections.index', compact('collections'));
    }

    public function create()
    {
        $products = Product::all();
        return view('admin.collections.form', [
            'collection' => new Collection(),
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'images.*' => 'nullable|image',
            'main_image_index' => 'nullable|integer',
            'hover_image_index' => 'nullable|integer',
        ]);

        $collection = Collection::create($validated);
        $collection->products()->sync($request->products ?? []);

        $files = $request->file('images', []);
        foreach ($files as $index => $file) {
            $path = $file->store('products', 'public');
            $collection->images()->create([
                'path' => $path,
                'is_main' => $index == $request->main_image_index,
                'is_hover' => $index == $request->hover_image_index,
            ]);
        }

        return redirect()->route('admin.collections.index')->with('success', 'Коллекция создана!');
    }

    public function edit($id)
    {
        $collection = Collection::findOrFail($id);
        $products = Product::all();
        $selected = $collection->products->pluck('id')->toArray();

        return view('admin.collections.form', compact('collection', 'products', 'selected'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'products' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'images.*' => 'nullable|image',
            'main_image_index' => 'required|integer',
            'hover_image_index' => 'nullable|integer',
        ]);
        $collection = Collection::findOrFail($id);
        $collection->update($validated);
        $collection->products()->sync($request->products ?? []);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $image = $collection->images()->create(['path' => $path]);

                if ($index == $request->main_image_index) {
                    $image->update(['is_main' => true]);
                }

                if ($index == $request->hover_image_index) {
                    $image->update(['is_hover' => true]);
                }
            }
        }

        return redirect()->route('admin.collections.index')->with('success', 'Коллекция обновлена!');
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);
        $collection->products()->detach();
        $collection->delete();

        return redirect()->route('admin.collections.index')->with('success', 'Коллекция успешно удалена.');
    }


}
