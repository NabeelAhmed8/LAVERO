<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    $this->category = Category::create(['name' => 'Tailoring', 'slug' => 'tailoring']);

    $this->admin = User::create([
        'name' => 'Atelier Director',
        'email' => 'admin@lavero.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
        'is_admin' => true,
    ]);

    $this->client = User::create([
        'name' => 'Private Client',
        'email' => 'client@lavero.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
        'is_admin' => false,
    ]);
});

test('guests cannot access admin dashboard', function (): void {
    $this->get('/admin')->assertRedirect('/login');
});

test('non-admin users receive 403 on admin dashboard', function (): void {
    $this->actingAs($this->client)
        ->get('/admin')
        ->assertForbidden();
});

test('admin can access dashboard and metrics', function (): void {
    $this->actingAs($this->admin)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Atelier CMS Dashboard');
});

test('admin can view creations list', function (): void {
    Product::create([
        'category_id' => $this->category->id,
        'name' => 'Silk Lapel Blazer',
        'slug' => 'silk-lapel-blazer',
        'price' => 1950,
        'material' => 'Mulberry Silk',
        'image' => 'https://example.com/blazer.jpg',
        'sizes' => ['S', 'M'],
    ]);

    $this->actingAs($this->admin)
        ->get('/admin/products')
        ->assertOk()
        ->assertSee('Silk Lapel Blazer');
});

test('admin can create a new creation', function (): void {
    $response = $this->actingAs($this->admin)
        ->post('/admin/products', [
            'name' => 'Double-Breasted Wool Overcoat',
            'category_id' => $this->category->id,
            'price' => 2800,
            'material' => 'Virgin Wool & Cashmere',
            'tag' => 'Runway Drop',
            'sizes' => '48, 50, 52',
            'image' => 'https://images.unsplash.com/photo-1544441893-675973e31985?auto=format&fit=crop&w=1200&q=80',
            'description' => 'Architectural tailoring handcrafted in Italy.',
            'featured' => 1,
            'in_stock' => 1,
        ]);

    $response->assertRedirect('/admin/products');
    $this->assertDatabaseHas('products', [
        'name' => 'Double-Breasted Wool Overcoat',
        'price' => 2800,
    ]);
});

test('admin can update an existing creation', function (): void {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Original Tuxedo',
        'slug' => 'original-tuxedo',
        'price' => 2100,
        'material' => 'Wool',
        'image' => 'https://example.com/tux.jpg',
        'sizes' => ['M'],
    ]);

    $response = $this->actingAs($this->admin)
        ->put("/admin/products/{$product->id}", [
            'name' => 'Updated Evening Tuxedo',
            'category_id' => $this->category->id,
            'price' => 2450,
            'material' => 'Super 150s Wool',
            'sizes' => 'M, L',
            'image' => 'https://example.com/tux-new.jpg',
            'description' => 'Updated luxury evening description.',
        ]);

    $response->assertRedirect('/admin/products');
    expect($product->fresh()->name)->toBe('Updated Evening Tuxedo');
    expect((float) $product->fresh()->price)->toBe(2450.0);
});

test('admin can delete a product', function (): void {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Archival Piece To Delete',
        'slug' => 'archival-piece-delete',
        'price' => 1200,
        'material' => 'Silk',
        'image' => 'https://example.com/archival.jpg',
    ]);

    $response = $this->actingAs($this->admin)
        ->delete("/admin/products/{$product->id}");

    $response->assertRedirect('/admin/products');
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
});

test('admin can view orders and inquiries tabs', function (): void {
    $this->actingAs($this->admin)
        ->get('/admin/orders')
        ->assertOk();

    $this->actingAs($this->admin)
        ->get('/admin/inquiries')
        ->assertOk();
});

test('admin can update order tracking status and tracking number', function (): void {
    $order = Order::create([
        'order_number' => 'LAV-TEST-001',
        'customer_name' => 'Elena Rostova',
        'email' => 'elena@example.com',
        'address' => 'Via Montenapoleone 8',
        'city' => 'Milan',
        'country' => 'Italy',
        'postal_code' => '20121',
        'payment_method' => 'stripe',
        'payment_status' => 'paid',
        'fulfillment_status' => 'processing',
        'subtotal' => 1850,
        'shipping' => 0,
        'total' => 1850,
    ]);

    $response = $this->actingAs($this->admin)
        ->put("/admin/orders/{$order->id}/status", [
            'fulfillment_status' => 'shipped',
            'tracking_number' => 'DHL-EXP-9921',
        ]);

    $response->assertRedirect();
    expect($order->fresh()->fulfillment_status)->toBe('shipped');
    expect($order->fresh()->tracking_number)->toBe('DHL-EXP-9921');
});

