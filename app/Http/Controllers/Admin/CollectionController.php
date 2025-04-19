<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        ]);

        $collection = Collection::create($validated);

        $images = $request->file('images');
        $mainIndex = $request->input('main_image_index');

        foreach ($images as $index => $image) {
            $path = $image->store('uploads', 'public');

            $collection->images()->create([
                'path' => $path,
                'is_main' => ($mainIndex == $index) ? 1 : 0,
            ]);
        }

        $collection->products()->sync($request->products ?? []);

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
            'main_image_index' => 'nullable|integer',
            'existing_image_ids' => 'nullable|array',
            'existing_image_ids.*' => 'integer|exists:collection_images,id',
        ]);

        $collection = Collection::findOrFail($id);
        $collection->update($validated);

        $mainIndex = (int) $request->input('main_image_index');

        $collection->products()->sync($request->products ?? []);

        $existingImageIds = $request->input('existing_image_ids', []);
        $imagesToDelete = $collection->images()->whereNotIn('id', $existingImageIds)->get();

        foreach ($imagesToDelete as $image) {
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            $image->delete();
        }

        $collection->images()->update(['is_main' => false]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');

                $collection->images()->create([
                    'path' => $path,
                    'is_main' => ($index === $mainIndex),
                ]);
            }
        } else {
            $images = $collection->images()->get()->values();

            if (isset($images[$mainIndex])) {
                $images[$mainIndex]->update(['is_main' => true]);
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
