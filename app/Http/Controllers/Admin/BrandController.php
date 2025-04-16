<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand = Brand::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.brands.index')->with('success', "Бренд {$brand->name} успешно создан!");
    }

    public function edit($id)
    {
        $brand = Brand::where('id', $id)->firstOrFail();
        return view('admin.brands.form', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $brand->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.brands.index')->with('success', "Бренд {$brand->name} успешно обновлен!");
    }

    public function destroy($id)
    {
        $brand = Brand::where('id', $id)->firstOrFail();
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', "Бренд {$brand->name} успешно удален!");
    }

}