test('admin can filter orders by fulfillment status', function (): void {
    Order::create([
        'order_number' => 'LAV-PROC-01',
        'customer_name' => 'Alice',
        'email' => 'alice@example.com',
        'address' => 'Paris',
        'city' => 'Paris',
        'country' => 'France',
        'postal_code' => '75001',
        'payment_method' => 'stripe',
        'payment_status' => 'paid',
        'fulfillment_status' => 'processing',
        'subtotal' => 1000,
        'shipping' => 0,
        'total' => 1000,
    ]);

    Order::create([
        'order_number' => 'LAV-DELIV-01',
        'customer_name' => 'Bob',
        'email' => 'bob@example.com',
        'address' => 'London',
        'city' => 'London',
        'country' => 'UK',
        'postal_code' => 'SW1',
        'payment_method' => 'stripe',
        'payment_status' => 'paid',
        'fulfillment_status' => 'delivered',
        'subtotal' => 2000,
        'shipping' => 0,
        'total' => 2000,
    ]);

    $response = $this->actingAs($this->admin)->get('/admin/orders?status=delivered');
    $response->assertOk()
        ->assertSee('LAV-DELIV-01')
        ->assertDontSee('LAV-PROC-01');
});

test('admin can create a product with uploaded image and hover image files', function (): void {
    Storage::fake('public');

    $file = UploadedFile::fake()->create('blazer.jpg', 500, 'image/jpeg');
    $hoverFile = UploadedFile::fake()->create('blazer-back.jpg', 500, 'image/jpeg');

    $response = $this->actingAs($this->admin)
        ->post('/admin/products', [
            'name' => 'Uploaded Silk Blazer',
            'category_id' => $this->category->id,
            'price' => 1750,
            'material' => 'Silk & Linen',
            'sizes' => 'S, M, L',
            'image_file' => $file,
            'hover_image_file' => $hoverFile,
            'description' => 'Test description with uploaded files.',
        ]);

    $response->assertRedirect('/admin/products');
    $product = Product::where('name', 'Uploaded Silk Blazer')->first();
    expect($product)->not->toBeNull();
    expect($product->image)->toStartWith('/storage/products/');
    expect($product->hover_image)->toStartWith('/storage/products/');

    $path = str_replace('/storage/', '', $product->image);
    Storage::disk('public')->assertExists($path);
});

test('admin can update a product image using an uploaded file', function (): void {
    Storage::fake('public');

    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Original Tux',
        'slug' => 'original-tux',
        'price' => 2000,
        'material' => 'Wool',
        'image' => 'https://example.com/old.jpg',
        'sizes' => ['M'],
    ]);

    $file = UploadedFile::fake()->create('new-tux.jpg', 500, 'image/jpeg');

    $response = $this->actingAs($this->admin)
        ->put("/admin/products/{$product->id}", [
            'name' => 'Updated Tux',
            'category_id' => $this->category->id,
            'price' => 2200,
            'material' => 'Wool',
            'sizes' => 'M',
            'image_file' => $file,
            'description' => 'Updated tux description.',
        ]);

    $response->assertRedirect('/admin/products');
    expect($product->fresh()->image)->toStartWith('/storage/products/');
});

test('admin can set product stock status to low in stock and custom quantity', function (): void {
    $response = $this->actingAs($this->admin)
        ->post('/admin/products', [
            'name' => 'Low Stock Tuxedo',
            'category_id' => $this->category->id,
            'price' => 2500,
            'material' => 'Wool',
            'image' => 'https://example.com/tux.jpg',
            'sizes' => 'M',
            'description' => 'Only two pieces left in atelier.',
            'stock_quantity' => 2,
            'stock_status' => 'low_stock',
        ]);

    $response->assertRedirect('/admin/products');
    $product = Product::where('name', 'Low Stock Tuxedo')->first();
    expect($product->stock_quantity)->toBe(2);
    expect($product->stock_status)->toBe('low_stock');
    expect($product->stock_badge['label'])->toBe('Low in Stock');
    expect($product->stock_badge['sublabel'])->toBe('Only 2 left');
});

test('admin can update product to out of stock and zero quantity', function (): void {
    $product = Product::create([
        'category_id' => $this->category->id,
        'name' => 'Sold Out Cashmere Coat',
        'slug' => 'sold-out-cashmere',
        'price' => 3200,
        'material' => 'Cashmere',
        'image' => 'https://example.com/coat.jpg',
        'sizes' => ['L'],
        'stock_quantity' => 5,
        'stock_status' => 'low_stock',
    ]);

    $response = $this->actingAs($this->admin)
        ->put("/admin/products/{$product->id}", [
            'name' => 'Sold Out Cashmere Coat',
            'category_id' => $this->category->id,
            'price' => 3200,
            'material' => 'Cashmere',
            'sizes' => 'L',
            'description' => 'Currently out of stock.',
            'stock_quantity' => 0,
            'stock_status' => 'out_of_stock',
        ]);

    $response->assertRedirect('/admin/products');
    expect($product->fresh()->stock_quantity)->toBe(0);
    expect($product->fresh()->stock_status)->toBe('out_of_stock');
    expect($product->fresh()->in_stock)->toBeFalse();
    expect($product->fresh()->stock_badge['label'])->toBe('Out of Stock');
});
