<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $types = Type::all();
        return view('admin.categories.form', compact('types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type_id' => 'required|exists:types,id',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'type_id' => $request->type_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', "Категория {$category->name} успешно создана!");
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('admin.categories.show', compact('category'));
    }

    public function edit($slug)
    {
        $types = Type::all();
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('admin.categories.form', compact('category', 'types'));
    }

    public function update(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type_id' => 'required|exists:types,id',
        ]);

        $category->update([
            'name' => $request->name,
            'type_id' => $request->type_id,
        ]);

        return redirect()->route('admin.categories.index')->with('success', "Категория {$category->name} успешно обновлена!");
    }

    public function destroy($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', "Категория {$category->name} успешно удалена!");
    }
}
