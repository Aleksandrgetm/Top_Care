<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'max:5120'],
        ]);

        $nextSortOrder = (int) $product->productImages()->max('sort_order');

        foreach ($validated['images'] as $image) {
            $nextSortOrder++;

            $product->productImages()->create([
                'image_path' => $image->store('products', 'public'),
                'sort_order' => $nextSortOrder,
            ]);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Product images uploaded successfully.');
    }

    public function destroy(ProductImage $productImage): RedirectResponse
    {
        $product = $productImage->product;

        $productImage->delete();

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('status', 'Product image deleted successfully.');
    }
}
