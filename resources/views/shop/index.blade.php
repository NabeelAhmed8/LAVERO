@extends('layouts.app')

@section('title', 'Ready-to-Wear & Couture Catalogue — LAVÉRO')

@section('content')
<div class="pt-28 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-12 pb-8 border-b border-white/10 flex flex-col md:flex-row md:items-end justify-between gap-6" data-motion="fade-up">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2 font-medium">
                Maison LAVÉRO Archive
            </span>
            <h1 class="font-serif text-3xl md:text-5xl text-white font-light tracking-wide">
                Haute Couture &amp; Ready-to-Wear
            </h1>
        </div>
        <p class="text-xs uppercase tracking-widest text-neutral-400">
            Showing {{ $products->total() }} Individual Creations
        </p>
    </div>

    <!-- Filters & Sort Bar -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-12 pb-6 border-b border-white/5">
        <!-- Categories -->
        <div class="flex flex-wrap gap-2">
            <a 
                href="{{ route('shop.index') }}" 
                class="px-4 py-2 text-xs uppercase tracking-wider rounded-full transition-all duration-300 {{ empty($activeCategory) ? 'bg-[#c5a880] text-black font-semibold' : 'bg-neutral-900 text-neutral-400 hover:text-white border border-white/5' }}"
            >
                All ({{ $products->total() }})
            </a>
            @foreach($categories as $category)
                <a 
                    href="{{ route('shop.index', ['category' => $category->slug, 'sort' => $activeSort]) }}" 
                    class="px-4 py-2 text-xs uppercase tracking-wider rounded-full transition-all duration-300 {{ $activeCategory === $category->slug ? 'bg-[#c5a880] text-black font-semibold' : 'bg-neutral-900 text-neutral-400 hover:text-white border border-white/5' }}"
                >
                    {{ $category->name }} ({{ $category->products_count }})
                </a>
            @endforeach
        </div>

        <!-- Sorting -->
        <div class="flex items-center space-x-3 text-xs text-neutral-400 uppercase tracking-widest">
            <label for="catalog-sort" class="text-neutral-400">Sort by:</label>
            <select 
                id="catalog-sort"
                name="sort"
                onchange="window.location.href = this.value"
                class="bg-neutral-900 border border-white/10 text-white rounded px-3 py-1.5 text-xs outline-none focus:border-[#c5a880] cursor-pointer"
            >
                <option value="{{ route('shop.index', ['category' => $activeCategory]) }}" {{ empty($activeSort) ? 'selected' : '' }}>Latest Drops</option>
                <option value="{{ route('shop.index', ['category' => $activeCategory, 'sort' => 'price_asc']) }}" {{ $activeSort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="{{ route('shop.index', ['category' => $activeCategory, 'sort' => 'price_desc']) }}" {{ $activeSort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-14">
        @forelse($products as $product)
            <div class="group relative flex flex-col" data-motion="fade-up">
                <!-- Image Frame -->
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
                        @if($product->stock_status === 'low_stock' || ($product->stock_quantity <= 5 && $product->stock_quantity > 0))
                            <span class="text-[10px] text-amber-400 font-medium tracking-wider block mt-1">
                                &bull; Only {{ $product->stock_quantity }} Remaining
                            </span>
                        @elseif($product->stock_status === 'out_of_stock' || $product->stock_quantity <= 0)
                            <span class="text-[10px] text-rose-400 font-medium tracking-wider block mt-1">
                                &bull; Out of Stock (Bespoke)
                            </span>
                        @endif
                    </div>
                    <span class="font-sans text-sm text-neutral-300 font-medium shrink-0">
                        ${{ number_format($product->price, 0) }}
                    </span>
                </div>

                <!-- Dynamic Add to Bag Action -->
                <div class="mt-3 pt-3 border-t border-white/5 flex items-center gap-2">
                    @if($product->stock_status === 'out_of_stock' || $product->stock_quantity <= 0)
                        <a 
                            href="{{ route('product.show', $product->slug) }}"
                            class="flex-1 py-2.5 px-3 bg-neutral-900 text-neutral-400 hover:text-white border border-white/10 rounded text-[11px] uppercase tracking-[0.18em] font-semibold flex items-center justify-center transition-all duration-300"
                        >
                            <span>Bespoke Order</span>
                        </a>
                    @else
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
                    @endif
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
        @empty
            <div class="col-span-3 text-center py-20 text-neutral-500">
                <p class="font-serif text-2xl text-neutral-400">No creations found in this category.</p>
                <a href="{{ route('shop.index') }}" class="text-xs uppercase tracking-widest text-[#c5a880] mt-4 inline-block hover:underline">
                    View All Garments &rarr;
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-16 pt-8 border-t border-white/5 flex justify-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
