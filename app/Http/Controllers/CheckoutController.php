<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index')->with('info', 'Your shopping bag is empty.');
        }

        $subtotal = array_reduce($cart, fn (float $carry, array $item): float => $carry + ($item['price'] * $item['quantity']), 0.0);
        $shipping = $subtotal >= 500 ? 0.0 : 45.0;
        $total = $subtotal + $shipping;

        return view('checkout.index', [
            'cart' => $cart,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);
    }

    public function process(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('shop.index');
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'payment_method' => 'nullable|string|in:cod,stripe',
        ]);

        $orderNumber = 'LAV-'.strtoupper(bin2hex(random_bytes(4)));
        $subtotal = array_reduce($cart, fn (float $carry, array $item): float => $carry + ($item['price'] * $item['quantity']), 0.0);
        $shipping = $subtotal >= 500 ? 0.0 : 45.0;
        $paymentType = $request->input('payment_method', 'cod');

        if ($paymentType === 'stripe') {
            $secret = config('services.stripe.secret');

            if ($secret) {
                Stripe::setApiKey($secret);

                $lineItems = [];
                foreach ($cart as $item) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $item['name'].' (Size '.($item['size'] ?? 'Standard').')',
                            ],
                            'unit_amount' => (int) round($item['price'] * 100),
                        ],
                        'quantity' => (int) $item['quantity'],
                    ];
                }

                if ($shipping > 0) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'White Glove Express Courier Dispatch',
                            ],
                            'unit_amount' => (int) round($shipping * 100),
                        ],
                        'quantity' => 1,
                    ];
                }

                $session = StripeSession::create([
                    'line_items' => $lineItems,
                    'mode' => 'payment',
                    'customer_email' => $validated['email'],
                    'success_url' => route('checkout.stripe.success').'?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('checkout.stripe.cancel'),
                    'metadata' => [
                        'order_number' => $orderNumber,
                        'customer_name' => $validated['first_name'].' '.$validated['last_name'],
                    ],
                ]);

                $this->createOrderRecord($validated, $cart, $subtotal, $shipping, $orderNumber, 'Stripe (Credit / Debit)', 'pending');

                session()->put('stripe_pending_order', [
                    'order_number' => $orderNumber,
                    'customer_name' => $validated['first_name'].' '.$validated['last_name'],
                    'email' => $validated['email'],
                    'payment_method' => 'Stripe (Credit / Debit)',
                    'items' => $cart,
                    'subtotal' => $subtotal,
                    'date' => now()->format('F j, Y'),
                ]);

                return redirect()->away($session->url);
            }
        }

        // Cash on delivery fallback / default
        $this->createOrderRecord($validated, $cart, $subtotal, $shipping, $orderNumber, 'Cash on Delivery', 'pending');

        session()->put('last_order', [
            'order_number' => $orderNumber,
            'customer_name' => $validated['first_name'].' '.$validated['last_name'],
            'email' => $validated['email'],
            'payment_method' => 'Cash on Delivery',
            'items' => $cart,
            'subtotal' => $subtotal,
            'date' => now()->format('F j, Y'),
        ]);

        session()->forget('cart');

        return redirect()->route('checkout.success');
    }

    public function stripeSuccess(Request $request): RedirectResponse
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('checkout.index');
        }

        $secret = config('services.stripe.secret');
        if ($secret) {
            Stripe::setApiKey($secret);
            try {
                $session = StripeSession::retrieve($sessionId);
                if ($session->payment_status === 'paid' || $session->status === 'complete') {
                    $orderNumber = $session->metadata->order_number ?? null;
                    if ($orderNumber) {
                        Order::where('order_number', $orderNumber)->update(['payment_status' => 'paid']);
                    }

                    $pending = session()->get('stripe_pending_order');
                    if ($pending) {
                        session()->put('last_order', $pending);
                        session()->forget('stripe_pending_order');
                        session()->forget('cart');
                    }

                    return redirect()->route('checkout.success');
                }
            } catch (\Exception $e) {
                // If retrieval fails, redirect back
            }
        }

        return redirect()->route('checkout.index')->with('error', 'Stripe payment could not be confirmed.');
    }

    private function createOrderRecord(array $validated, array $cart, float $subtotal, float $shipping, string $orderNumber, string $paymentMethod, string $paymentStatus): Order
    {
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'customer_name' => $validated['first_name'].' '.$validated['last_name'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'postal_code' => $validated['postal_code'],
            'payment_method' => $paymentMethod,
            'payment_status' => $paymentStatus,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $subtotal + $shipping,
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['name'],
                'price' => $item['price'],
                'size' => $item['size'] ?? 'M',
                'quantity' => $item['quantity'],
                'image' => $item['image'] ?? null,
            ]);
        }

        return $order;
    }

    public function stripeCancel(): RedirectResponse
    {
        session()->forget('stripe_pending_order');

        return redirect()->route('checkout.index')->with('info', 'Stripe checkout cancelled.');
    }

    public function success(): View|RedirectResponse
    {
        $order = session()->get('last_order');

        if (! $order) {
            return redirect()->route('home');
        }

        return view('checkout.success', [
            'order' => $order,
        ]);
    }
}
