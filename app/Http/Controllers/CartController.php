<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->cart();

        return view('shop.cart', [
            'title' => 'Grozs | Top Care Group',
            'description' => 'Apskatiet Top Care Group veikala grozu un izvēlēto preču kopsavilkumu.',
            'canonical' => '/grozs',
            'items' => array_values($cart),
            'cartTotal' => $this->cartTotal($cart),
            'cartCount' => $this->cartCount($cart),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        if (! $product->is_active) {
            return back()->with('error', 'Šo preci pašlaik nevar pievienot grozam.');
        }

        if ($product->stock_quantity < 1) {
            return back()->with('error', 'Prece pašlaik nav noliktavā.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:' . (int) $product->stock_quantity],
        ]);

        $cart = $this->cart();
        $existingQuantity = (int) ($cart[$product->id]['quantity'] ?? 0);
        $requestedQuantity = (int) $validated['quantity'];
        $newQuantity = $existingQuantity + $requestedQuantity;

        if ($newQuantity > $product->stock_quantity) {
            return back()->with('error', "Pieejamais daudzums šai precei ir {$product->stock_quantity} gab.");
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => (float) $product->price,
            'quantity' => $newQuantity,
            'image' => $product->productImages()->orderBy('sort_order')->orderBy('id')->value('image_path'),
            'stock_quantity' => (int) $product->stock_quantity,
        ];

        $this->putCart($cart);

        return redirect()
            ->route('cart.index')
            ->with('status', 'Prece pievienota grozam.');
    }

    public function update(Request $request, int $product): RedirectResponse
    {
        $cart = $this->cart();

        if (! isset($cart[$product])) {
            return redirect()->route('cart.index')->with('error', 'Prece grozā netika atrasta.');
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $productModel = Product::query()->find($product);

        if (! $productModel?->is_active) {
            return redirect()->route('cart.index')->with('error', 'Šo preci vairs nevar pasūtīt.');
        }

        $maxQuantity = max(0, (int) $productModel->stock_quantity);

        if ($maxQuantity < 1) {
            unset($cart[$product]);
            $this->putCart($cart);

            return redirect()->route('cart.index')->with('error', 'Prece vairs nav noliktavā un tika izņemta no groza.');
        }

        if ($validated['quantity'] > $maxQuantity) {
            return redirect()->route('cart.index')->with('error', "Pieejamais daudzums šai precei ir {$maxQuantity} gab.");
        }

        $cart[$product]['quantity'] = $validated['quantity'];
        $cart[$product]['stock_quantity'] = $maxQuantity;
        $cart[$product]['price'] = (float) $productModel->price;
        $cart[$product]['name'] = $productModel->name;
        $cart[$product]['slug'] = $productModel->slug;
        $cart[$product]['description'] = $productModel->description;
        $cart[$product]['image'] = $productModel->productImages()->orderBy('sort_order')->orderBy('id')->value('image_path');

        $this->putCart($cart);

        return redirect()->route('cart.index')->with('status', 'Grozs atjaunināts.');
    }

    public function destroy(int $product): RedirectResponse
    {
        $cart = $this->cart();

        if (isset($cart[$product])) {
            unset($cart[$product]);
            $this->putCart($cart);
        }

        return redirect()->route('cart.index')->with('status', 'Prece izņemta no groza.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('status', 'Grozs ir iztīrīts.');
    }

    private function cart(): array
    {
        return session('cart', []);
    }

    private function putCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    private function cartCount(array $cart): int
    {
        return (int) collect($cart)->sum('quantity');
    }

    private function cartTotal(array $cart): float
    {
        return (float) collect($cart)->sum(fn (array $item) => ((float) $item['price']) * ((int) $item['quantity']));
    }
}
