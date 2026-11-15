<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $subtotal = array_reduce($cart, fn (float $carry, array $item): float => $carry + ($item['price'] * $item['quantity']), 0.0);

        return view('cart.index', [
            'cart' => $cart,
            'subtotal' => $subtotal,
        ]);
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = (int) ($validated['quantity'] ?? 1);
        $size = $validated['size'];
        $itemKey = $product->id.'-'.$size;

        $cart = session()->get('cart', []);

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $quantity;
        } else {
            $cart[$itemKey] = [
                'key' => $itemKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (float) $product->price,
                'size' => $size,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }

        foreach ($cart as $k => &$item) {
            $item['key'] = (string) $k;
        }
        unset($item);

        session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = array_reduce($cart, fn (int $c, array $item): int => $c + $item['quantity'], 0);
            $subtotal = array_reduce($cart, fn (float $carry, array $item): float => $carry + ($item['price'] * $item['quantity']), 0.0);

            return response()->json([
                'success' => true,
                'message' => "{$product->name} (Size {$size}) added to your bag.",
                'cart' => $cart,
                'cart_count' => $cartCount,
                'subtotal' => $subtotal,
            ]);
        }

        return redirect()->back()->with('success', "{$product->name} (Size {$size}) added to your bag.");
    }

    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'item_key' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$validated['item_key']])) {
            $cart[$validated['item_key']]['quantity'] = (int) $validated['quantity'];
            foreach ($cart as $k => &$item) {
                $item['key'] = (string) $k;
            }
            unset($item);
            session()->put('cart', $cart);
        }

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = array_reduce($cart, fn (int $c, array $item): int => $c + $item['quantity'], 0);
            $subtotal = array_reduce($cart, fn (float $carry, array $item): float => $carry + ($item['price'] * $item['quantity']), 0.0);

            return response()->json([
                'success' => true,
                'message' => 'Your shopping bag was updated.',
                'cart' => $cart,
                'cart_count' => $cartCount,
                'subtotal' => $subtotal,
            ]);
        }

        return redirect()->back()->with('success', 'Your shopping bag was updated.');
    }

    public function remove(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'item_key' => 'required|string',
        ]);

        $cart = session()->get('cart', []);

        if (isset($cart[$validated['item_key']])) {
            unset($cart[$validated['item_key']]);
            foreach ($cart as $k => &$item) {
                $item['key'] = (string) $k;
            }
            unset($item);
            session()->put('cart', $cart);
        }

        if ($request->expectsJson() || $request->ajax()) {
            $cartCount = array_reduce($cart, fn (int $c, array $item): int => $c + $item['quantity'], 0);
            $subtotal = array_reduce($cart, fn (float $carry, array $item): float => $carry + ($item['price'] * $item['quantity']), 0.0);

            return response()->json([
                'success' => true,
                'message' => 'Item removed from your bag.',
                'cart' => $cart,
                'cart_count' => $cartCount,
                'subtotal' => $subtotal,
            ]);
        }

        return redirect()->back()->with('success', 'Item removed from your bag.');
    }

    public function clear(): RedirectResponse
    {
        session()->forget('cart');

        return redirect()->route('cart.index')->with('success', 'Shopping bag emptied.');
    }
}
