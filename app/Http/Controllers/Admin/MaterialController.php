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

        $material = Material::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.materials.index')->with('success', "Материал {$material->name} успешно создан!");
    }

    public function edit($id)
    {
        $material = Material::where('id', $id)->firstOrFail();
        return view('admin.materials.form', compact('material'));
    }

    public function update(Request $request, $id)
    {
        $material = Material::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $material->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.materials.index')->with('success', "Материал {$material->name} успешно обновлен!");
    }

    public function destroy($id)
    {
        $material = Material::where('id', $id)->firstOrFail();
        $material->delete();
        return redirect()->route('admin.materials.index')->with('success', "Материал {$material->name} успешно удален!");
    }

}
