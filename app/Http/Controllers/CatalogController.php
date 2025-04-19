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

        $products = $query->paginate(16);

        if ($products->isEmpty() && $request->page > 1) {
            $lastPage = $products->lastPage();
            return redirect()->route('catalog.index', ['page' => $lastPage]);
        }

        $title = "Каталог одежды";
        return view('catalog.index', [
            'title' => $title,
            'products' => $products,
            'searchTerm' => $searchTerm,
            'category' => $category,
            'sortBy' => $sortBy
        ]);
    }

    public function novelty(Request $request): View|RedirectResponse
    {
        $searchTerm = $request->input('search') ?? '';
        $category = $request->input('category') ?? '';
        $sortBy = $request->input('sort_by') ?? '';

        $query = Product::query();

        // Товары, созданные за последнюю неделю
        $query->where('created_at', '>=', now()->subWeek());

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('type', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('color', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('brand', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

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
            }
        } else {
            // По умолчанию — сначала самые новые
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(16);

        if ($products->isEmpty() && $request->page > 1) {
            $lastPage = $products->lastPage();
            return redirect()->route('catalog.novelty', ['page' => $lastPage]);
        }

        $title = "Новинки";

        return view('catalog.novelty', [
            'title' => $title,
            'products' => $products,
            'searchTerm' => $searchTerm,
            'category' => $category,
            'sortBy' => $sortBy
        ]);
    }


    public function limited(Request $request): View|RedirectResponse
    {
        $searchTerm = $request->input('search') ?? '';
        $category = $request->input('category') ?? '';
        $sortBy = $request->input('sort_by') ?? '';

        $query = Product::query();

        // Товары, созданные за последнюю неделю
        $query->where('is_limited', '=', 1);

        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('type', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('color', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('brand', function($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

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
            }
        } else {
            // По умолчанию — сначала самые новые
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(16);

        if ($products->isEmpty() && $request->page > 1) {
            $lastPage = $products->lastPage();
            return redirect()->route('catalog.limited', ['page' => $lastPage]);
        }

        $title = "Limited Edition";

        return view('catalog.limited', [
            'title' => $title,
            'products' => $products,
            'searchTerm' => $searchTerm,
            'category' => $category,
            'sortBy' => $sortBy
        ]);
    }

}
