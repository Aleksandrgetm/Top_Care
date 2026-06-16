<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\ProductImageThumbnailService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductImageThumbnailService $productImageThumbnailService,
    ) {
    }

    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::query()
                ->with([
                    'category',
                    'primaryImage' => fn ($query) => $query->select([
                        'product_images.id',
                        'product_images.product_id',
                        'product_images.image_path',
                        'product_images.thumbnail_path',
                        'product_images.sort_order',
                    ]),
                ])
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product' => new Product([
                'is_active' => true,
                'stock_quantity' => 0,
                'supports_courier' => true,
                'supports_dpd' => false,
                'supports_omniva' => false,
            ]),
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProduct($request);

        $product = DB::transaction(function () use ($request, $validated): Product {
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name']),
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_active' => $request->boolean('is_active', true),
                'supports_courier' => $request->boolean('supports_courier', true),
                'supports_dpd' => $request->boolean('supports_dpd'),
                'supports_omniva' => $request->boolean('supports_omniva'),
            ]);

            $this->storeImages($product, $request->file('images', []));

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load([
            'category',
            'productImages' => fn ($query) => $query->orderBy('sort_order')->orderBy('id'),
        ]);

        return view('admin.products.edit', [
            'product' => $product,
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function () use ($request, $product, $validated): void {
            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => $this->generateUniqueSlug($validated['name'], $product),
                'description' => $validated['description'] ?? null,
                'price' => $validated['price'],
                'stock_quantity' => $validated['stock_quantity'],
                'is_active' => $request->boolean('is_active'),
                'supports_courier' => $request->boolean('supports_courier', true),
                'supports_dpd' => $request->boolean('supports_dpd'),
                'supports_omniva' => $request->boolean('supports_omniva'),
            ]);

            $this->storeImages($product, $request->file('images', []));
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Product deleted successfully.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', Rule::exists(Category::class, 'id')],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'supports_courier' => ['nullable', 'boolean'],
            'supports_dpd' => ['nullable', 'boolean'],
            'supports_omniva' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function storeImages(Product $product, array $images): void
    {
        $nextSortOrder = (int) $product->productImages()->max('sort_order');

        foreach ($images as $image) {
            $nextSortOrder++;
            $paths = $this->productImageThumbnailService->storeUploadedImage($image);

            $product->productImages()->create([
                'image_path' => $paths['image_path'],
                'thumbnail_path' => $paths['thumbnail_path'],
                'sort_order' => $nextSortOrder,
            ]);
        }
    }

    private function categories()
    {
        return Category::query()
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();
    }

    private function generateUniqueSlug(string $name, ?Product $product = null): string
    {
        $baseSlug = Str::slug($name);

        if ($baseSlug === '') {
            $baseSlug = 'product';
        }

        $slug = $baseSlug;
        $suffix = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($product, fn ($query) => $query->whereKeyNot($product->getKey()))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }
}
