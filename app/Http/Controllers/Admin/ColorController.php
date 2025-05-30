<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{

    public function index()
    {
        $colors = Color::all();
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (\App\Models\Color::where('name', $request->name)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Цвет с таким названием уже существует!');
        }

        try {
            $color = \App\Models\Color::create([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ошибка при создании цвета: ' . $e->getMessage());
        }

        return redirect()->route('admin.colors.index')->with('success', "Цвет {$color->name} успешно создан!");
    }

    public function edit($id)
    {
        $color = Color::where('id', $id)->firstOrFail();
        return view('admin.colors.form', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $color = \App\Models\Color::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if (\App\Models\Color::where('name', $request->name)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Цвет с таким названием уже существует!');
        }

        try {
            $color->update([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ошибка при обновлении цвета: ' . $e->getMessage());
        }

        return redirect()->route('admin.colors.index')->with('success', "Цвет {$color->name} успешно обновлен!");
    }

    public function destroy($id)
    {
        $color = Color::where('id', $id)->firstOrFail();
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', "Цвет {$color->name} успешно удален!");
    }

}
