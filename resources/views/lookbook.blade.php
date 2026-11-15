@extends('layouts.app')

@section('title', 'Runway Lookbook Autumn / Winter — LAVÉRO')

@section('content')
<div class="pt-28 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="text-center max-w-2xl mx-auto mb-20" data-motion="fade-up">
        <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
            Runway Archives
        </span>
        <h1 class="font-serif text-4xl md:text-6xl text-white font-light uppercase tracking-wide">
            Autumn / Winter Lookbook
        </h1>
        <p class="text-sm text-neutral-400 mt-4 leading-relaxed font-light">
            An exploration of raw volume, tailored precision, and monochromatic architecture photographed at the Grand Palais Éphémère, Paris.
        </p>
    </div>

    <!-- Lookbook Editorial Grid from Database -->
    <div class="space-y-32">
        @forelse($featuredProducts as $index => $product)
            @php
                $isEven = $index % 2 !== 0;
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center" data-motion="fade-up">
                <div class="lg:col-span-7 aspect-[4/5] rounded overflow-hidden bg-neutral-900 border border-white/5 {{ $isEven ? 'order-1 lg:order-2' : '' }}">
                    <img 
                        src="{{ $product->image }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover hover:scale-103 transition-transform duration-1000"
                    >
                </div>
                <div class="lg:col-span-5 space-y-6 {{ $isEven ? 'order-2 lg:order-1' : '' }}">
                    <span class="font-serif text-5xl text-neutral-600 font-light">{{ sprintf('%02d', $index + 1) }}</span>
                    <h3 class="font-serif text-3xl text-white font-light">{{ $product->name }}</h3>
                    <p class="text-sm text-neutral-400 leading-relaxed font-light">
                        {{ $product->description }}
                    </p>
                    <div class="pt-4 border-t border-white/10 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-xs uppercase tracking-widest text-neutral-400 block">{{ $product->material }}</span>
                            <span class="font-serif text-2xl text-white font-medium">${{ number_format($product->price, 0) }}</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button 
                                type="button" 
                                @click="$store.cart.add({{ $product->id }}, '{{ $product->sizes[0] ?? 'M' }}', 1)" 
                                class="py-2.5 px-5 bg-white hover:bg-[#c5a880] text-black hover:text-white rounded text-xs uppercase tracking-[0.2em] font-semibold transition-all duration-300 shadow-lg cursor-pointer flex items-center space-x-2 active:scale-95"
                            >
                                <svg class="w-3.5 h-3.5 stroke-[1.8]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Add to Bag</span>
                            </button>
                            <a href="{{ route('product.show', $product->slug) }}" class="text-xs uppercase tracking-widest text-[#c5a880] hover:underline">
                                View Details &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 text-neutral-500">
                <p class="font-serif text-2xl text-neutral-400">No featured garments found in lookbook archive.</p>
                <a href="{{ route('shop.index') }}" class="text-xs uppercase tracking-widest text-[#c5a880] mt-4 inline-block hover:underline">
                    View Complete Collection &rarr;
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
