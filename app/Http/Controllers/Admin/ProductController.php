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
        $products = Product::with(['brand', 'type', 'materials', 'color'])
            ->orderBy('order', 'asc')  // Сортировка по полю order по возрастанию
            ->paginate(16);

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
            'material_ids'   => 'required|array',
            'material_ids.*' => 'exists:materials,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image',
            'main_image_index' => 'nullable|integer',
            'hover_image_index' => 'nullable|integer',
            'quantities' => 'nullable|array',
            'is_limited' => 'nullable',
        ]);

        $maxOrder = Product::max('order');  // Получаем максимальное значение поля order
        $data['order'] = $maxOrder + 1;  // Устанавливаем новое значение для order

        $data['is_limited'] = $request->has('is_limited') && $request->input('is_limited') === 'on' ? 1 : 0;
        $product = Product::create($data);
        $product->materials()->attach($data['material_ids']);

        foreach ($request->input('quantities', []) as $sizeId => $quantity) {
            if ($quantity > 0) {
                $product->sizes()->attach($sizeId, ['quantity' => $quantity]);
            }
        }

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
            'product'   => $product->load('sizes', 'images', 'materials'),
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
            'material_ids'   => 'required|array',
            'material_ids.*' => 'exists:materials,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'images.*' => 'nullable|image',
            'main_image_index' => 'nullable|integer',
            'hover_image_index' => 'nullable|integer',
            'quantities' => 'nullable|array',
            'is_limited' => 'nullable',
        ]);
        $data['is_limited'] = $request->has('is_limited') && $request->input('is_limited') === 'on' ? 1 : 0;
        $product = Product::findOrFail($id);
        $product->update($data);

        $product->sizes()->sync([]);
        $product->materials()->sync($data['material_ids']);

        foreach ($request->input('quantities', []) as $sizeId => $quantity) {
            if ($quantity > 0) {
                $product->sizes()->attach($sizeId, ['quantity' => $quantity]);
            }
        }

        if ($request->has('main_image_index') || $request->has('hover_image_index')) {
            $product->images()->update(['is_main' => false, 'is_hover' => false]);
        }

        $existingIds = $request->input('existing_image_ids', []);
        $currentIds = $product->images()->pluck('id')->toArray();

        $removedIds = array_diff($currentIds, $existingIds);

        foreach ($removedIds as $id) {
            $image = $product->images()->find($id);
            if ($image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }
        }


        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $image = $product->images()->create(['image_path' => $path]);

                if ($index == $request->main_image_index) {
                    $image->update(['is_main' => true]);
                }

                if ($index == $request->hover_image_index) {
                    $image->update(['is_hover' => true]);
                }
            }
        } else {
            $images = $product->images()->get()->values();

            if ($request->has('main_image_index') && isset($images[$request->main_image_index])) {
                $images[$request->main_image_index]->update(['is_main' => true]);
            }

            if ($request->has('hover_image_index') && isset($images[$request->hover_image_index])) {
                $images[$request->hover_image_index]->update(['is_hover' => true]);
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

    public function updateOrder(Request $request)
    {
        $orderData = $request->get('order');  // Получаем данные с клиентской стороны (новый порядок товаров)

        // Проверяем, что каждый элемент массива order является числом
        foreach ($orderData as $productId) {
            if (!is_numeric($productId)) {
                return response()->json(['error' => 'Invalid product ID'], 400);
            }
        }

        // Обновляем порядок товаров
        foreach ($orderData as $index => $productId) {
            Product::where('id', $productId)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

}

