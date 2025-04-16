<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Type;
use App\Models\Material;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['brand', 'type', 'material', 'color'])->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.form', [
            'brands'    => Brand::all(),
            'types'     => Type::all(),
            'materials' => Material::all(),
            'colors'    => Color::all(),
            'sizes'     => Size::all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'material_id' => 'required|exists:materials,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image',
            'main_image_index' => 'nullable|integer',
            'hover_image_index' => 'nullable|integer',
            'quantities' => 'nullable|array',
        ]);

        $product = Product::create($data);

        // Сохраняем размеры
        foreach ($request->input('quantities', []) as $sizeId => $quantity) {
            if ($quantity > 0) {
                $product->sizes()->attach($sizeId, ['quantity' => $quantity]);
            }
        }

        // Сохраняем изображения
        $files = $request->file('images', []);
        foreach ($files as $index => $file) {
            $path = $file->store('products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'is_main' => $index == $request->main_image_index,
                'is_hover' => $index == $request->hover_image_index,
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Товар успешно создан.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('admin.products.form', [
            'product'   => $product->load('sizes', 'images'),
            'brands'    => Brand::all(),
            'types'     => Type::all(),
            'materials' => Material::all(),
            'colors'    => Color::all(),
            'sizes'     => Size::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'brand_id' => 'required|exists:brands,id',
            'type_id' => 'required|exists:types,id',
            'material_id' => 'required|exists:materials,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image',
            'main_image_index' => 'required|integer',
            'hover_image_index' => 'nullable|integer',
            'quantities' => 'nullable|array',
        ]);

        $product = Product::findOrFail($id);
        $product->update($data);

        $product->sizes()->sync([]);

        foreach ($request->input('quantities', []) as $sizeId => $quantity) {
            if ($quantity > 0) {
                $product->sizes()->attach($sizeId, ['quantity' => $quantity]);
            }
        }

        // Обработка изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $image = $product->images()->create(['image_path' => $path]);

                // Устанавливаем главное изображение
                if ($index == $request->main_image_index) {
                    $image->update(['is_main' => true]);
                }

                // Устанавливаем hover-изображение
                if ($index == $request->hover_image_index) {
                    $image->update(['is_hover' => true]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Товар обновлен');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Товар удалён');
    }
}
