@extends('layouts.app')

@section('title', 'LAVÉRO — Haute Couture & Ready-to-Wear')

@section('content')
<!-- 1. EDITORIAL HERO -->
<section class="relative min-h-[92vh] flex items-end justify-start pb-16 sm:pb-20 pt-28 sm:pt-36 px-4 sm:px-6 md:px-16 overflow-hidden">
    <!-- Background Image -->
    @php
        $heroImage = $products->first()?->image ?? 'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=2400&q=85';
    @endphp
    <div 
        class="absolute inset-0 z-0 bg-cover bg-center transition-transform duration-1000 scale-100 hover:scale-105"
        style="background-image: url('{{ $heroImage }}');"
    >
        <div class="absolute inset-0 bg-gradient-to-t from-[#090909] via-[#090909]/50 to-[#090909]/75"></div>
    </div>

    <!-- Hero Content -->
    <div class="relative z-10 max-w-4xl" data-motion="fade-up">
        <div class="inline-flex items-center space-x-2 text-xs uppercase tracking-[0.35em] text-[#c5a880] mb-4">
            <span class="w-1.5 h-1.5 rounded-full bg-[#c5a880]"></span>
            <span>Autumn / Winter 2026 Collection</span>
        </div>

        <h1 class="font-serif text-4xl sm:text-6xl md:text-8xl tracking-tight text-white font-light leading-[0.95] uppercase">
            The Architecture of Silhouette
        </h1>

        <div class="mt-8 flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <a 
                href="#collection" 
                data-magnetic
                class="px-8 py-4 bg-white text-black text-xs uppercase tracking-[0.25em] font-semibold rounded hover:bg-[#c5a880] hover:text-white transition-colors duration-300"
            >
                Explore Collection
            </a>

            <a 
                href="{{ route('lookbook') }}" 
                class="group flex items-center space-x-3 text-xs uppercase tracking-[0.2em] text-neutral-300 hover:text-white py-3"
            >
                <span>View Runway Editorial</span>
                <span class="group-hover:translate-x-1.5 transition-transform duration-300">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 right-8 md:right-16 hidden md:flex items-center space-x-2 text-xs uppercase tracking-widest text-neutral-400">
        <span>Scroll Down</span>
        <svg class="w-3.5 h-3.5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
        </svg>
    </div>
</section>

<!-- 2. INFINITE MARQUEE TICKER -->
<div class="relative py-6 bg-neutral-950 border-y border-white/5 overflow-hidden select-none">
    <div class="flex whitespace-nowrap text-xs md:text-sm uppercase tracking-[0.35em] text-neutral-500 font-medium animate-pulse">
        <span class="mx-6">LAVÉRO HAUTE COUTURE</span> &middot;
        <span class="mx-6">MILANO ATELIER</span> &middot;
        <span class="mx-6">BESPOKE VIRGIN WOOL</span> &middot;
        <span class="mx-6">PARIS FASHION WEEK</span> &middot;
        <span class="mx-6">LIMITED ARCHIVAL PIECES</span> &middot;
        <span class="mx-6">COMPLIMENTARY WORLDWIDE DELIVERY</span> &middot;
        <span class="mx-6">LAVÉRO HAUTE COUTURE</span> &middot;
        <span class="mx-6">MILANO ATELIER</span> &middot;
        <span class="mx-6">BESPOKE VIRGIN WOOL</span> &middot;
        <span class="mx-6">PARIS FASHION WEEK</span> &middot;
        <span class="mx-6">LIMITED ARCHIVAL PIECES</span>
    </div>
</div>

