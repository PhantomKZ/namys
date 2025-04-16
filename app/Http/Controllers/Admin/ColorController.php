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

        $color = Color::create([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.colors.index')->with('success', "Цвет {$color->name} успешно создан!");
    }

    public function edit($id)
    {
        $color = Color::where('id', $id)->firstOrFail();
        return view('admin.colors.form', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $color = Color::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $color->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.colors.index')->with('success', "Цвет {$color->name} успешно обновлен!");
    }

    public function destroy($id)
    {
        $color = Color::where('id', $id)->firstOrFail();
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', "Цвет {$color->name} успешно удален!");
    }

}
