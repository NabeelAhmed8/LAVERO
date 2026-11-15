<?php

use App\Models\Order;

test('checkout redirects to shop when cart is empty', function () {
    $response = $this->get('/checkout');

    $response->assertRedirect('/shop');
});

test('checkout renders properly when cart has items', function () {
    $response = $this->withSession([
        'cart' => [
            1 => [
                'name' => 'Signature Trench',
                'price' => 1500,
                'image' => 'https://example.com/trench.jpg',
                'size' => 'M',
                'quantity' => 1,
            ],
        ],
    ])->get('/checkout');

    $response->assertStatus(200);
    $response->assertSee('Cash on Delivery');
    $response->assertSee('Stripe');
});

test('order can be processed with cash on delivery', function () {
    $response = $this->withSession([
        'cart' => [
            1 => [
                'name' => 'Signature Trench',
                'price' => 1500,
                'image' => 'https://example.com/trench.jpg',
                'size' => 'M',
                'quantity' => 1,
            ],
        ],
    ])->post('/checkout/process', [
        'first_name' => 'Alexander',
        'last_name' => 'Vanderbilt',
        'email' => 'client@private.com',
        'address' => '742 Avenue Montaigne',
        'city' => 'Paris',
        'country' => 'France',
        'postal_code' => '75008',
        'payment_method' => 'cod',
    ]);

    $response->assertRedirect(route('checkout.success'));
    expect(session('last_order')['payment_method'])->toBe('Cash on Delivery');
    expect(session('cart'))->toBeNull();
    expect(Order::where('email', 'client@private.com')->exists())->toBeTrue();
    $order = Order::where('email', 'client@private.com')->first();
    expect($order->items()->count())->toBe(1);
    expect($order->items()->first()->product_name)->toBe('Signature Trench');
});

test('order redirects away to stripe hosted checkout url when stripe selected', function () {
    $response = $this->withSession([
        'cart' => [
            1 => [
                'name' => 'Silk Noir Gown',
                'price' => 2400,
                'image' => 'https://example.com/gown.jpg',
                'size' => '38',
                'quantity' => 1,
            ],
        ],
    ])->post('/checkout/process', [
        'first_name' => 'Elena',
        'last_name' => 'Rostova',
        'email' => 'elena@milan.com',
        'address' => '12 Via Montenapoleone',
        'city' => 'Milan',
        'country' => 'Italy',
        'postal_code' => '20121',
        'payment_method' => 'stripe',
    ]);

    $response->assertStatus(302);
    expect(str_contains($response->headers->get('Location'), 'checkout.stripe.com'))->toBeTrue();
    expect(session('stripe_pending_order')['payment_method'])->toBe('Stripe (Credit / Debit)');
});

test('stripe cancel clears pending order and redirects to checkout index', function () {
    $response = $this->withSession([
        'stripe_pending_order' => ['order_number' => 'LAV-TEST1234'],
    ])->get('/checkout/stripe-cancel');

    $response->assertRedirect(route('checkout.index'));
    expect(session('stripe_pending_order'))->toBeNull();
});
