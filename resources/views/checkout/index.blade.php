@extends('layouts.app')

@section('title', 'Secure Checkout — LAVÉRO')

@section('content')
<div class="pt-28 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-12 pb-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-end justify-between gap-4" data-motion="fade-up">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2 font-medium">Bespoke Concierge Dispatch</span>
            <h1 class="font-serif text-3xl md:text-5xl text-white font-light tracking-wide uppercase">
                Order Checkout
            </h1>
        </div>
        <a href="{{ route('cart.index') }}" class="text-xs uppercase tracking-widest text-neutral-400 hover:text-white">
            &larr; Back to Shopping Bag
        </a>
    </div>

    <form action="{{ route('checkout.process') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-12" x-data="{ paymentMethod: 'cod' }">
        @csrf

        <!-- Left Column: Shipping & Payment -->
        <div class="lg:col-span-7 space-y-10" data-motion="fade-up">
            <!-- 1. Contact Info -->
            <div class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-white/10">
                    <span class="w-6 h-6 rounded-full bg-[#c5a880] text-black text-xs font-bold flex items-center justify-center">1</span>
                    <h3 class="font-serif text-lg text-white tracking-widest uppercase">Contact Information</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">First Name *</label>
                        <input type="text" id="first_name" name="first_name" autocomplete="given-name" value="{{ old('first_name', auth()->user() ? explode(' ', auth()->user()->name)[0] : '') }}" required placeholder="Alexander" class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880]">
                    </div>
                    <div>
                        <label for="last_name" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Last Name *</label>
                        <input type="text" id="last_name" name="last_name" autocomplete="family-name" value="{{ old('last_name', auth()->user() ? (explode(' ', auth()->user()->name, 2)[1] ?? '') : '') }}" required placeholder="Vanderbilt" class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880]">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Email Address for Dispatch Tracking *</label>
                    <input type="email" id="email" name="email" autocomplete="email" value="{{ old('email', auth()->user()?->email) }}" required placeholder="client@private.com" class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880]">
                </div>
            </div>

            <!-- 2. White Glove Delivery Address -->
            <div class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-white/10">
                    <span class="w-6 h-6 rounded-full bg-[#c5a880] text-black text-xs font-bold flex items-center justify-center">2</span>
                    <h3 class="font-serif text-lg text-white tracking-widest uppercase">Delivery Destination</h3>
                </div>

                <div>
                    <label for="address" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Street Address *</label>
                    <input type="text" id="address" name="address" autocomplete="street-address" value="{{ old('address', auth()->user()?->address) }}" required placeholder="742 Avenue Montaigne, Apt 4B" class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="city" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">City *</label>
                        <input type="text" id="city" name="city" autocomplete="address-level2" value="{{ old('city', auth()->user()?->city) }}" required placeholder="Paris" class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880]">
                    </div>
                    <div>
                        <label for="country" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Country *</label>
                        <input type="text" id="country" name="country" autocomplete="country-name" value="{{ old('country', auth()->user()?->country) }}" required placeholder="France" class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880]">
                    </div>
                    <div>
                        <label for="postal_code" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Postal Code *</label>
                        <input type="text" id="postal_code" name="postal_code" autocomplete="postal-code" value="{{ old('postal_code', auth()->user()?->postal_code) }}" required placeholder="75008" class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880]">
                    </div>
                </div>
            </div>

            <!-- 3. Payment Method -->
            <div class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-6">
                <div class="flex items-center space-x-3 pb-4 border-b border-white/10">
                    <span class="w-6 h-6 rounded-full bg-[#c5a880] text-black text-xs font-bold flex items-center justify-center">3</span>
                    <h3 class="font-serif text-lg text-white tracking-widest uppercase">Payment Method</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Cash on Delivery -->
                    <label 
                        for="payment_method_cod"
                        @click="paymentMethod = 'cod'"
                        :class="paymentMethod === 'cod' ? 'border-[#c5a880] bg-[#c5a880]/10 text-white' : 'border-white/10 bg-neutral-900 text-neutral-400'"
                        class="p-5 rounded border cursor-pointer flex flex-col justify-between transition-colors relative"
                    >
                        <input type="radio" id="payment_method_cod" name="payment_method" value="cod" :checked="paymentMethod === 'cod'" class="hidden">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs uppercase tracking-widest font-semibold text-white">Cash on Delivery</span>
                            <span class="w-2.5 h-2.5 rounded-full" :class="paymentMethod === 'cod' ? 'bg-[#c5a880]' : 'bg-neutral-700'"></span>
                        </div>
                        <span class="text-[11px] text-neutral-400">Pay cash upon courier arrival at doorstep</span>
                    </label>

                    <!-- Stripe -->
                    <label 
                        for="payment_method_stripe"
                        @click="paymentMethod = 'stripe'"
                        :class="paymentMethod === 'stripe' ? 'border-[#c5a880] bg-[#c5a880]/10 text-white' : 'border-white/10 bg-neutral-900 text-neutral-400'"
                        class="p-5 rounded border cursor-pointer flex flex-col justify-between transition-colors relative"
                    >
                        <input type="radio" id="payment_method_stripe" name="payment_method" value="stripe" :checked="paymentMethod === 'stripe'" class="hidden">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs uppercase tracking-widest font-semibold text-white">Stripe</span>
                            <span class="w-2.5 h-2.5 rounded-full" :class="paymentMethod === 'stripe' ? 'bg-[#c5a880]' : 'bg-neutral-700'"></span>
                        </div>
                        <span class="text-[11px] text-neutral-400">Cards, Apple Pay, Google Pay via Stripe</span>
                    </label>
                </div>

                <!-- Cash on Delivery Details -->
                <div x-show="paymentMethod === 'cod'" class="p-5 rounded bg-neutral-900 border border-white/5 text-xs text-neutral-400 space-y-2">
                    <p class="text-white font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#c5a880]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cash on Delivery (COD) Protocol
                    </p>
                    <p class="leading-relaxed">Your order will be dispatched via white-glove courier. You may inspect the package and pay the exact order amount in cash upon delivery.</p>
                </div>

                <!-- Stripe Hosted Checkout Info -->
                <div x-show="paymentMethod === 'stripe'" x-cloak class="p-5 rounded bg-neutral-900 border border-white/5 text-xs text-neutral-400 space-y-3">
                    <div class="flex items-center justify-between pb-2 border-b border-white/5">
                        <span class="text-[11px] uppercase tracking-wider text-neutral-300 font-medium">Official Stripe Gateway</span>
                        <span class="text-[10px] text-[#c5a880] font-semibold tracking-widest uppercase">256-bit Encrypted</span>
                    </div>
                    <p class="leading-relaxed">Upon clicking proceed, you will be directed to Stripe's secure checkout page to finalize payment with Credit/Debit Card, Apple Pay, Google Pay, or Link.</p>
                </div>
            </div>

            <!-- Submit -->
            <button 
                type="submit" 
                data-magnetic
                class="w-full py-4 px-6 bg-white hover:bg-[#c5a880] text-black hover:text-white rounded text-xs uppercase tracking-[0.25em] font-semibold transition-all duration-300 shadow-2xl cursor-pointer"
            >
                <span x-show="paymentMethod === 'cod'">Place Order (Cash on Delivery) &middot; ${{ number_format($total, 2) }} USD</span>
                <span x-show="paymentMethod === 'stripe'" x-cloak>Proceed to Stripe Checkout &middot; ${{ number_format($total, 2) }} USD &rarr;</span>
            </button>
        </div>

        <!-- Right Column: Order Summary -->
        <div class="lg:col-span-5" data-motion="fade-up">
            <div class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-6 sticky top-28">
                <h3 class="font-serif text-xl text-white font-light uppercase tracking-widest border-b border-white/10 pb-4">
                    Your Selection ({{ count($cart) }})
                </h3>

                <div class="space-y-4 max-h-80 overflow-y-auto pr-1">
                    @foreach($cart as $item)
                        <div class="flex gap-4 items-center">
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-16 h-20 object-cover rounded bg-neutral-900">
                            <div class="flex-1">
                                <h4 class="font-serif text-sm text-white">{{ $item['name'] }}</h4>
                                <p class="text-xs text-neutral-400 mt-0.5">Size: {{ $item['size'] }} &middot; Qty: {{ $item['quantity'] }}</p>
                            </div>
                            <span class="text-sm text-neutral-200 font-medium">
                                ${{ number_format($item['price'] * $item['quantity'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-white/10 space-y-2.5 text-xs">
                    <div class="flex justify-between text-neutral-400">
                        <span>Garments Subtotal</span>
                        <span class="text-white">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-neutral-400">
                        <span>Courier Express</span>
                        <span class="text-white">
                            @if($shipping == 0)
                                <span class="text-[#c5a880] uppercase tracking-wider font-semibold">Complimentary</span>
                            @else
                                ${{ number_format($shipping, 2) }}
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-neutral-400">
                        <span>Customs &amp; VAT</span>
                        <span class="text-white">Included</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-between items-baseline">
                    <span class="text-xs uppercase tracking-widest text-neutral-300">Total Due</span>
                    <span class="font-serif text-2xl text-white font-semibold">
                        ${{ number_format($total, 2) }} USD
                    </span>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
