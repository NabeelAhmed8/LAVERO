<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function home(): View
    {
        $products = Product::with('category')->where('in_stock', true)->get();
        $categories = Category::all();

        return view('home', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function index(Request $request): View
    {
        $query = Product::with('category')->where('in_stock', true);

        if ($request->filled('category')) {
            $query->whereHas('category', function ($q) use ($request): void {
                $q->where('slug', $request->input('category'));
            });
        }

        if ($request->input('sort') === 'price_asc') {
            $query->orderBy('price', 'asc');
        } elseif ($request->input('sort') === 'price_desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(9)->withQueryString();
        $categories = Category::withCount('products')->get();

        return view('shop.index', [
            'products' => $products,
            'categories' => $categories,
            'activeCategory' => $request->input('category'),
            'activeSort' => $request->input('sort'),
        ]);
    }

    public function show(string $slug): View
    {
        $product = Product::with('category')->where('slug', $slug)->firstOrFail();
        $related = Product::where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->take(3)
            ->get();

        return view('shop.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }
}
