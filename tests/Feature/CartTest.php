<?php

use App\Models\Category;
use App\Models\Product;

beforeEach(function (): void {
    $category = Category::create(['name' => 'Suits', 'slug' => 'suits']);
    $this->product = Product::create([
        'category_id' => $category->id,
        'name' => 'Structured Tuxedo',
        'slug' => 'structured-tuxedo',
        'price' => 2200,
        'material' => 'Barathea Wool',
        'image' => 'https://example.com/tux.jpg',
        'sizes' => ['48', '50', '52'],
    ]);
});

test('it renders the cart page', function (): void {
    $this->get('/cart')->assertStatus(200);
});

test('it adds an item to the shopping bag', function (): void {
    $response = $this->post('/cart/add', [
        'product_id' => $this->product->id,
        'size' => '50',
        'quantity' => 1,
    ]);

    $response->assertRedirect();
    $cart = session('cart');
    expect($cart)->toHaveKey($this->product->id.'-50');
    expect($cart[$this->product->id.'-50']['quantity'])->toBe(1);
});

test('it increments quantity when same item is added again', function (): void {
    $this->post('/cart/add', [
        'product_id' => $this->product->id,
        'size' => '50',
        'quantity' => 1,
    ]);

    $this->post('/cart/add', [
        'product_id' => $this->product->id,
        'size' => '50',
        'quantity' => 2,
    ]);

    $cart = session('cart');
    expect($cart[$this->product->id.'-50']['quantity'])->toBe(3);
});

test('it updates item quantity in bag', function (): void {
    $this->withSession([
        'cart' => [
            $this->product->id.'-50' => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => 2200,
                'size' => '50',
                'quantity' => 1,
            ],
        ],
    ])->post('/cart/update', [
        'item_key' => $this->product->id.'-50',
        'quantity' => 4,
    ]);

    expect(session('cart')[$this->product->id.'-50']['quantity'])->toBe(4);
});

test('it removes an item from the shopping bag', function (): void {
    $this->withSession([
        'cart' => [
            $this->product->id.'-50' => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => 2200,
                'size' => '50',
                'quantity' => 1,
            ],
        ],
    ])->post('/cart/remove', [
        'item_key' => $this->product->id.'-50',
    ]);

    expect(session('cart'))->not->toHaveKey($this->product->id.'-50');
});

test('it clears the shopping bag', function (): void {
    $this->withSession([
        'cart' => [
            'item-1' => ['quantity' => 1],
            'item-2' => ['quantity' => 2],
        ],
    ])->post('/cart/clear');

    expect(session('cart'))->toBeEmpty();
});

test('it adds an item via json request and returns json payload', function (): void {
    $response = $this->postJson('/cart/add', [
        'product_id' => $this->product->id,
        'size' => '50',
        'quantity' => 2,
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'cart_count' => 2,
            'subtotal' => 4400,
        ]);
});

test('it removes an item via json request and returns updated cart json', function (): void {
    $this->withSession([
        'cart' => [
            $this->product->id.'-50' => [
                'product_id' => $this->product->id,
                'name' => $this->product->name,
                'price' => 2200,
                'size' => '50',
                'quantity' => 1,
            ],
        ],
    ]);

    $response = $this->postJson('/cart/remove', [
        'item_key' => $this->product->id.'-50',
    ]);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'cart_count' => 0,
            'subtotal' => 0,
        ]);
});
