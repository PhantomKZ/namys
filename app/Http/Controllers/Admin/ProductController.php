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
            ->orderBy('order', 'asc')
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
            'name'                => 'required|string',
            'brand_id'            => 'required|exists:brands,id',
            'type_id'             => 'required|exists:types,id',
            'material_ids'        => 'required|array',
            'material_ids.*'      => 'exists:materials,id',
            'color_id'            => 'required|exists:colors,id',
            'price'               => 'required|numeric',
            'model_code'          => 'nullable|string|max:100',        // ← добавили
            'description'         => 'nullable|string',
            'images.*'            => 'nullable|image',
            'main_image_index'    => 'nullable|integer',
            'hover_image_index'   => 'nullable|integer',
            'quantities'          => 'nullable|array',
            'is_limited'          => 'nullable',
        ]);

        // порядковый номер
        $data['order'] = Product::max('order') + 1;

        // флаг limited
        $data['is_limited'] = $request->has('is_limited') ? 1 : 0;

        // создаём товар (model_code попадёт из $data автоматически)
        $product = Product::create($data);

        // привязываем материалы
        $product->materials()->sync($data['material_ids']);

        // привязываем размеры
        foreach ($data['quantities'] ?? [] as $sizeId => $quantity) {
            if ($quantity > 0) {
                $product->sizes()->attach($sizeId, ['quantity' => $quantity]);
            }
        }

        // сохраняем картинки
        foreach ($request->file('images', []) as $index => $file) {
            $path = $file->store('products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'is_main'    => $index == $request->main_image_index,
                'is_hover'   => $index == $request->hover_image_index,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан.');
    }

    public function edit($id)
    {
        $product = Product::with(['materials','sizes','images'])->findOrFail($id);

        return view('admin.products.form', [
            'product'   => $product,
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
            'name'                => 'required|string',
            'brand_id'            => 'required|exists:brands,id',
            'type_id'             => 'required|exists:types,id',
            'material_ids'        => 'required|array',
            'material_ids.*'      => 'exists:materials,id',
            'color_id'            => 'required|exists:colors,id',
            'price'               => 'required|numeric',
            'model_code'          => 'nullable|string|max:100',
            'description'         => 'nullable|string',
            'images.*'            => 'nullable|image',
            'main_image_index'    => 'nullable|integer',
            'hover_image_index'   => 'nullable|integer',
            'quantities'          => 'nullable|array',
            'is_limited'          => 'nullable',
        ]);

        // если код-модели оставлен пустым — не затираем старый
        if (! $request->filled('model_code')) {
            unset($data['model_code']);
        }

        $data['is_limited'] = $request->has('is_limited') ? 1 : 0;

        /** @var Product $product */
        $product = Product::findOrFail($id);
        $product->update($data);

        // синхронизуем материалы
        $product->materials()->sync($data['material_ids']);

        // размеры: полностью пересобираем
        $product->sizes()->detach();
        foreach ($data['quantities'] ?? [] as $sizeId => $qty) {
            if ($qty > 0) {
                $product->sizes()->attach($sizeId, ['quantity' => $qty]);
            }
        }

        // Сбрасываем флаги всех картинок
        $product->images()->update([
            'is_main'  => false,
            'is_hover' => false,
        ]);

        // Удаляем те, что убрали в форме
        $existingIds = (array) $request->input('existing_image_ids', []);
        $toRemove    = $product->images()->pluck('id')->diff($existingIds);
        foreach ($toRemove as $imgId) {
            $img = $product->images()->find($imgId);
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        }

        // Обновляем флаги для оставшихся (по их порядку в коллекции)
        $remaining = $product->images()->orderBy('id')->get()->values();
        if ($request->filled('main_image_index') && isset($remaining[$request->main_image_index])) {
            $remaining[$request->main_image_index]->update(['is_main' => true]);
        }
        if ($request->filled('hover_image_index') && isset($remaining[$request->hover_image_index])) {
            $remaining[$request->hover_image_index]->update(['is_hover' => true]);
        }

        // Загружаем новые файлы и проставляем у них флаги
        foreach ($request->file('images', []) as $idx => $file) {
            $path = $file->store('products', 'public');
            $img  = $product->images()->create(['image_path' => $path]);
            if ((int)$idx === (int)$request->main_image_index) {
                $img->update(['is_main' => true]);
            }
            if ((int)$idx === (int)$request->hover_image_index) {
                $img->update(['is_hover' => true]);
            }
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Товар успешно обновлён');
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар удалён');
    }

    public function updateOrder(Request $request)
    {
        $orderData = (array)$request->get('order', []);
        foreach ($orderData as $idx => $pid) {
            if (is_numeric($pid)) {
                Product::where('id', $pid)->update(['order' => $idx + 1]);
            }
        }
        return response()->json(['success' => true]);
    }
}
