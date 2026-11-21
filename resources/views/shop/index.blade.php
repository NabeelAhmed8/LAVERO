@extends('layouts.app')

@section('title', 'Ready-to-Wear & Couture Catalogue — LAVÉRO')

@section('content')
<div 
    x-data="{
        gridCols: (window.innerWidth < 640) ? 2 : 3,
        quickViewOpen: false,
        activeProduct: null,
        modalSelectedSize: '',
        modalAdding: false,
        modalAdded: false,
        openQuickView(prod) {
            this.activeProduct = prod;
            this.modalSelectedSize = (prod.sizes && prod.sizes.length > 0) ? prod.sizes[0] : 'Standard';
            this.modalAdding = false;
            this.modalAdded = false;
            this.quickViewOpen = true;
        },
        async addModalToBag() {
            if (!this.activeProduct) return;
            this.modalAdding = true;
            await $store.cart.add(this.activeProduct.id, this.modalSelectedSize, 1);
            this.modalAdding = false;
            this.modalAdded = true;
            $store.cart.open = true;
            setTimeout(() => {
                this.modalAdded = false;
                this.quickViewOpen = false;
            }, 1200);
        }
    }"
    class="pt-24 sm:pt-28 pb-20 px-3 sm:px-6 md:px-10 lg:px-12 max-w-7xl mx-auto"
