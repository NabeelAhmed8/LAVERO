<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'LAVÉRO — Haute Couture & Ready-to-Wear')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|inter:300,400,500,600" rel="stylesheet" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.__LAVERO_CART__ = @json(session('cart', []));</script>
    <style>[x-cloak] { display: none !important; }</style>

    <!-- Vite CSS & JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body 
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false 
    }" 
    @scroll.window="scrolled = (window.pageYOffset > 40)"
    class="bg-[#090909] text-[#ededed] font-sans antialiased selection:bg-[#c5a880] selection:text-black overflow-x-hidden min-h-screen flex flex-col"
>
    <!-- 1. TOP ANNOUNCEMENT BAR -->
    <div class="bg-neutral-950 border-b border-white/5 py-2 px-3 text-center text-[9px] sm:text-[10px] md:text-[11px] uppercase tracking-[0.2em] sm:tracking-[0.25em] text-[#c5a880] truncate sm:whitespace-normal">
        <span>Complimentary Express Courier on all orders over $500 &middot; Handcrafted in Italy</span>
    </div>

    <!-- 2. NAVIGATION HEADER -->
    <header 
        :class="scrolled ? 'bg-black/85 backdrop-blur-md border-b border-white/10 py-3 sm:py-4 shadow-2xl' : 'bg-transparent py-4 sm:py-6'"
        class="sticky top-0 z-40 transition-all duration-500 w-full"
    >
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12 flex items-center justify-between">
            <!-- Left: Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8 text-xs uppercase tracking-[0.2em] font-medium text-neutral-300">
                <a href="{{ route('shop.index') }}" class="relative hover:text-white transition-colors duration-300 py-1 group">
                    <span>Collection</span>
                    <span class="absolute bottom-0 left-0 w-0 h-[1px] bg-[#c5a880] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('lookbook') }}" class="relative hover:text-white transition-colors duration-300 py-1 group">
                    <span>Lookbook</span>
                    <span class="absolute bottom-0 left-0 w-0 h-[1px] bg-[#c5a880] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('about') }}" class="relative hover:text-white transition-colors duration-300 py-1 group">
                    <span>Atelier</span>
                    <span class="absolute bottom-0 left-0 w-0 h-[1px] bg-[#c5a880] transition-all duration-300 group-hover:w-full"></span>
                </a>
                <a href="{{ route('contact') }}" class="relative hover:text-white transition-colors duration-300 py-1 group">
                    <span>Concierge</span>
                    <span class="absolute bottom-0 left-0 w-0 h-[1px] bg-[#c5a880] transition-all duration-300 group-hover:w-full"></span>
                </a>
            </nav>

            <!-- Center: Maison Monogram -->
            <div class="text-center">
                <a href="{{ route('home') }}" class="inline-block group">
                    <h1 class="font-serif text-2xl md:text-3xl tracking-[0.32em] font-light text-white uppercase transition-transform duration-500 group-hover:tracking-[0.38em]">
                        LAVÉRO
                    </h1>
                    <span class="block text-[8px] uppercase tracking-[0.45em] text-neutral-400 group-hover:text-[#c5a880] transition-colors">
                        Paris &middot; Milano
                    </span>
                </a>
            </div>

            <!-- Right: Actions -->
            <div class="flex items-center space-x-5">
                <!-- User Authentication Status -->
                @auth
                    <div class="hidden md:flex items-center space-x-3 text-xs tracking-wider">
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="px-2.5 py-1 bg-[#c5a880]/15 text-[#c5a880] border border-[#c5a880]/40 rounded text-[10px] uppercase tracking-widest font-semibold hover:bg-[#c5a880] hover:text-black transition-colors">
                                Atelier CMS
                            </a>
                            <span class="text-neutral-700">|</span>
                        @endif
                        <a href="{{ route('profile') }}" class="text-neutral-400 hover:text-white transition-colors text-xs flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-[#c5a880]"></span>
                            <span>Dossier: <span class="text-white font-medium hover:text-[#c5a880]">{{ Auth::user()->name }}</span></span>
                        </a>
                        <span class="text-neutral-700">|</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-neutral-500 hover:text-red-400 uppercase text-[10px] tracking-widest transition-colors cursor-pointer">
                                Sign Out
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden md:inline-block text-xs uppercase tracking-[0.2em] text-neutral-300 hover:text-[#c5a880] transition-colors py-1">
                        Sign In
                    </a>
                @endauth

                <!-- Cart Button -->
                @php
                    $cartCount = array_reduce(session()->get('cart', []), fn($c, $item) => $c + $item['quantity'], 0);
                @endphp
                <button 
                    @click="$store.cart.open = true" 
                    data-magnetic
                    class="relative p-2 text-white hover:text-[#c5a880] transition-colors cursor-pointer flex items-center"
                    aria-label="Open Shopping Bag"
                >
                    <svg class="w-5 h-5 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span 
                        x-show="$store.cart.count > 0" 
                        x-cloak 
                        x-text="$store.cart.count"
                        class="absolute -top-1 -right-1 w-4 h-4 bg-[#c5a880] text-black text-[10px] font-bold rounded-full flex items-center justify-center"
                    >
                        {{ $cartCount > 0 ? $cartCount : '' }}
                    </span>
                </button>

                <!-- Mobile Menu Button -->
                <button 
                    @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="md:hidden p-2 text-white hover:text-[#c5a880] transition-colors"
                    aria-label="Toggle Navigation"
                >
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- 3. FULLSCREEN MOBILE MENU -->
    <div 
        x-show="mobileMenuOpen" 
        x-cloak 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="fixed inset-0 z-30 bg-[#0c0c0c] flex flex-col justify-between p-8 pt-32 md:hidden"
    >
        <div class="space-y-6">
            <a href="{{ route('shop.index') }}" @click="mobileMenuOpen = false" class="block font-serif text-3xl uppercase tracking-widest text-neutral-200 hover:text-[#c5a880] border-b border-white/5 pb-4">
                Collection
            </a>
            <a href="{{ route('lookbook') }}" @click="mobileMenuOpen = false" class="block font-serif text-3xl uppercase tracking-widest text-neutral-200 hover:text-[#c5a880] border-b border-white/5 pb-4">
                Lookbook
            </a>
            <a href="{{ route('about') }}" @click="mobileMenuOpen = false" class="block font-serif text-3xl uppercase tracking-widest text-neutral-200 hover:text-[#c5a880] border-b border-white/5 pb-4">
                The Atelier
            </a>
            <a href="{{ route('contact') }}" @click="mobileMenuOpen = false" class="block font-serif text-3xl uppercase tracking-widest text-neutral-200 hover:text-[#c5a880] border-b border-white/5 pb-4">
                Private Concierge
            </a>
            @auth
                <div class="pt-2 space-y-3 text-xs text-neutral-400 uppercase tracking-widest">
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" @click="mobileMenuOpen = false" class="block text-[#c5a880] font-semibold">
                            &rarr; Atelier CMS Panel
                        </a>
                    @endif
                    <div class="flex justify-between items-center">
                        <a href="{{ route('profile') }}" @click="mobileMenuOpen = false" class="text-white hover:text-[#c5a880] underline">
                            Dossier: {{ Auth::user()->name }}
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-red-400 hover:underline">Sign Out</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="pt-2 flex space-x-6 text-xs text-neutral-300 uppercase tracking-widest">
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="text-[#c5a880] hover:underline">Sign In</a>
                    <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="hover:underline">Create Account</a>
                </div>
            @endauth
        </div>
        <div class="pt-8 border-t border-white/10 text-xs text-neutral-500 uppercase tracking-widest">
            <p>LAVÉRO Haute Couture &copy; {{ date('Y') }}</p>
            <p class="mt-1">Milan &middot; Paris Ateliers</p>
        </div>
    </div>

    <!-- 4. FLASH NOTIFICATIONS -->
    @if(session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4500)"
            x-transition
            class="fixed bottom-6 right-6 z-50 bg-[#161616] border border-[#c5a880]/40 text-white px-5 py-3.5 rounded shadow-2xl flex items-center space-x-3 text-xs tracking-wider"
        >
            <span class="w-2 h-2 rounded-full bg-[#c5a880]"></span>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-neutral-500 hover:text-white ml-2">&times;</button>
        </div>
    @endif

    <!-- Live AJAX Cart Notification Toast -->
    <div 
        x-show="$store.cart.toast" 
        x-cloak 
        style="display: none;"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-3"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-3"
        class="fixed bottom-6 right-6 z-50 bg-[#141414] border border-[#c5a880]/50 text-white px-5 py-3.5 rounded shadow-2xl flex items-center space-x-3 text-xs tracking-wider"
    >
        <span class="w-2 h-2 rounded-full bg-[#c5a880] animate-pulse"></span>
        <span x-text="$store.cart.toast"></span>
        <button @click="$store.cart.toast = null" class="text-neutral-500 hover:text-white ml-2 cursor-pointer">&times;</button>
    </div>

    <!-- 5. SLIDE-OUT CART DRAWER -->
    <div 
        x-show="$store.cart.open" 
        x-cloak 
        style="display: none;"
        class="fixed inset-0 z-50 overflow-hidden" 
        aria-labelledby="slide-over-title" 
        role="dialog" 
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div 
            x-show="$store.cart.open" 
            x-cloak
            x-transition:enter="ease-in-out duration-500" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="ease-in-out duration-400" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            @click="$store.cart.open = false"
            class="fixed inset-0 bg-black/75 backdrop-blur-sm transition-opacity"
        ></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-0 sm:pl-10">
            <div 
                x-show="$store.cart.open" 
                x-cloak
                x-transition:enter="transform transition ease-in-out duration-500" 
                x-transition:enter-start="translate-x-full" 
                x-transition:enter-end="translate-x-0" 
                x-transition:leave="transform transition ease-in-out duration-400" 
                x-transition:leave-start="translate-x-0" 
                x-transition:leave-end="translate-x-full" 
                class="w-screen max-w-full sm:max-w-md bg-[#0e0e0e] border-l border-white/10 flex flex-col shadow-2xl p-4 sm:p-6"
            >
                <!-- Drawer Header -->
                <div class="flex items-center justify-between pb-5 border-b border-white/10">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5 text-neutral-400 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h2 class="font-serif text-lg tracking-widest uppercase">Shopping Bag (<span x-text="$store.cart.count"></span>)</h2>
                    </div>
                    <button @click="$store.cart.open = false" class="p-2 text-neutral-400 hover:text-white transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Free Shipping Progress -->
                <div class="py-4 border-b border-white/10 text-xs text-neutral-400">
                    <div class="flex justify-between mb-1.5">
                        <span>
                            <template x-if="$store.cart.subtotal >= 500">
                                <span>Complimentary worldwide shipping unlocked</span>
                            </template>
                            <template x-if="$store.cart.subtotal < 500">
                                <span>Add $<span x-text="$store.cart.remainingForFreeShipping"></span> for complimentary shipping</span>
                            </template>
                        </span>
                        <span class="text-white font-medium"><span x-text="Math.round($store.cart.progress)"></span>%</span>
                    </div>
                    <div class="h-1 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-[#c5a880] to-[#ecd9be] transition-all duration-500" :style="`width: ${$store.cart.progress}%;`"></div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="flex-1 overflow-y-auto py-4 space-y-4 pr-1">
                    <template x-for="item in $store.cart.itemsList" :key="item.key">
                        <div class="flex gap-4 p-3 rounded bg-neutral-900/60 border border-white/5">
                            <img :src="item.image" :alt="item.name" class="w-20 h-24 object-cover rounded bg-neutral-950">
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-serif text-sm tracking-wide text-white" x-text="item.name"></h4>
                                        <button 
                                            type="button" 
                                            @click="$store.cart.remove(item.key)" 
                                            class="text-neutral-500 hover:text-red-400 transition-colors p-1 cursor-pointer" 
                                            title="Remove"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="text-xs text-neutral-400 mt-0.5">Size: <span x-text="item.size"></span></p>
                                </div>
                                <div class="flex justify-between items-center mt-3">
                                    <div class="flex items-center border border-white/10 rounded overflow-hidden">
                                        <button 
                                            type="button" 
                                            @click="$store.cart.update(item.key, Number(item.quantity) - 1)" 
                                            class="px-2.5 py-0.5 text-xs text-neutral-400 hover:text-white hover:bg-white/10 transition-colors cursor-pointer select-none"
                                            aria-label="Decrease quantity"
                                        >-</button>
                                        <span class="px-2.5 text-xs text-white font-medium select-none" x-text="item.quantity"></span>
                                        <button 
                                            type="button" 
                                            @click="$store.cart.update(item.key, Number(item.quantity) + 1)" 
                                            class="px-2.5 py-0.5 text-xs text-neutral-400 hover:text-white hover:bg-white/10 transition-colors cursor-pointer select-none"
                                            aria-label="Increase quantity"
                                        >+</button>
                                    </div>
                                    <span class="font-sans text-sm text-[#c5a880] font-medium">
                                        $<span x-text="(Number(item.price) * Number(item.quantity)).toFixed(2)"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Empty Bag State -->
                    <div x-show="$store.cart.count === 0" class="h-full flex flex-col items-center justify-center text-center text-neutral-500 py-16">
                        <svg class="w-12 h-12 mb-3 stroke-1 opacity-40 text-neutral-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="font-serif text-lg text-neutral-300">Your bag is empty</p>
                        <a href="{{ route('shop.index') }}" @click="$store.cart.open = false" class="text-xs uppercase tracking-widest text-[#c5a880] mt-3 hover:underline">
                            Discover Runway Collection &rarr;
                        </a>
                    </div>
                </div>

                <!-- Footer Summary & Checkout -->
                <div x-show="$store.cart.count > 0" class="pt-4 border-t border-white/10 space-y-3">
                    <div class="flex justify-between items-baseline text-sm">
                        <span class="text-neutral-400 uppercase tracking-widest text-xs">Subtotal</span>
                        <span class="font-serif text-xl text-white font-semibold">
                            $<span x-text="$store.cart.formattedSubtotal"></span> USD
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <a 
                            href="{{ route('cart.index') }}" 
                            class="py-3 px-4 border border-white/20 text-neutral-200 text-xs uppercase tracking-widest font-semibold text-center rounded hover:border-white transition-colors"
                        >
                            View Bag
                        </a>
                        <a 
                            href="{{ route('checkout.index') }}" 
                            class="py-3 px-4 bg-white text-black text-xs uppercase tracking-widest font-semibold text-center rounded hover:bg-[#c5a880] hover:text-white transition-colors"
                        >
                            Checkout
                        </a>
                    </div>
                    <p class="text-[10px] text-center text-neutral-500">Taxes, duties & express shipping confirmed at checkout</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 6. PAGE BODY -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- 7. FOOTER -->
    @if(in_array(Route::currentRouteName(), ['login', 'register', 'verify.show']))
        <footer class="py-4 text-center text-[10px] uppercase tracking-[0.25em] text-neutral-600 border-t border-white/5">
            <span>&copy; {{ date('Y') }} LAVÉRO S.A. &middot; Paris &middot; Milano</span>
        </footer>
    @else
    <footer class="bg-[#060606] border-t border-white/10 text-neutral-400 pt-16 sm:pt-20 pb-12 mt-16 sm:mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 pb-16 border-b border-white/10">
                <div class="md:col-span-2 space-y-4">
                    <h2 class="font-serif text-3xl md:text-4xl text-white tracking-[0.2em] font-light">
                        LAVÉRO
                    </h2>
                    <p class="text-sm text-neutral-400 max-w-md leading-relaxed font-light">
                        An architectural exploration of contemporary silhouette and bespoke tailoring. Made in limited quantities between our Milanese and Parisian ateliers.
                    </p>
                    <div 
                        x-data="{ 
                            email: '', 
                            loading: false, 
                            subscribed: false, 
                            message: '' 
                        }" 
                        class="pt-2"
                    >
                        <span class="text-[11px] uppercase tracking-[0.25em] text-[#c5a880] block font-medium">
                            Private Salon Showings &amp; Exclusive Drops
                        </span>

                        <!-- Dynamic Success Notification -->
                        <div 
                            x-show="subscribed" 
                            x-cloak 
                            x-transition
                            class="mt-3 py-2 flex items-center space-x-2 text-xs text-[#c5a880] tracking-wide"
                        >
                            <svg class="w-4 h-4 text-[#c5a880]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="message"></span>
                        </div>

                        <!-- Dynamic Form -->
                        <form 
                            x-show="!subscribed"
                            @submit.prevent="
                                if (!email) return;
                                loading = true;
                                fetch('{{ route('newsletter.subscribe') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ email: email })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    loading = false;
                                    subscribed = true;
                                    message = data.message || 'Invitation confirmed.';
                                })
                                .catch(() => {
                                    loading = false;
                                    subscribed = true;
                                    message = 'Invitation confirmed.';
                                })
                            " 
                            class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center max-w-md gap-3 sm:gap-0 border-b border-white/20 focus-within:border-[#c5a880] pb-2 transition-colors duration-300"
                        >
                            <label for="newsletter-email" class="sr-only">Email address for private invitations</label>
                            <input 
                                type="email" 
                                id="newsletter-email"
                                name="email"
                                x-model="email"
                                required
                                autocomplete="email"
                                placeholder="client@private-domain.com" 
                                class="bg-transparent text-sm w-full outline-none text-white placeholder-neutral-600 tracking-wide py-1"
                            >
                            <button 
                                type="submit" 
                                :disabled="loading"
                                data-magnetic
                                class="text-xs uppercase tracking-[0.2em] font-semibold text-neutral-300 hover:text-[#c5a880] transition-colors py-1.5 sm:py-0 px-2 sm:px-0 self-start sm:self-auto cursor-pointer disabled:opacity-50"
                            >
                                <span x-show="!loading">Request Invitation</span>
                                <span x-show="loading" x-cloak>Transmitting...</span>
                            </button>
                        </form>
                        <p class="text-[10px] text-neutral-500 mt-2 font-light tracking-wide">
                            Subscribers receive private salon invitations before public runway drops.
                        </p>
                    </div>
                </div>

                <div>
                    <h4 class="font-serif text-sm uppercase tracking-widest text-white mb-4">Maison</h4>
                    <ul class="space-y-2.5 text-xs uppercase tracking-wider">
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">The Atelier</a></li>
                        <li><a href="{{ route('lookbook') }}" class="hover:text-white transition-colors">Runway Archives</a></li>
                        <li><a href="{{ route('about') }}#sustainability" class="hover:text-white transition-colors">Traceability &amp; Craft</a></li>
                        <li><a href="{{ route('shop.index') }}" class="hover:text-white transition-colors">All Creations</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-serif text-sm uppercase tracking-widest text-white mb-4">Concierge</h4>
                    <ul class="space-y-2.5 text-xs uppercase tracking-wider">
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Private Client Care</a></li>
                        <li><a href="{{ route('contact') }}#fittings" class="hover:text-white transition-colors">Book Atelier Fitting</a></li>
                        <li><a href="{{ route('cart.index') }}" class="hover:text-white transition-colors">Global Delivery &amp; Returns</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Paris &middot; Milan Salons</a></li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 flex flex-col md:flex-row justify-between items-center text-[11px] text-neutral-600 uppercase tracking-widest gap-4 text-center md:text-left">
                <p>&copy; {{ date('Y') }} LAVÉRO S.A. All rights reserved.</p>
                <div class="flex flex-wrap justify-center gap-4 sm:gap-6">
                    <a href="{{ route('privacy') }}" class="hover:text-neutral-400">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-neutral-400">Terms of Service</a>
                    <a href="{{ route('legal') }}" class="hover:text-neutral-400">Legal Notice</a>
                </div>
            </div>
        </div>
    </footer>
    @endif
</body>
</html>
