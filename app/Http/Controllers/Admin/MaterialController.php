<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{

    public function index()
    {
        $materials = Material::all();
        return view('admin.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('admin.materials.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (\App\Models\Material::where('name', $request->name)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Материал с таким названием уже существует!');
        }

        try {
            $material = \App\Models\Material::create([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ошибка при создании материала: ' . $e->getMessage());
        }

        return redirect()->route('admin.materials.index')->with('success', "Материал {$material->name} успешно создан!");
    }

    public function edit($id)
    {
        $material = Material::where('id', $id)->firstOrFail();
        return view('admin.materials.form', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = \App\Models\Material::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (\App\Models\Material::where('name', $request->name)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Материал с таким названием уже существует!');
        }

        try {
            $material->update([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ошибка при обновлении материала: ' . $e->getMessage());
        }

        return redirect()->route('admin.materials.index')->with('success', "Материал {$material->name} успешно обновлен!");
    }

    public function destroy($id)
    {
        $material = Material::where('id', $id)->firstOrFail();
        $material->delete();
        return redirect()->route('admin.materials.index')->with('success', "Материал {$material->name} успешно удален!");
    }

}
