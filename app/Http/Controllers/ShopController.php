<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->filters($request);

        return view('shop.index', [
            'title' => 'Veikals | Top Care Group',
            'description' => 'Apskatiet Top Care Group veikala aktīvās preces pēc kategorijām.',
            'canonical' => '/veikals',
            'categories' => $this->shopCategories(),
            'products' => $this->paginatedProducts($this->applyFilters($this->activeProducts(), $filters)),
            'currentCategory' => null,
            'filters' => $filters,
        ]);
    }

    public function category(Request $request, Category $category): View
    {
        abort_unless($category->is_active, 404);

        $filters = $this->filters($request);

        return view('shop.index', [
            'title' => "{$category->name} | Veikals | Top Care Group",
            'description' => "Apskatiet kategorijas {$category->name} aktīvās preces Top Care Group veikalā.",
            'canonical' => route('shop.category', $category, false),
            'categories' => $this->shopCategories(),
            'products' => $this->paginatedProducts(
                $this->applyFilters(
                    $this->activeProducts()->whereBelongsTo($category),
                    $filters
                )
            ),
            'currentCategory' => $category,
            'filters' => $filters,
        ]);
    }

    public function show(Product $product): View
    {
        $product->loadMissing([
            'category',
            'productImages' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
        ]);

        abort_unless($product->is_active && $product->category?->is_active, 404);

        return view('shop.show', [
            'title' => "{$product->name} | Veikals | Top Care Group",
            'description' => $product->description
                ? str($product->description)->limit(160)->toString()
                : "Apskatiet preces {$product->name} informāciju Top Care Group veikalā.",
            'canonical' => route('shop.show', $product, false),
            'categories' => $this->shopCategories(),
            'product' => $product,
        ]);
    }

    private function activeProducts()
    {
        return Product::query()
            ->with([
                'category',
                'productImages' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
            ])
            ->where('is_active', true)
            ->whereHas('category', fn ($query) => $query->where('is_active', true));
    }

    private function paginatedProducts($query): LengthAwarePaginator
    {
        return $query
            ->paginate(12)
            ->onEachSide(1)
            ->withQueryString()
            ->through(function (Product $product) {
                $product->setRelation(
                    'productImages',
                    $product->productImages->sortBy([
                        ['sort_order', 'asc'],
                        ['id', 'asc'],
                    ])->values()
                );

                return $product;
            });
    }

    private function applyFilters($query, array $filters)
    {
        if ($filters['availability'] === 'in_stock') {
            $query->where('stock_quantity', '>', 0);
        }

        if ($filters['availability'] === 'out_of_stock') {
            $query->where('stock_quantity', '<=', 0);
        }

        if ($filters['price_min'] !== null) {
            $query->where('price', '>=', $filters['price_min']);
        }

        if ($filters['price_max'] !== null) {
            $query->where('price', '<=', $filters['price_max']);
        }

        match ($filters['sort']) {
            'newest' => $query->reorder()->latest(),
            'price_asc' => $query->reorder()->orderBy('price')->orderBy('name'),
            'price_desc' => $query->reorder()->orderByDesc('price')->orderBy('name'),
            'name_asc' => $query->reorder()->orderBy('name'),
            default => $query->reorder()->latest(),
        };

        return $query;
    }

    private function shopCategories()
    {
        return Category::query()
            ->where('is_active', true)
            ->withCount([
                'products as active_products_count' => fn ($query) => $query->where('is_active', true),
            ])
            ->orderBy('name')
            ->get();
    }

    private function filters(Request $request): array
    {
        $availability = $request->string('availability')->value();
        $sort = $request->string('sort')->value();
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');

        return [
            'availability' => in_array($availability, ['in_stock', 'out_of_stock'], true) ? $availability : null,
            'price_min' => is_numeric($priceMin) ? max(0, (float) $priceMin) : null,
            'price_max' => is_numeric($priceMax) ? max(0, (float) $priceMax) : null,
            'sort' => in_array($sort, ['newest', 'price_asc', 'price_desc', 'name_asc'], true) ? $sort : 'newest',
        ];
    }
}
