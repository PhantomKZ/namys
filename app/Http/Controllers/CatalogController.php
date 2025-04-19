<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    protected array $filters = [];

    public function __construct(Request $request)
    {
        $this->filters = [
            'type_id' => $request->input('type_id'),
            'brand_id' => $request->input('brand_id'),
            'color_id' => $request->input('color_id'),
            'material_id' => $request->input('material_id'),
        ];
    }
    public function index(Request $request): View|RedirectResponse
    {
        $searchTerm = $request->input('search') ?? '';
        $category = $request->input('category') ?? '';
        $sortBy = $request->input('sort_by') ?? '';

        $query = Product::query();

        $this->applySearchFilter($query, $searchTerm);
        $this->applySort($query, $sortBy);
        $this->applyFilter($query, $this->filters);

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

        $this->applySearchFilter($query, $searchTerm);
        $this->applySort($query, $sortBy);
        $this->applyFilter($query, $this->filters);

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

        $this->applySearchFilter($query, $searchTerm);
        $this->applySort($query, $sortBy);
        $this->applyFilter($query, $this->filters);

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

    protected function applySearchFilter($query, $searchTerm)
    {
        if ($searchTerm) {
            $words = explode(' ', $searchTerm);

            $query->where(function($q) use ($words) {
                foreach ($words as $word) {
                    $q->where(function($q2) use ($word) {
                        $q2->where('name', 'like', '%' . $word . '%')
                            ->orWhereHas('type', function($query) use ($word) {
                                $query->where('name', 'like', '%' . $word . '%');
                            })
                            ->orWhereHas('color', function($query) use ($word) {
                                $query->where('name', 'like', '%' . $word . '%');
                            })
                            ->orWhereHas('brand', function($query) use ($word) {
                                $query->where('name', 'like', '%' . $word . '%');
                            });
                    });
                }
            });
        }
        return $query;
    }

    protected function applySort($query, $sortBy)
    {
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
            $query->orderBy('created_at', 'desc');
        }
        return $query;
    }

    protected function applyFilter($query, array $filters = [])
    {
        if (!empty($filters['type_id'])) {
            $query->where('type_id', $filters['type_id']);
        }

        if (!empty($filters['brand_id'])) {
            $query->where('brand_id', $filters['brand_id']);
        }

        if (!empty($filters['color_id'])) {
            if (is_array($filters['color_id'])) {
                $query->whereIn('color_id', $filters['color_id']);
            } else {
                $query->where('color_id', $filters['color_id']);
            }
        }

        if (!empty($filters['material_id'])) {
            $query->where('material_id', $filters['material_id']);
        }

        return $query;
    }

}
