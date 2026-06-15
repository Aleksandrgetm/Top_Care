<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        return view('shop.index', [
            'title' => 'Veikals | Top Care Group',
            'description' => 'Apskatiet Top Care Group veikala aktīvās preces pēc kategorijām.',
            'canonical' => '/veikals',
            'heading' => 'Veikals',
            'intro' => 'Apskatiet Top Care Group piedāvātās aktīvās preces un atlasiet tās pēc kategorijām.',
            'categories' => $this->shopCategories(),
            'products' => $this->activeProducts()->get(),
            'currentCategory' => null,
        ]);
    }

    public function category(Category $category): View
    {
        abort_unless($category->is_active, 404);

        return view('shop.index', [
            'title' => "{$category->name} | Veikals | Top Care Group",
            'description' => "Apskatiet kategorijas {$category->name} aktīvās preces Top Care Group veikalā.",
            'canonical' => route('shop.category', $category, false),
            'heading' => $category->name,
            'intro' => "Kategorijā {$category->name} pieejamās aktīvās preces.",
            'categories' => $this->shopCategories(),
            'products' => $this->activeProducts()
                ->whereBelongsTo($category)
                ->get(),
            'currentCategory' => $category,
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
            ->whereHas('category', fn ($query) => $query->where('is_active', true))
            ->orderBy('name');
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
}
