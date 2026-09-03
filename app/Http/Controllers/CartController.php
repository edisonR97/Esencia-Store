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
        return view('cart.index', ['cart' => $this->contents()]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $quantity = max(1, min(99, $request->integer('quantity', 1)));

        if ($request->boolean('buy_now')) {
            session(['buy_now' => ['product_id' => $product->id, 'quantity' => $quantity]]);

            return redirect()->route('checkout.index', ['mode' => 'buy-now']);
        }

        $cart = session('cart', []);
        $cart[$product->id] = min(99, ($cart[$product->id] ?? 0) + $quantity);
        session(['cart' => $cart]);

        return back()->with('toast', 'Producto agregado al carrito.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        $quantity = max(0, min(99, $request->integer('quantity')));
        if ($quantity) {
            $cart[$product->id] = $quantity;
        } else {
            unset($cart[$product->id]);
        }
        session(['cart' => $cart]);

        return back();
    }

    public function destroy(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back();
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');
        return back();
    }

    public function contents(): array
    {
        return $this->contentsFromQuantities(session('cart', []));
    }

    public function buyNowContents(): array
    {
        $selection = session('buy_now', []);
        $quantities = isset($selection['product_id'], $selection['quantity'])
            ? [$selection['product_id'] => $selection['quantity']]
            : [];

        return $this->contentsFromQuantities($quantities);
    }

    private function contentsFromQuantities(array $quantities): array
    {
        $products = Product::whereKey(array_keys($quantities))->get()->keyBy('id');
        $items = collect($quantities)->map(function (int $quantity, int|string $id) use ($products) {
            $product = $products->get((int) $id);
            return $product ? compact('product', 'quantity') + ['lineTotal' => ($product->price ?? 0) * $quantity] : null;
        })->filter()->values();

        return [
            'items' => $items,
            'count' => $items->sum('quantity'),
            'subtotal' => $items->sum('lineTotal'),
        ];
    }
}
