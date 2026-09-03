<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'categories' => Category::withCount('products')->orderBy('sort_order')->take(6)->get(),
            'featured' => Product::with('category')->where('featured', true)->take(8)->get(),
            'latest' => Product::with('category')->orderBy('source_page')->take(8)->get(),
        ]);
    }

    public function shop(Request $request): View
    {
        $products = Product::query()->with('category')->search($request->string('q')->trim()->toString())
            ->when($request->filled('category'), fn (Builder $query) => $query->whereHas(
                'category', fn (Builder $query) => $query->where('slug', $request->string('category')->toString())
            ))
            ->when($request->filled('brand'), fn (Builder $query) => $query->where('brand', $request->string('brand')->toString()))
            ->when($request->filled('availability'), fn (Builder $query) => $query->where('availability', $request->string('availability')->toString()))
            ->when($request->integer('min_price'), fn (Builder $query, int $price) => $query->where('price', '>=', $price))
            ->when($request->integer('max_price'), fn (Builder $query, int $price) => $query->where('price', '<=', $price));

        match ($request->string('sort')->toString()) {
            'name' => $products->orderBy('name'),
            'price-asc' => $products->orderByRaw('price is null, price asc'),
            'price-desc' => $products->orderByRaw('price is null, price desc'),
            default => $products->orderByDesc('featured')->orderBy('source_page'),
        };

        return view('shop.index', [
            'products' => $products->paginate(16)->withQueryString(),
            'categories' => Category::withCount('products')->orderBy('name')->get(),
            'brands' => Product::whereNotNull('brand')->distinct()->orderBy('brand')->pluck('brand'),
        ]);
    }

    public function product(Product $product): View
    {
        $product->load('category');
        $related = Product::with('category')->whereKeyNot($product->id)
            ->where(fn (Builder $query) => $query->where('category_id', $product->category_id)
                ->when($product->brand, fn (Builder $query) => $query->orWhere('brand', $product->brand)))
            ->orderByRaw('subcategory = ? desc', [$product->subcategory ?? ''])
            ->take(4)->get();

        return view('products.show', compact('product', 'related'));
    }

    public function search(Request $request)
    {
        return Product::with('category')->search($request->string('q')->trim()->toString())
            ->take(7)->get()->map(fn (Product $product) => [
                'name' => $product->name,
                'code' => $product->code,
                'category' => $product->category->name,
                'price' => $product->price,
                'image' => $product->image ? asset('storage/'.$product->image) : null,
                'url' => route('products.show', $product),
            ]);
    }
}
