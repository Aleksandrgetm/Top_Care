<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = Validator::make($request->all(), [
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['required', 'image', 'max:4096'],
        ], [
            'images.required' => 'Please choose at least one photo before uploading.',
            'images.array' => 'Photos must be uploaded as a file list.',
            'images.min' => 'Please choose at least one photo before uploading.',
            'images.*.required' => 'One of the selected files is missing.',
            'images.*.image' => 'Each uploaded file must be an image.',
            'images.*.max' => 'Each photo must be 4 MB or smaller.',
        ])->validate();

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
            ->with('status', 'Photos uploaded successfully.');
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
