<?php

use App\Models\Category;
use App\Models\Product;

test('it renders the homepage with products and categories', function () {
    $category = Category::create(['name' => 'Runway Coats', 'slug' => 'runway-coats']);
    Product::create([
        'category_id' => $category->id,
        'name' => 'Signature Trench',
        'slug' => 'signature-trench',
        'price' => 1500,
        'material' => 'Double-Faced Wool',
        'image' => 'https://example.com/img.jpg',
        'sizes' => ['S', 'M'],
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
});

test('it renders the shop catalog page', function () {
    $response = $this->get('/shop');

    $response->assertStatus(200);
});

test('it renders the product detail page for valid slug', function () {
    $category = Category::create(['name' => 'Evening', 'slug' => 'evening']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Silk Noir Gown',
        'slug' => 'silk-noir-gown',
        'price' => 2400,
        'material' => 'Mulberry Silk',
        'image' => 'https://example.com/gown.jpg',
        'sizes' => ['36', '38'],
    ]);

    $response = $this->get('/product/'.$product->slug);

    $response->assertStatus(200);
});

test('it returns 404 for non-existent product', function () {
    $response = $this->get('/product/non-existent-garment');

    $response->assertStatus(404);
});

test('it renders editorial pages', function () {
    $this->get('/lookbook')->assertStatus(200);
    $this->get('/about')->assertStatus(200);
    $this->get('/contact')->assertStatus(200);
    $this->get('/cart')->assertStatus(200);
});

test('it can add product to cart and checkout', function () {
    $category = Category::create(['name' => 'Tailoring', 'slug' => 'tailoring']);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Atelier Suit',
        'slug' => 'atelier-suit',
        'price' => 1200,
        'image' => 'https://example.com/suit.jpg',
        'sizes' => ['M', 'L'],
    ]);

    $addResponse = $this->post('/cart/add', [
        'product_id' => $product->id,
        'size' => 'M',
        'quantity' => 1,
    ]);

    $addResponse->assertSessionHas('cart');

    $checkoutResponse = $this->get('/checkout');
    $checkoutResponse->assertStatus(200);
});

test('it subscribes client to newsletter via json request', function () {
    $response = $this->postJson('/newsletter', [
        'email' => 'client.vip@lavero.com',
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
        ]);

    $this->assertDatabaseHas('newsletter_subscribers', [
        'email' => 'client.vip@lavero.com',
    ]);
});
