<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'totalSales' => Order::where('payment_status', 'paid')->sum('total'),
            'totalOrders' => Order::count(),
            'totalProducts' => Product::count(),
            'totalInquiries' => ContactMessage::count(),
            'recentOrders' => Order::with('items')->latest()->take(6)->get(),
            'recentInquiries' => ContactMessage::latest()->take(5)->get(),
        ]);
    }

    public function products(): View
    {
        return view('admin.products.index', [
            'products' => Product::with('category')->latest()->paginate(15),
        ]);
    }

    public function createProduct(): View
    {
        return view('admin.products.form', [
            'product' => new Product,
            'categories' => Category::orderBy('name')->get(),
            'isEdit' => false,
        ]);
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'material' => 'required|string|max:255',
            'tag' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:10240',
            'hover_image' => 'nullable|string|max:500',
            'hover_image_file' => 'nullable|image|max:10240',
            'sizes' => 'nullable|string',
            'description' => 'required|string',
            'featured' => 'nullable|boolean',
            'stock_status' => 'nullable|string|in:in_stock,low_stock,out_of_stock',
            'stock_quantity' => 'nullable|integer|min:0',
            'in_stock' => 'nullable|boolean',
        ]);

        $imageUrl = null;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $imageUrl = '/storage/'.$path;
        } elseif (! empty($validated['image'])) {
            $imageUrl = $validated['image'];
        }

        if (! $imageUrl) {
            return back()->withErrors(['image' => 'Primary Garment Image is required (upload an image file or provide a URL).'])->withInput();
        }

        $hoverImageUrl = null;
        if ($request->hasFile('hover_image_file')) {
            $path = $request->file('hover_image_file')->store('products', 'public');
            $hoverImageUrl = '/storage/'.$path;
        } elseif (! empty($validated['hover_image'])) {
            $hoverImageUrl = $validated['hover_image'];
        }

        $sizes = ! empty($validated['sizes'])
            ? array_filter(array_map('trim', explode(',', $validated['sizes'])))
            : ['S', 'M', 'L'];

        $stockQuantity = isset($validated['stock_quantity']) ? (int) $validated['stock_quantity'] : 10;
        $stockStatus = $validated['stock_status'] ?? 'in_stock';

        if ($stockStatus === 'out_of_stock' || $stockQuantity === 0) {
            $stockStatus = 'out_of_stock';
            $inStock = false;
        } elseif ($stockStatus === 'low_stock' || $stockQuantity <= 5) {
            $stockStatus = 'low_stock';
            $inStock = true;
        } else {
            $stockStatus = 'in_stock';
            $inStock = true;
        }

        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.Str::random(5),
            'price' => $validated['price'],
            'material' => $validated['material'],
            'tag' => $validated['tag'] ?? null,
            'image' => $imageUrl,
            'hover_image' => $hoverImageUrl,
            'sizes' => $sizes,
            'description' => $validated['description'],
            'featured' => $request->boolean('featured'),
            'stock_quantity' => $stockQuantity,
            'stock_status' => $stockStatus,
            'in_stock' => $inStock,
        ]);

        return redirect()->route('admin.products')->with('success', 'Creation added to the Atelier catalogue.');
    }

    public function editProduct(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
            'isEdit' => true,
        ]);
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'material' => 'required|string|max:255',
            'tag' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|max:10240',
            'hover_image' => 'nullable|string|max:500',
            'hover_image_file' => 'nullable|image|max:10240',
            'sizes' => 'nullable|string',
            'description' => 'required|string',
            'featured' => 'nullable|boolean',
            'stock_status' => 'nullable|string|in:in_stock,low_stock,out_of_stock',
            'stock_quantity' => 'nullable|integer|min:0',
            'in_stock' => 'nullable|boolean',
        ]);

        $imageUrl = $product->image;
        if ($request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('products', 'public');
            $imageUrl = '/storage/'.$path;
        } elseif (! empty($validated['image'])) {
            $imageUrl = $validated['image'];
        }

        $hoverImageUrl = $product->hover_image;
        if ($request->hasFile('hover_image_file')) {
            $path = $request->file('hover_image_file')->store('products', 'public');
            $hoverImageUrl = '/storage/'.$path;
        } elseif (array_key_exists('hover_image', $validated)) {
            $hoverImageUrl = $validated['hover_image'];
        }

        $sizes = ! empty($validated['sizes'])
            ? array_filter(array_map('trim', explode(',', $validated['sizes'])))
            : $product->sizes;

        $stockQuantity = isset($validated['stock_quantity']) ? (int) $validated['stock_quantity'] : ($product->stock_quantity ?? 10);
        $stockStatus = $validated['stock_status'] ?? ($product->stock_status ?? 'in_stock');

        if ($stockStatus === 'out_of_stock' || $stockQuantity === 0) {
            $stockStatus = 'out_of_stock';
            $inStock = false;
        } elseif ($stockStatus === 'low_stock' || $stockQuantity <= 5) {
            $stockStatus = 'low_stock';
            $inStock = true;
        } else {
            $stockStatus = 'in_stock';
            $inStock = true;
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'material' => $validated['material'],
            'tag' => $validated['tag'] ?? null,
            'image' => $imageUrl,
            'hover_image' => $hoverImageUrl,
            'sizes' => $sizes,
            'description' => $validated['description'],
            'featured' => $request->boolean('featured'),
            'stock_quantity' => $stockQuantity,
            'stock_status' => $stockStatus,
            'in_stock' => $inStock,
        ]);

        return redirect()->route('admin.products')->with('success', "{$product->name} updated successfully.");
    }

    public function destroyProduct(Product $product): RedirectResponse
    {
        $name = $product->name;
        $product->delete();

        return redirect()->route('admin.products')->with('success', "{$name} removed from catalogue.");
    }

    public function orders(Request $request): View
    {
        $status = $request->query('status');
        $query = Order::with(['items', 'user'])->latest();

        if ($status && in_array($status, ['processing', 'shipped', 'delivered', 'cancelled'])) {
            $query->where('fulfillment_status', $status);
        }

        return view('admin.orders.index', [
            'orders' => $query->paginate(15)->withQueryString(),
            'currentStatus' => $status,
            'counts' => [
                'all' => Order::count(),
                'processing' => Order::where('fulfillment_status', 'processing')->count(),
                'shipped' => Order::where('fulfillment_status', 'shipped')->count(),
                'delivered' => Order::where('fulfillment_status', 'delivered')->count(),
                'cancelled' => Order::where('fulfillment_status', 'cancelled')->count(),
            ],
        ]);
    }

    public function updateOrderStatus(Request $request, Order $order): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'fulfillment_status' => 'required|in:processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $order->update([
            'fulfillment_status' => $validated['fulfillment_status'],
            'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$order->order_number} tracking updated to ".ucfirst($order->fulfillment_status),
                'order' => $order,
            ]);
        }

        return redirect()->back()->with('success', "Order #{$order->order_number} tracking status updated to ".ucfirst($order->fulfillment_status).'.');
    }

    public function inquiries(): View
    {
        return view('admin.inquiries.index', [
            'inquiries' => ContactMessage::latest()->paginate(15),
        ]);
    }
}
