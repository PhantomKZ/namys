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

        $type = Type::create([
            'name' => $request->name,
        ]);

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

        $type->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.types.index')->with('success', "Тип одежды {$type->name} успешно обновлен!");
    }

    public function destroy($id)
    {
        $type = Type::where('id', $id)->firstOrFail();
        $type->delete();
        return redirect()->route('admin.types.index')->with('success', "Тип одежды {$type->name} успешно удален!");
    }

}
