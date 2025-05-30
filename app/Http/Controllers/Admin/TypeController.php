<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{

    public function index()
    {
        $types = Type::all();
        return view('admin.types.index', compact('types'));
    }

    public function create()
    {
        return view('admin.types.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Проверка на дубликат
        if (Type::where('name', $request->name)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Тип одежды с таким названием уже существует!');
        }

        try {
            $type = Type::create([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ошибка при создании типа: ' . $e->getMessage());
        }

        return redirect()->route('admin.types.index')->with('success', "Тип одежды {$type->name} успешно создан!");
    }

    public function edit($id)
    {
        $type = Type::where('id', $id)->firstOrFail();
        return view('admin.types.form', compact('type'));
    }

    public function update(Request $request, $id)
    {
        $type = Type::where('id', $id)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Проверка на дубликат (кроме текущего типа)
        if (Type::where('name', $request->name)->where('id', '!=', $id)->exists()) {
            return redirect()->back()->withInput()->with('error', 'Тип одежды с таким названием уже существует!');
        }

        try {
            $type->update([
                'name' => $request->name,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Ошибка при обновлении типа: ' . $e->getMessage());
        }

        return redirect()->route('admin.types.index')->with('success', "Тип одежды {$type->name} успешно обновлен!");
    }

    public function destroy($id)
    {
        $type = Type::where('id', $id)->firstOrFail();
        $type->delete();
        return redirect()->route('admin.types.index')->with('success', "Тип одежды {$type->name} успешно удален!");
    }

}
