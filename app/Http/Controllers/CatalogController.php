<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $searchTerm = $request->input('search') ?? '';
        $category = $request->input('category') ?? '';
        $sortBy = $request->input('sort_by') ?? '';

        $query = Product::query();

        // Поиск по названию, типу, цвету, бренду
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%') // Поиск по названию
                ->orWhereHas('type', function($query) use ($searchTerm) {
                    $query->where('name', 'like', '%' . $searchTerm . '%'); // Поиск по типу
                })
                    ->orWhereHas('color', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%'); // Поиск по цвету
                    })
                    ->orWhereHas('brand', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%'); // Поиск по бренду
                    });
            });
        }

        // Применение сортировки
        if ($sortBy) {
            switch ($sortBy) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    // Можно добавить другие сортировки, если необходимо
                    break;
            }
        }

        // Пагинация
        $products = $query->paginate(16);

        // Если на запрашиваемой странице нет товаров, перенаправляем на последнюю страницу
        if ($products->isEmpty() && $request->page > 1) {
            $lastPage = $products->lastPage();
            return redirect()->route('catalog', ['page' => $lastPage]);
        }

        $title = "Каталог одежды";
        return view('catalog.index', [
            'title' => $title,
            'products' => $products,
            'searchTerm' => $searchTerm,
            'category' => $category,
            'sortBy' => $sortBy // Передаем параметр сортировки в представление
        ]);
    }

    public function novelty(): View
    {
        $title = "Новинки";
        return view('catalog.novelty', ['title' => $title]);
    }

    public function limited(): View
    {
        $title = "Limited Edition";
        return view('catalog.limited', ['title' => $title]);
    }

}