<!-- 3. SEASONAL COLLECTION GRID -->
<section id="collection" class="py-20 sm:py-28 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto" x-data="{ selectedCategory: 'all' }">
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 pb-6 border-b border-white/10 gap-6" data-motion="fade-up">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2 font-medium">
                Seasonal Selection
            </span>
            <h2 class="font-serif text-3xl md:text-5xl text-white font-light tracking-wide">
                Runway Winter Capsule
            </h2>
        </div>

        <!-- Category Filters -->
        <div class="flex flex-wrap gap-2">
            <button 
                @click="selectedCategory = 'all'"
                :class="selectedCategory === 'all' ? 'bg-[#c5a880] text-black font-semibold' : 'bg-neutral-900 text-neutral-400 hover:text-white border border-white/5'"
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-full transition-all duration-300"
            >
                All Creations
            </button>
            @foreach($categories as $category)
                <button 
                    @click="selectedCategory = '{{ $category->slug }}'"
                    :class="selectedCategory === '{{ $category->slug }}' ? 'bg-[#c5a880] text-black font-semibold' : 'bg-neutral-900 text-neutral-400 hover:text-white border border-white/5'"
                    class="px-4 py-2 text-xs uppercase tracking-wider rounded-full transition-all duration-300"
                >
                    {{ $category->name }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
        @foreach($products as $product)
            <div 
                x-show="selectedCategory === 'all' || selectedCategory === '{{ $product->category->slug ?? '' }}'"
                class="group relative flex flex-col"
                data-motion="fade-up"
            >
                <!-- Image Frame with Hover Flip -->
                <div class="relative aspect-[3/4] w-full overflow-hidden bg-neutral-900 rounded-sm">
                    <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
                        <img 
                            src="{{ $product->image }}" 
                            alt="{{ $product->name }}" 
                            class="absolute inset-0 w-full h-full object-cover transition-all duration-700 group-hover:scale-105 group-hover:opacity-0"
                        >
                        @if($product->hover_image)
                            <img 
                                src="{{ $product->hover_image }}" 
                                alt="{{ $product->name }} alternate" 
                                class="absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 group-hover:scale-105 group-hover:opacity-100"
                            >
                        @endif
                    </a>

                    <!-- Tag Badge -->
                    @if($product->tag)
                        <span class="absolute top-3 left-3 px-2.5 py-1 text-[10px] uppercase tracking-widest bg-black/60 backdrop-blur-md text-[#c5a880] border border-[#c5a880]/30 rounded-full font-medium">
                            {{ $product->tag }}
                        </span>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="mt-4 flex justify-between items-start gap-2">
                    <div>
                        <a href="{{ route('product.show', $product->slug) }}">
                            <h3 class="font-serif text-base text-neutral-100 group-hover:text-[#c5a880] transition-colors duration-300">
                                {{ $product->name }}
                            </h3>
                        </a>
                        <p class="text-xs text-neutral-500 uppercase tracking-wider mt-0.5">
                            {{ $product->material }}
                        </p>
                    </div>
                    <span class="font-sans text-sm text-neutral-300 font-medium shrink-0">
                        ${{ number_format($product->price, 0) }}
                    </span>
                </div>

                <!-- Dynamic Add to Bag Action -->
                <div class="mt-3 pt-3 border-t border-white/5 flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="$store.cart.add({{ $product->id }}, '{{ $product->sizes[0] ?? 'M' }}', 1)"
                        class="flex-1 py-2.5 px-3 bg-white/5 hover:bg-[#c5a880] text-neutral-200 hover:text-black border border-white/10 hover:border-transparent rounded text-[11px] uppercase tracking-[0.18em] font-semibold flex items-center justify-center space-x-2 transition-all duration-300 cursor-pointer shadow-sm active:scale-[0.98]"
                        title="Add to Shopping Bag"
                    >
                        <svg class="w-3.5 h-3.5 stroke-[1.8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Add to Bag</span>
                    </button>
                    <a 
                        href="{{ route('product.show', $product->slug) }}"
                        class="p-2.5 bg-white/5 hover:bg-white/15 text-neutral-400 hover:text-white border border-white/10 rounded transition-colors"
                        title="View Details"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-16 text-center">
        <a 
            href="{{ route('shop.index') }}" 
            data-magnetic
            class="inline-block px-8 py-3.5 border border-white/20 text-xs uppercase tracking-[0.25em] text-white rounded hover:border-[#c5a880] hover:text-[#c5a880] transition-colors"
        >
            View Complete Catalogue ({{ count($products) }})
        </a>
    </div>
</section>

<!-- 4. ATELIER MANIFESTO -->
<section class="py-24 bg-neutral-950 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-16 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-5 space-y-6" data-motion="fade-up">
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block font-medium">
                The Atelier Philosophy
            </span>
            <h3 class="font-serif text-3xl md:text-5xl text-white font-light leading-snug mt-3">
                Refined tailoring, zero compromise on architectural form.
            </h3>
            <p class="text-sm text-neutral-400 leading-relaxed font-light mt-4">
                Every garment at LAVÉRO is drafted by hand on wooden mannequins, balancing strict architectural lines with supple drape. We source certified sustainable cashmere and double-faced wool directly from family-run mills in Biella, Italy.
            </p>
            <div class="pt-4 flex items-center space-x-8 text-xs uppercase tracking-widest text-neutral-300">
                <div>
                    <span class="block font-serif text-2xl text-white font-normal">100%</span>
                    <span>Traceable Fabrics</span>
                </div>
                <div class="w-[1px] h-8 bg-white/10"></div>
                <div>
                    <span class="block font-serif text-2xl text-white font-normal">48h</span>
                    <span>Artisanal Hand Stitch</span>
                </div>
            </div>
            <div class="pt-4">
                <a href="{{ route('about') }}" class="text-xs uppercase tracking-[0.2em] text-[#c5a880] hover:underline">
                    Read Our Craftsmanship Manifesto &rarr;
                </a>
            </div>
        </div>

        <div class="lg:col-span-7 grid grid-cols-2 gap-4">
            <div class="aspect-[3/4] overflow-hidden rounded bg-neutral-900" data-motion="fade-in">
                @if(isset($products[1]))
                    <img 
                        src="{{ $products[1]->image }}" 
                        alt="{{ $products[1]->name }}" 
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                    >
                @endif
            </div>
            <div class="aspect-[3/4] overflow-hidden rounded mt-8 bg-neutral-900" data-motion="fade-in">
                @if(isset($products[2]))
                    <img 
                        src="{{ $products[2]->image }}" 
                        alt="{{ $products[2]->name }}" 
                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-700"
                    >
                @endif
            </div>
        </div>
    </div>
</section>

<!-- 5. INTERACTIVE SHOP-THE-LOOK RUNWAY SPOTLIGHT -->
<section class="py-20 sm:py-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto">
    <div class="text-center max-w-xl mx-auto mb-16" data-motion="fade-up">
        <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2">
            Interactive Lookbook
        </span>
        <h2 class="font-serif text-3xl md:text-4xl text-white font-light">
            {{ $products[0]->name ?? 'Runway Spotlight' }} &middot; Paris Runway Showcase
        </h2>
    </div>

    <div class="relative aspect-[16/9] max-w-5xl mx-auto overflow-hidden rounded-lg bg-neutral-900 border border-white/10" data-motion="fade-in">
        @if(isset($products[0]))
            <img 
                src="{{ $products[0]->hover_image ?? $products[0]->image }}" 
                alt="{{ $products[0]->name }}" 
                class="w-full h-full object-cover"
            >
        @endif

        <!-- Hotspot Pin -->
        @if(isset($products[0]))
            <div class="absolute top-[35%] left-[52%] group">
                <button class="w-6 h-6 rounded-full bg-white text-black flex items-center justify-center text-xs font-bold shadow-lg animate-ping absolute inset-0 opacity-75"></button>
                <button class="relative w-6 h-6 rounded-full bg-white text-black flex items-center justify-center text-xs font-bold shadow-lg">
                    +
                </button>
                <!-- Popover Card -->
                <div class="absolute left-8 top-1/2 -translate-y-1/2 hidden group-hover:flex items-center gap-3 bg-black/90 backdrop-blur-md border border-white/15 p-3 rounded shadow-2xl w-60 z-20">
                    <img src="{{ $products[0]->image }}" class="w-12 h-14 object-cover rounded bg-neutral-950">
                    <div class="flex-1">
                        <p class="text-xs font-serif text-white font-medium line-clamp-1">{{ $products[0]->name }}</p>
                        <p class="text-[11px] text-[#c5a880]">${{ number_format($products[0]->price, 0) }}</p>
                    </div>
                    <form action="{{ route('cart.add') }}" method="POST" @submit.prevent="$store.cart.add({{ $products[0]->id }}, '{{ $products[0]->sizes[0] ?? 'M' }}', 1)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $products[0]->id }}">
                        <input type="hidden" name="size" value="{{ $products[0]->sizes[0] ?? 'M' }}">
                        <button type="submit" class="px-2 py-1 bg-white text-black text-[10px] uppercase font-bold rounded hover:bg-[#c5a880] hover:text-white transition-colors cursor-pointer">
                            Add
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- 6. SALON APPOINTMENT CTA -->
<section class="py-16 sm:py-20 bg-gradient-to-b from-[#090909] to-neutral-950 border-t border-white/5 text-center px-4 sm:px-6">
    <div class="max-w-2xl mx-auto" data-motion="fade-up">
        <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-3 font-medium">
            Personal Atelier Experience
        </span>
        <h2 class="font-serif text-3xl md:text-5xl text-white font-light">
            Book a Bespoke Fitting in Paris or Milan
        </h2>
        <p class="text-sm text-neutral-400 mt-4 leading-relaxed font-light">
            Experience individual consultations with our master patternmakers. Selected garments fitted precisely to your measurements.
        </p>
        <div class="mt-8">
            <a 
                href="{{ route('contact') }}" 
                data-magnetic
                class="inline-block px-8 py-4 bg-white text-black text-xs uppercase tracking-[0.25em] font-semibold rounded hover:bg-[#c5a880] hover:text-white transition-colors"
            >
                Reserve Private Salon Fitting
            </a>
        </div>
    </div>
</section>
@endsection