>
    <!-- 1. Editorial Header -->
    <div class="mb-8 pb-6 border-b border-white/10 flex flex-col md:flex-row md:items-end justify-between gap-4" data-motion="fade-up">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#c5a880] animate-pulse"></span>
                <span class="text-[10px] sm:text-xs uppercase tracking-[0.3em] text-[#c5a880] font-medium">
                    Maison LAVÉRO Archive
                </span>
            </div>
            <h1 class="font-serif text-2xl sm:text-4xl md:text-5xl text-white font-light tracking-wide">
                Haute Couture &amp; Ready-to-Wear
            </h1>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-xs font-mono text-neutral-400">
            <span>
                Showing <span class="text-white font-medium">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</span> of <span class="text-[#c5a880] font-medium">{{ $products->total() }}</span> garments
            </span>
            @if(!empty($totalProducts) && $totalProducts !== $products->total())
                <span class="text-neutral-600 hidden sm:inline">&bull;</span>
                <span class="text-neutral-500 hidden sm:inline">({{ $totalProducts }} total in archive)</span>
            @endif
        </div>
    </div>

    <!-- 2. Dynamic Toolbar: Search, Filters, Sort, and Grid Density -->
    <div class="space-y-4 mb-8 pb-6 border-b border-white/5">
        <!-- Top Toolbar Row: Search Input + Sort + Grid Switcher -->
        <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
            <!-- Search Form -->
            <form action="{{ route('shop.index') }}" method="GET" class="relative flex-1 max-w-md">
                @if($activeCategory)
                    <input type="hidden" name="category" value="{{ $activeCategory }}">
                @endif
                @if($activeSort)
                    <input type="hidden" name="sort" value="{{ $activeSort }}">
                @endif
                <div class="relative">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search ?? '' }}"
                        placeholder="Search creations, fabric, tailoring..." 
                        class="w-full bg-neutral-900/90 border border-white/10 hover:border-white/20 focus:border-[#c5a880] text-xs text-white rounded-full pl-9 pr-9 py-2 outline-none transition-all placeholder:text-neutral-500"
                    >
                    <svg class="w-3.5 h-3.5 text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if(!empty($search))
                        <a 
                            href="{{ route('shop.index', array_filter(['category' => $activeCategory, 'sort' => $activeSort])) }}" 
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white text-xs"
                            title="Clear search"
                        >
                            &times;
                        </a>
                    @endif
                </div>
            </form>

            <!-- Controls: Sorting & Grid Switcher -->
            <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0">
                <!-- Sorting Dropdown -->
                <div class="flex items-center space-x-2 text-xs text-neutral-400">
                    <label for="catalog-sort" class="text-neutral-500 text-[11px] uppercase tracking-wider hidden sm:inline">Sort:</label>
                    <select 
                        id="catalog-sort"
                        name="sort"
                        onchange="window.location.href = this.value"
                        class="bg-neutral-900 border border-white/10 text-white rounded-lg px-2.5 py-1.5 text-xs outline-none focus:border-[#c5a880] cursor-pointer"
                    >
                        <option value="{{ route('shop.index', array_filter(['category' => $activeCategory, 'search' => $search])) }}" {{ empty($activeSort) ? 'selected' : '' }}>Latest Drops</option>
                        <option value="{{ route('shop.index', array_filter(['category' => $activeCategory, 'search' => $search, 'sort' => 'price_asc'])) }}" {{ $activeSort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ route('shop.index', array_filter(['category' => $activeCategory, 'search' => $search, 'sort' => 'price_desc'])) }}" {{ $activeSort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    </select>
                </div>

                <!-- Grid Density Switcher (Dynamic Responsive Toggle) -->
                <div class="flex items-center gap-1 p-1 bg-neutral-900/80 border border-white/10 rounded-lg">
                    <!-- 1 Column (Mobile Focused) -->
                    <button 
                        type="button"
                        @click="gridCols = 1"
                        :class="gridCols === 1 ? 'bg-[#c5a880] text-black' : 'text-neutral-400 hover:text-white'"
                        class="p-1 rounded transition-colors block sm:hidden"
                        title="Single Column View"
                    >
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 16 16">
                            <rect width="14" height="12" x="1" y="2" rx="1.5" />
                        </svg>
                    </button>

                    <!-- 2 Columns -->
                    <button 
                        type="button"
                        @click="gridCols = 2"
                        :class="gridCols === 2 ? 'bg-[#c5a880] text-black' : 'text-neutral-400 hover:text-white'"
                        class="p-1 rounded transition-colors"
                        title="2 Columns View"
                    >
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 16 16">
                            <rect width="6" height="12" x="1" y="2" rx="1" />
                            <rect width="6" height="12" x="9" y="2" rx="1" />
                        </svg>
                    </button>

                    <!-- 3 Columns (Tablet & Desktop) -->
                    <button 
                        type="button"
                        @click="gridCols = 3"
                        :class="gridCols === 3 ? 'bg-[#c5a880] text-black' : 'text-neutral-400 hover:text-white'"
                        class="p-1 rounded transition-colors hidden sm:block"
                        title="3 Columns View"
                    >
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 16 16">
                            <rect width="3.8" height="12" x="1" y="2" rx="0.8" />
                            <rect width="3.8" height="12" x="6.1" y="2" rx="0.8" />
                            <rect width="3.8" height="12" x="11.2" y="2" rx="0.8" />
                        </svg>
                    </button>

                    <!-- 4 Columns (Desktop Wide) -->
                    <button 
                        type="button"
                        @click="gridCols = 4"
                        :class="gridCols === 4 ? 'bg-[#c5a880] text-black' : 'text-neutral-400 hover:text-white'"
                        class="p-1 rounded transition-colors hidden md:block"
                        title="4 Columns View"
                    >
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 16 16">
                            <rect width="2.6" height="12" x="1" y="2" rx="0.6" />
                            <rect width="2.6" height="12" x="4.8" y="2" rx="0.6" />
                            <rect width="2.6" height="12" x="8.6" y="2" rx="0.6" />
                            <rect width="2.6" height="12" x="12.4" y="2" rx="0.6" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Category Filter Pills (Smooth Responsive Horizontal Scroll) -->
        <div class="relative">
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar scroll-smooth py-1">
                <a 
                    href="{{ route('shop.index', array_filter(['sort' => $activeSort, 'search' => $search])) }}" 
                    class="px-3.5 py-1.5 text-[11px] sm:text-xs uppercase tracking-wider rounded-full shrink-0 transition-all duration-300 {{ empty($activeCategory) ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'bg-neutral-900 text-neutral-400 hover:text-white border border-white/5 hover:border-white/20' }}"
                >
                    All ({{ $totalProducts ?? $products->total() }})
                </a>
                @foreach($categories as $category)
                    <a 
                        href="{{ route('shop.index', array_filter(['category' => $category->slug, 'sort' => $activeSort, 'search' => $search])) }}" 
                        class="px-3.5 py-1.5 text-[11px] sm:text-xs uppercase tracking-wider rounded-full shrink-0 transition-all duration-300 {{ $activeCategory === $category->slug ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'bg-neutral-900 text-neutral-400 hover:text-white border border-white/5 hover:border-white/20' }}"
                    >
                        {{ $category->name }} ({{ $category->products_count }})
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Active Filter Feedback Bar -->
        @if(!empty($activeCategory) || !empty($search))
            <div class="flex flex-wrap items-center gap-2 pt-2 text-xs">
                <span class="text-neutral-500 uppercase tracking-widest text-[10px]">Active Filters:</span>
                @if(!empty($activeCategory))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-[#c5a880]/15 text-[#c5a880] border border-[#c5a880]/30 rounded-full text-[11px]">
                        <span>Category: {{ ucwords(str_replace('-', ' ', $activeCategory)) }}</span>
                        <a href="{{ route('shop.index', array_filter(['sort' => $activeSort, 'search' => $search])) }}" class="hover:text-white">&times;</a>
                    </span>
                @endif
                @if(!empty($search))
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-white/10 text-white border border-white/20 rounded-full text-[11px]">
                        <span>Search: "{{ $search }}"</span>
                        <a href="{{ route('shop.index', array_filter(['category' => $activeCategory, 'sort' => $activeSort])) }}" class="hover:text-red-400">&times;</a>
                    </span>
                @endif
                <a href="{{ route('shop.index') }}" class="text-[11px] text-neutral-400 hover:text-[#c5a880] underline ml-2">
                    Reset All
                </a>
            </div>
        @endif
    </div>

    <!-- 3. Dynamic Responsive Product Grid -->
    <div 
        class="grid items-stretch gap-x-3 sm:gap-x-5 md:gap-x-6 gap-y-7 sm:gap-y-9 transition-all duration-300"
        :class="{
            'grid-cols-1 max-w-md mx-auto': gridCols === 1,
            'grid-cols-2': gridCols === 2,
            'grid-cols-2 sm:grid-cols-2 md:grid-cols-3': gridCols === 3,
            'grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4': gridCols === 4
        }"
    >
        @forelse($products as $product)
            @php
                $availableSizes = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes ?? '[]', true);
                if (empty($availableSizes)) { $availableSizes = ['Standard']; }
                $initialSize = $availableSizes[0] ?? 'Standard';
                $productJson = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->price,
                    'material' => $product->material,
                    'description' => $product->description,
                    'tag' => $product->tag,
                    'image' => $product->image,
                    'hover_image' => $product->hover_image,
                    'sizes' => $availableSizes,
                    'stock_status' => $product->stock_status,
                    'stock_quantity' => $product->stock_quantity,
                    'in_stock' => $product->in_stock,
                ];
            @endphp
            <div 
                x-data="{
                    selectedSize: '{{ $initialSize }}',
                    adding: false,
                    added: false,
                    async addToBag() {
                        this.adding = true;
                        await $store.cart.add({{ $product->id }}, this.selectedSize, 1);
                        this.adding = false;
                        this.added = true;
                        $store.cart.open = true;
                        setTimeout(() => this.added = false, 2500);
                    }
                }"
                class="group relative h-full flex flex-col bg-neutral-950/60 hover:bg-neutral-900/40 border border-white/10 hover:border-[#c5a880]/40 rounded-xl p-3 sm:p-4 transition-all duration-300 shadow-md hover:shadow-2xl hover:-translate-y-1"
                data-motion="fade-up"
            >
                <!-- Image Container with Alternate View & Quick View trigger (Fixed Ratio) -->
                <div class="relative aspect-[4/5] sm:aspect-[3/4] w-full shrink-0 overflow-hidden bg-neutral-900 rounded-lg">
                    <a href="{{ route('product.show', $product->slug) }}" class="block w-full h-full">
                        <img 
                            src="{{ $product->image }}" 
                            alt="{{ $product->name }}" 
                            loading="lazy"
                            class="absolute inset-0 w-full h-full object-cover transition-all duration-700 group-hover:scale-105 group-hover:opacity-0"
                        >
                        @if($product->hover_image)
                            <img 
                                src="{{ $product->hover_image }}" 
                                alt="{{ $product->name }} alternate view" 
                                loading="lazy"
                                class="absolute inset-0 w-full h-full object-cover opacity-0 transition-all duration-700 group-hover:scale-105 group-hover:opacity-100"
                            >
                        @endif
                    </a>

                    <!-- Tag Badge -->
                    @if($product->tag)
                        <span class="absolute top-2.5 left-2.5 px-2.5 py-0.5 text-[9px] uppercase tracking-widest bg-black/80 backdrop-blur-md text-[#c5a880] border border-[#c5a880]/30 rounded-full font-medium pointer-events-none">
                            {{ $product->tag }}
                        </span>
                    @endif

                    <!-- Quick View Floating Button -->
                    <button 
                        type="button"
                        @click="openQuickView({{ json_encode($productJson) }})"
                        class="absolute bottom-2.5 right-2.5 p-2 bg-black/85 hover:bg-[#c5a880] text-white hover:text-black backdrop-blur-md rounded-lg opacity-0 group-hover:opacity-100 transition-all duration-300 shadow-xl cursor-pointer flex items-center gap-1.5 text-[10px] uppercase tracking-wider"
                        title="Quick View"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="hidden lg:inline text-[9px] font-semibold">Quick View</span>
                    </button>
                </div>

                <!-- Product Body (Flex-1 ensures cards stretch equally and footer pins to bottom) -->
                <div class="flex-1 flex flex-col pt-3 sm:pt-3.5">
                    <!-- Title & Price (Single line truncate for uniform height) -->
                    <div class="flex justify-between items-start gap-2">
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('product.show', $product->slug) }}">
                                <h3 class="font-serif text-xs sm:text-sm text-neutral-100 group-hover:text-[#c5a880] transition-colors duration-300 truncate font-normal" title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>
                            </a>
                            <p class="text-[10px] sm:text-[11px] text-neutral-500 uppercase tracking-wider mt-0.5 truncate">
                                {{ $product->material }}
                            </p>
                        </div>
                        <span class="font-sans text-xs sm:text-sm text-neutral-200 font-medium shrink-0">
                            ${{ number_format($product->price, 0) }}
                        </span>
                    </div>

                    <!-- Fixed-Height Stock Badge (Guarantees zero height shifts across cards) -->
                    <div class="h-4 flex items-center mt-1.5">
                        @if($product->stock_status === 'low_stock' || ($product->stock_quantity <= 5 && $product->stock_quantity > 0))
                            <span class="text-[9px] text-amber-400 font-medium tracking-wider flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span>{{ $product->stock_quantity }} Remaining</span>
                            </span>
                        @elseif($product->stock_status === 'out_of_stock' || $product->stock_quantity <= 0)
                            <span class="text-[9px] text-rose-400 font-medium tracking-wider flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                <span>Bespoke Order</span>
                            </span>
                        @else
                            <span class="text-[9px] text-emerald-400/80 tracking-wider flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400/80"></span>
                                <span>Atelier Available</span>
                            </span>
                        @endif
                    </div>

                    <!-- Fixed-Height Size Selector Row with generous breathing room -->
                    <div class="h-7 flex items-center justify-between mt-3 pt-2 border-t border-white/5 text-[9px] uppercase tracking-wider text-neutral-400">
                        @if(count($availableSizes) > 1)
                            <span class="text-neutral-500 mr-1 shrink-0">Size:</span>
                            <div class="flex items-center gap-1 overflow-x-auto no-scrollbar">
                                @foreach($availableSizes as $sz)
                                    <button 
                                        type="button"
                                        @click="selectedSize = '{{ $sz }}'"
                                        :class="selectedSize === '{{ $sz }}' ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'bg-neutral-900 text-neutral-400 hover:text-white border border-white/10 hover:border-white/20'"
                                        class="px-2 py-0.5 text-[9px] font-mono rounded transition-colors shrink-0"
                                    >
                                        {{ $sz }}
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <span class="text-neutral-500">Size:</span>
                            <span class="font-mono text-neutral-400 px-2 py-0.5 bg-neutral-900/60 border border-white/5 rounded">{{ $availableSizes[0] ?? 'Standard' }}</span>
                        @endif
                    </div>

                    <!-- Pinned Bottom Action Bar: Generous padding from sizes + Bag Icon -->
                    <div class="mt-4 pt-3.5 border-t border-white/10 flex items-center gap-2">
                        @if($product->stock_status === 'out_of_stock' || $product->stock_quantity <= 0)
                            <a 
                                href="{{ route('product.show', $product->slug) }}"
                                class="flex-1 py-2 sm:py-2.5 px-3 bg-neutral-900 text-neutral-400 hover:text-white border border-white/10 rounded-lg text-[10px] uppercase tracking-[0.16em] font-semibold flex items-center justify-center transition-all duration-300"
                            >
                                <span>Bespoke</span>
                            </a>
                        @else
                            <button 
                                type="button" 
                                @click="addToBag()"
                                :disabled="adding"
                                :class="added ? 'bg-[#c5a880] text-black border-transparent shadow-md' : 'bg-white/5 hover:bg-[#c5a880] text-neutral-200 hover:text-black border-white/10 hover:border-transparent'"
                                class="flex-1 py-2 sm:py-2.5 px-3 border rounded-lg transition-all duration-300 flex items-center justify-center cursor-pointer shadow-sm active:scale-95 group/btn"
                                title="Add to Shopping Bag"
                            >
                                <template x-if="adding">
                                    <svg class="animate-spin w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </template>
                                <template x-if="added">
                                    <span class="font-bold flex items-center justify-center text-black">
                                        <svg class="w-4 h-4 stroke-[2.2]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </template>
                                <template x-if="!adding && !added">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-4 h-4 stroke-[1.8] group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </span>
                                </template>
                            </button>
                        @endif
                        <a 
                            href="{{ route('product.show', $product->slug) }}"
                            class="p-2 sm:p-2.5 bg-white/5 hover:bg-white/15 text-neutral-400 hover:text-white border border-white/10 rounded-lg transition-colors shrink-0 flex items-center justify-center"
                            title="View Garment Details"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-24 text-neutral-500">
                <p class="font-serif text-2xl text-neutral-400">No creations matched your criteria.</p>
                <p class="text-xs text-neutral-500 mt-2">Try adjusting your filters or search keywords.</p>
                <a href="{{ route('shop.index') }}" class="text-xs uppercase tracking-widest text-[#c5a880] mt-4 inline-block hover:underline">
                    View Complete Archive &rarr;
                </a>
            </div>
        @endforelse
    </div>

    <!-- 4. Dynamic Responsive Pagination -->
    @if($products->hasPages())
        <div class="mt-14 pt-8 border-t border-white/10 flex justify-center">
            {{ $products->links() }}
        </div>
    @endif

    <!-- 5. Dynamic Couture Quick View Modal -->
    <div 
        x-show="quickViewOpen" 
        x-cloak 
        @keydown.escape.window="quickViewOpen = false"
        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-3 sm:p-6 bg-black/85 backdrop-blur-md"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            @click.away="quickViewOpen = false"
            class="relative w-full max-w-3xl bg-neutral-950 border border-white/15 rounded-xl shadow-2xl overflow-hidden flex flex-col md:flex-row"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <!-- Close Button -->
            <button 
                type="button" 
                @click="quickViewOpen = false"
                class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-black/60 hover:bg-white text-white hover:text-black border border-white/10 flex items-center justify-center transition-colors text-lg"
            >
                &times;
            </button>

            <!-- Modal Image Gallery -->
            <div class="w-full md:w-1/2 aspect-[3/4] relative bg-neutral-900 overflow-hidden">
                <template x-if="activeProduct">
                    <img 
                        :src="activeProduct.image" 
                        :alt="activeProduct.name" 
                        class="w-full h-full object-cover"
                    >
                </template>
                <template x-if="activeProduct && activeProduct.tag">
                    <span 
                        x-text="activeProduct.tag"
                        class="absolute top-3 left-3 px-2.5 py-1 text-[9px] uppercase tracking-widest bg-black/80 text-[#c5a880] border border-[#c5a880]/30 rounded-full font-medium"
                    ></span>
                </template>
            </div>

            <!-- Modal Product Details -->
            <div class="w-full md:w-1/2 p-6 flex flex-col justify-between">
                <div>
                    <span class="text-[10px] uppercase tracking-[0.25em] text-[#c5a880] block mb-1">Haute Archive Preview</span>
                    <h2 class="font-serif text-xl sm:text-2xl text-white font-normal" x-text="activeProduct?.name"></h2>
                    <p class="text-xs text-neutral-400 mt-1 uppercase tracking-wider" x-text="activeProduct?.material"></p>
                    
                    <div class="mt-4 pb-4 border-b border-white/10 flex items-baseline justify-between">
                        <span class="text-xl text-white font-medium font-sans" x-text="'$' + Number(activeProduct?.price || 0).toLocaleString()"></span>
                        <template x-if="activeProduct?.stock_status === 'low_stock'">
                            <span class="text-xs text-amber-400 font-medium">Low Stock Edition</span>
                        </template>
                    </div>

                    <p class="text-xs text-neutral-300 leading-relaxed mt-4 line-clamp-3" x-text="activeProduct?.description"></p>

                    <!-- Modal Size Picker -->
                    <div class="mt-5">
                        <span class="text-[10px] uppercase tracking-widest text-neutral-400 block mb-2 font-medium">Select Atelier Size:</span>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="sz in (activeProduct?.sizes || ['Standard'])" :key="sz">
                                <button 
                                    type="button"
                                    @click="modalSelectedSize = sz"
                                    :class="modalSelectedSize === sz ? 'bg-[#c5a880] text-black font-semibold shadow' : 'bg-neutral-900 text-neutral-300 hover:text-white border border-white/10'"
                                    class="px-3 py-1.5 text-xs font-mono rounded transition-colors"
                                    x-text="sz"
                                ></button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-6 pt-4 border-t border-white/10 flex flex-col gap-2.5">
                    <button 
                        type="button" 
                        @click="addModalToBag()"
                        :disabled="modalAdding"
                        :class="modalAdded ? 'bg-[#c5a880] text-black' : 'bg-white text-black hover:bg-[#c5a880] hover:text-black'"
                        class="w-full py-3 px-4 rounded text-xs uppercase tracking-[0.2em] font-semibold transition-all duration-300 flex items-center justify-center space-x-2 cursor-pointer"
                    >
                        <template x-if="modalAdding">
                            <span>Adding to Bag...</span>
                        </template>
                        <template x-if="modalAdded">
                            <span>Added to Shopping Bag ✓</span>
                        </template>
                        <template x-if="!modalAdding && !modalAdded">
                            <span>Add to Shopping Bag</span>
                        </template>
                    </button>
                    
                    <a 
                        :href="'/product/' + activeProduct?.slug" 
                        class="text-center text-[11px] uppercase tracking-widest text-neutral-400 hover:text-[#c5a880] py-1 transition-colors"
                    >
                        View Full Garment Dossier &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
