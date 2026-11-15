@extends('layouts.app')

@section('title', $product->name . ' — LAVÉRO')

@section('content')
<div class="pt-28 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto" x-data="{ 
    activeImage: '{{ $product->image }}',
    selectedSize: '{{ $product->sizes[0] ?? "M" }}',
    quantity: 1,
    openSection: 'details'
}">
    <!-- Breadcrumb -->
    <div class="mb-10">
        <a href="{{ route('shop.index') }}" class="inline-flex items-center space-x-2 text-xs uppercase tracking-[0.2em] text-neutral-400 hover:text-white transition-colors">
            <span>&larr;</span>
            <span>Return to Collection</span>
        </a>
    </div>

    <!-- Product Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16">
        <!-- Left: Image Gallery Column -->
        <div class="lg:col-span-7 space-y-4">
            <div class="relative aspect-[3/4] w-full overflow-hidden rounded-md bg-neutral-900 border border-white/5">
                <img 
                    :src="activeImage" 
                    alt="{{ $product->name }}" 
                    class="w-full h-full object-cover transition-all duration-500"
                >
                @if($product->tag)
                    <span class="absolute top-4 left-4 px-3 py-1.5 text-[11px] uppercase tracking-widest bg-black/70 backdrop-blur-md text-[#c5a880] border border-[#c5a880]/30 rounded-full font-medium">
                        {{ $product->tag }}
                    </span>
                @endif
            </div>

            <!-- Thumbnails -->
            @php
                $gallery = array_filter([$product->image, $product->hover_image]);
            @endphp
            @if(count($gallery) > 1)
                <div class="flex gap-4">
                    @foreach($gallery as $img)
                        <button 
                            type="button"
                            @click="activeImage = '{{ $img }}'"
                            :class="activeImage === '{{ $img }}' ? 'border-[#c5a880] opacity-100' : 'border-transparent opacity-50 hover:opacity-80'"
                            class="relative w-24 aspect-[3/4] rounded overflow-hidden border-2 transition-all duration-300"
                        >
                            <img src="{{ $img }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Garment Information & Add to Bag -->
        <div class="lg:col-span-5 flex flex-col justify-start" data-motion="fade-up">
            <div class="flex items-center space-x-2 text-xs uppercase tracking-[0.25em] text-[#c5a880] font-medium mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-[#c5a880]"></span>
                <span>{{ $product->category->name ?? 'Haute Couture' }}</span>
            </div>

            <h1 class="font-serif text-3xl md:text-5xl text-white font-light tracking-tight leading-tight">
                {{ $product->name }}
            </h1>

            <div class="mt-4 flex items-baseline justify-between border-b border-white/10 pb-6">
                <span class="text-2xl font-serif text-white font-medium">
                    ${{ number_format($product->price, 2) }} USD
                </span>
                @php
                    $badge = $product->stock_badge;
                @endphp
                @if($badge['color'] === 'rose')
                    <span class="text-xs font-medium uppercase tracking-widest px-2.5 py-1 rounded bg-rose-950/80 text-rose-300 border border-rose-800">
                        Out of Stock &middot; Bespoke Order
                    </span>
                @elseif($badge['color'] === 'amber')
                    <span class="text-xs font-medium uppercase tracking-widest px-2.5 py-1 rounded bg-amber-950/80 text-amber-300 border border-amber-800">
                        Low in Stock &middot; Only {{ $product->stock_quantity }} Remaining
                    </span>
                @else
                    <span class="text-xs text-neutral-400 uppercase tracking-widest">
                        In Stock &middot; Hand-Finished
                    </span>
                @endif
            </div>

            <p class="mt-6 text-sm text-neutral-300 leading-relaxed font-light">
                {{ $product->description }}
            </p>

            <!-- Fabric & Composition Box -->
            <div class="mt-6 p-4 rounded bg-neutral-900/60 border border-white/5 space-y-1.5 text-xs">
                <div class="flex justify-between text-neutral-400">
                    <span class="uppercase tracking-wider">Composition</span>
                    <span class="text-white font-medium">{{ $product->material }}</span>
                </div>
                <div class="flex justify-between text-neutral-400">
                    <span class="uppercase tracking-wider">Provenance</span>
                    <span class="text-white font-medium">Biella &middot; Milan Atelier</span>
                </div>
            </div>

            <!-- Size Selection & Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST" @submit.prevent="$store.cart.add({{ $product->id }}, selectedSize, quantity)" class="mt-8">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="size" :value="selectedSize">

                @if(!empty($product->sizes))
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3 text-xs uppercase tracking-widest text-neutral-400">
                            <span>Select Size</span>
                            <a href="{{ route('contact') }}#fittings" class="text-[#c5a880] hover:underline">Bespoke Size Guide</a>
                        </div>
                        <div class="flex flex-wrap gap-2.5">
                            @foreach($product->sizes as $size)
                                <button 
                                    type="button" 
                                    @click="selectedSize = '{{ $size }}'"
                                    :class="selectedSize === '{{ $size }}' ? 'bg-[#c5a880] text-black font-semibold shadow-lg shadow-[#c5a880]/20' : 'bg-neutral-900 text-neutral-300 hover:text-white border border-white/10'"
                                    class="min-w-12 py-2.5 px-3 text-xs rounded uppercase tracking-wider transition-all duration-300"
                                >
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Quantity & Submit Button -->
                <div class="space-y-4">
                    @if($badge['color'] === 'rose')
                        <a 
                            href="{{ route('contact') }}#fittings"
                            class="w-full py-4 px-6 bg-neutral-900 border border-white/20 hover:border-[#c5a880] text-neutral-200 hover:text-white rounded text-xs uppercase tracking-[0.25em] font-semibold flex items-center justify-center space-x-2 transition-all duration-300 text-center"
                        >
                            <span>Request Bespoke Commission &middot; Out of Stock</span>
                        </a>
                    @else
                        <button 
                            type="submit" 
                            :disabled="$store.cart.loading"
                            data-magnetic
                            class="w-full py-4 px-6 bg-white hover:bg-[#c5a880] text-black hover:text-white rounded text-xs uppercase tracking-[0.25em] font-semibold flex items-center justify-center space-x-2 transition-all duration-300 shadow-xl cursor-pointer disabled:opacity-60"
                        >
                            <span x-show="!$store.cart.loading">Add to Bag &middot; ${{ number_format($product->price, 0) }}</span>
                            <span x-show="$store.cart.loading" x-cloak>Adding to Bag...</span>
                        </button>
                    @endif

                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-2 sm:space-x-6 text-[10px] sm:text-[11px] text-neutral-400 uppercase tracking-widest text-center">
                        <span class="flex items-center space-x-1.5">
                            <span class="text-[#c5a880]">&check;</span>
                            <span>Complimentary Global Delivery</span>
                        </span>
                        <span class="flex items-center space-x-1.5">
                            <span class="text-[#c5a880]">&check;</span>
                            <span>Authenticity Guaranteed</span>
                        </span>
                    </div>
                </div>
            </form>

            <!-- Accordion Details -->
            <div class="mt-10 border-t border-white/10 divide-y divide-white/10 text-xs">
                <div>
                    <button 
                        type="button"
                        @click="openSection = (openSection === 'details' ? null : 'details')"
                        class="w-full py-4 flex justify-between items-center uppercase tracking-widest text-left text-white"
                    >
                        <span>Artisanal Tailoring &amp; Fit</span>
                        <span x-text="openSection === 'details' ? '&minus;' : '+'" class="text-neutral-400 text-base"></span>
                    </button>
                    <div x-show="openSection === 'details'" x-collapse class="pb-4 text-neutral-400 space-y-1.5 leading-relaxed font-light">
                        <p>&bull; Relaxed, architectural silhouette engineered for fluid drape.</p>
                        <p>&bull; Hand-stitched lapels and natural horn button closures.</p>
                        <p>&bull; Model is 179cm / 5'10" and wears a size S.</p>
                    </div>
                </div>

                <div>
                    <button 
                        type="button"
                        @click="openSection = (openSection === 'shipping' ? null : 'shipping')"
                        class="w-full py-4 flex justify-between items-center uppercase tracking-widest text-left text-white"
                    >
                        <span>Delivery &amp; Complimentary Returns</span>
                        <span x-text="openSection === 'shipping' ? '&minus;' : '+'" class="text-neutral-400 text-base"></span>
                    </button>
                    <div x-show="openSection === 'shipping'" x-collapse class="pb-4 text-neutral-400 space-y-1.5 leading-relaxed font-light">
                        <p>&bull; Express courier delivery (2-4 business days worldwide).</p>
                        <p>&bull; Signature garment bag and cedar hanger included with every order.</p>
                        <p>&bull; Complimentary pickup for exchanges within 30 days.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($related->isNotEmpty())
        <div class="mt-28 pt-16 border-t border-white/10">
            <div class="mb-12">
                <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2 font-medium">Curated Pairing</span>
                <h3 class="font-serif text-2xl md:text-3xl text-white font-light">Complete the Silhouette</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($related as $rel)
                    <div class="group relative flex flex-col">
                        <div class="relative aspect-[3/4] w-full overflow-hidden bg-neutral-900 rounded-sm">
                            <a href="{{ route('product.show', $rel->slug) }}">
                                <img src="{{ $rel->image }}" alt="{{ $rel->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            </a>
                        </div>
                        <div class="mt-4 flex justify-between items-baseline">
                            <div>
                                <a href="{{ route('product.show', $rel->slug) }}">
                                    <h4 class="font-serif text-base text-neutral-100 group-hover:text-[#c5a880] transition-colors">{{ $rel->name }}</h4>
                                </a>
                                <p class="text-xs text-neutral-500 mt-0.5">{{ $rel->material }}</p>
                            </div>
                            <span class="text-sm text-neutral-300">${{ number_format($rel->price, 0) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
