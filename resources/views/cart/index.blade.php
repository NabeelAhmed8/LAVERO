@extends('layouts.app')

@section('title', 'Shopping Bag — LAVÉRO')

@section('content')
<div class="pt-28 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto">
    <div class="mb-12 pb-6 border-b border-white/10 flex flex-col sm:flex-row sm:items-end justify-between gap-4" data-motion="fade-up">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2 font-medium">Your Selection</span>
            <h1 class="font-serif text-3xl md:text-5xl text-white font-light tracking-wide uppercase">
                Shopping Bag (<span x-text="$store.cart.count"></span>)
            </h1>
        </div>
        <a href="{{ route('shop.index') }}" class="text-xs uppercase tracking-widest text-[#c5a880] hover:underline">
            &larr; Continue Exploring
        </a>
    </div>

    <!-- Empty Bag State -->
    <div x-show="$store.cart.count === 0" x-cloak class="text-center py-28 border border-white/5 rounded bg-neutral-950" data-motion="fade-up">
        <svg class="w-16 h-16 mx-auto mb-4 stroke-1 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
        </svg>
        <h2 class="font-serif text-3xl text-neutral-300 font-light">Your shopping bag is empty</h2>
        <p class="text-xs uppercase tracking-widest text-neutral-500 mt-2">
            Discover pieces from the Runway Autumn / Winter Capsule
        </p>
        <div class="mt-8">
            <a 
                href="{{ route('shop.index') }}" 
                data-magnetic
                class="inline-block px-8 py-4 bg-white text-black text-xs uppercase tracking-[0.25em] font-semibold rounded hover:bg-[#c5a880] hover:text-white transition-colors"
            >
                View Runway Catalog
            </a>
        </div>
    </div>

    <!-- Active Cart Layout -->
    <div x-show="$store.cart.count > 0" class="grid grid-cols-1 lg:grid-cols-12 gap-12">
        <!-- Left: Items list -->
        <div class="lg:col-span-8 space-y-6">
            <template x-for="item in $store.cart.itemsList" :key="item.key">
                <div class="flex flex-col sm:flex-row gap-6 p-4 sm:p-6 rounded bg-neutral-950 border border-white/5 items-start sm:items-center justify-between">
                    <div class="flex gap-4 sm:gap-5 items-center">
                        <img :src="item.image" :alt="item.name" class="w-20 h-28 sm:w-24 sm:h-32 object-cover rounded bg-neutral-900 shrink-0">
                        <div>
                            <a :href="`/shop/${item.slug}`">
                                <h3 class="font-serif text-base sm:text-lg text-white hover:text-[#c5a880] transition-colors" x-text="item.name"></h3>
                            </a>
                            <p class="text-xs text-neutral-400 mt-1 uppercase tracking-wider">
                                Size: <span class="text-white" x-text="item.size"></span> &middot; $<span x-text="Number(item.price).toFixed(2)"></span> each
                            </p>
                            <button 
                                type="button" 
                                @click="$store.cart.remove(item.key)" 
                                class="mt-3 text-[11px] uppercase tracking-wider text-neutral-500 hover:text-red-400 transition-colors cursor-pointer"
                            >
                                Remove from bag
                            </button>
                        </div>
                    </div>

                    <!-- Quantity Stepper & Price -->
                    <div class="flex items-center justify-between w-full sm:w-auto sm:space-x-8 sm:self-center pt-3 sm:pt-0 border-t border-white/5 sm:border-t-0">
                        <div class="flex items-center border border-white/10 rounded">
                            <button 
                                type="button" 
                                @click="$store.cart.update(item.key, Number(item.quantity) - 1)" 
                                class="px-3 py-1 text-xs text-neutral-400 hover:text-white cursor-pointer select-none"
                                aria-label="Decrease quantity"
                            >-</button>
                            <span class="px-3 text-xs text-white font-medium select-none" x-text="item.quantity"></span>
                            <button 
                                type="button" 
                                @click="$store.cart.update(item.key, Number(item.quantity) + 1)" 
                                class="px-3 py-1 text-xs text-neutral-400 hover:text-white cursor-pointer select-none"
                                aria-label="Increase quantity"
                            >+</button>
                        </div>
                        <span class="font-sans text-base text-[#c5a880] font-medium min-w-24 text-right">
                            $<span x-text="(Number(item.price) * Number(item.quantity)).toFixed(2)"></span>
                        </span>
                    </div>
                </div>
            </template>
        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-4">
            <div class="p-5 sm:p-8 rounded bg-neutral-950 border border-white/10 space-y-6 sticky top-28">
                <h3 class="font-serif text-xl text-white font-light uppercase tracking-widest border-b border-white/10 pb-4">
                    Order Breakdown
                </h3>

                <div class="space-y-3 text-xs">
                    <div class="flex justify-between text-neutral-400">
                        <span>Subtotal</span>
                        <span class="text-white">$<span x-text="$store.cart.formattedSubtotal"></span></span>
                    </div>
                    <div class="flex justify-between text-neutral-400">
                        <span>Express Global Courier</span>
                        <span class="text-white">
                            <template x-if="$store.cart.subtotal >= 500">
                                <span class="text-[#c5a880] uppercase tracking-wider font-semibold">Complimentary</span>
                            </template>
                            <template x-if="$store.cart.subtotal < 500">
                                <span>$45.00</span>
                            </template>
                        </span>
                    </div>
                    <div class="flex justify-between text-neutral-400">
                        <span>Duties &amp; Import Customs</span>
                        <span class="text-white">Included</span>
                    </div>
                </div>

                <div class="pt-4 border-t border-white/10 flex justify-between items-baseline">
                    <span class="text-xs uppercase tracking-widest text-neutral-300">Estimated Total</span>
                    <span class="font-serif text-2xl text-white font-semibold">
                        $<span x-text="$store.cart.finalTotal"></span> USD
                    </span>
                </div>

                <a 
                    href="{{ route('checkout.index') }}" 
                    data-magnetic
                    class="block w-full py-4 bg-white text-black text-center text-xs uppercase tracking-[0.25em] font-semibold rounded hover:bg-[#c5a880] hover:text-white transition-colors"
                >
                    Proceed to Checkout
                </a>

                <div class="text-[11px] text-neutral-500 space-y-1 text-center">
                    <p>&bull; 30-Day Complimentary Exchanges</p>
                    <p>&bull; Signature Wooden Box &amp; Dust Bag</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
