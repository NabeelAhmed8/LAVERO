@extends('admin.layout')

@section('title', 'Manage Creations — LAVÉRO CMS')

@section('admin_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-1">Couture Inventory</span>
            <h1 class="font-serif text-2xl sm:text-3xl text-white font-light uppercase tracking-wide">
                Creations &amp; Catalog ({{ $products->total() }})
            </h1>
        </div>
        <a 
            href="{{ route('admin.products.create') }}" 
            class="py-2.5 px-5 bg-white hover:bg-[#c5a880] text-black hover:text-white rounded text-xs uppercase tracking-widest font-semibold transition-colors flex items-center space-x-2 self-start sm:self-auto cursor-pointer shadow"
        >
            <span>+</span>
            <span>Add New Creation</span>
        </a>
    </div>

    <!-- Catalog Container (Zero Horizontal Scroll & Dynamic Fit) -->
    <div class="rounded-lg bg-[#0f0f0f] border border-white/10 overflow-hidden">
        <!-- Desktop Table (lg screens and above: Dynamic auto-fit, zero overlap) -->
        <div class="hidden lg:block w-full">
            <table class="w-full text-left text-xs">
                <thead class="bg-black/60 text-neutral-400 uppercase tracking-widest border-b border-white/10 text-[11px]">
                    <tr>
                        <th class="py-3.5 px-4 font-normal">Piece</th>
                        <th class="py-3.5 px-3 font-normal whitespace-nowrap">Category</th>
                        <th class="py-3.5 px-3 font-normal text-right whitespace-nowrap">Price</th>
                        <th class="py-3.5 px-3 font-normal text-center whitespace-nowrap">Tag</th>
                        <th class="py-3.5 px-3 font-normal text-center whitespace-nowrap">Status</th>
                        <th class="py-3.5 px-4 font-normal text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-neutral-300">
                    @forelse($products as $product)
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <!-- Piece Name & Thumbnail (Flexible Fluid Column) -->
                            <td class="py-3 px-4 align-middle">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-9 h-12 object-cover rounded bg-neutral-900 shrink-0 border border-white/10">
                                    <div class="min-w-0 pr-2">
                                        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="font-serif text-sm text-white hover:text-[#c5a880] transition-colors truncate block max-w-xs xl:max-w-md" title="{{ $product->name }}">
                                            {{ $product->name }}
                                        </a>
                                        <span class="block text-[11px] text-neutral-500 mt-0.5 truncate max-w-xs xl:max-w-md">{{ $product->material }}</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Category -->
                            <td class="py-3 px-3 align-middle text-neutral-400 whitespace-nowrap text-xs">
                                {{ $product->category->name ?? 'Uncategorized' }}
                            </td>

                            <!-- Price (Right-Aligned Precision) -->
                            <td class="py-3 px-3 align-middle text-right font-mono text-sm text-[#c5a880] font-semibold whitespace-nowrap">
                                ${{ number_format($product->price, 2) }}
                            </td>

                            <!-- Tag (Centered Runway Badge with Dedicated Fit) -->
                            <td class="py-3 px-3 align-middle text-center whitespace-nowrap">
                                @if($product->tag)
                                    <span class="inline-block px-2.5 py-0.5 text-[10px] uppercase tracking-wider rounded-full bg-neutral-900 border border-[#c5a880]/40 text-[#c5a880] font-medium">
                                        {{ $product->tag }}
                                    </span>
                                @else
                                    <span class="text-neutral-600 text-xs">—</span>
                                @endif
                            </td>

                            <!-- Stock Status & Quantity Badge -->
                            <td class="py-3 px-3 align-middle text-center whitespace-nowrap">
                                @php
                                    $badge = $product->stock_badge;
                                @endphp
                                @if($badge['color'] === 'rose')
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded text-[10px] uppercase tracking-wider bg-rose-950/80 text-rose-300 border border-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400 shrink-0"></span>
                                        <span>Out of Stock</span>
                                    </span>
                                @elseif($badge['color'] === 'amber')
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded text-[10px] uppercase tracking-wider bg-amber-950/80 text-amber-300 border border-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 shrink-0"></span>
                                        <span>Low ({{ $product->stock_quantity }})</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded text-[10px] uppercase tracking-wider bg-emerald-950/80 text-emerald-300 border border-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                                        <span>{{ $product->stock_quantity }} in stock</span>
                                    </span>
                                @endif
                            </td>

                            <!-- Actions (Edit & Delete without Overlap) -->
                            <td class="py-3 px-4 align-middle text-right whitespace-nowrap">
                                <div class="inline-flex items-center justify-end space-x-2.5">
                                    <a 
                                        href="{{ route('admin.products.edit', $product) }}" 
                                        class="text-neutral-400 hover:text-[#c5a880] transition-colors text-xs font-medium uppercase tracking-wider"
                                    >
                                        Edit
                                    </a>
                                    <span class="text-white/20 select-none">&middot;</span>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Remove {{ addslashes($product->name) }} from the catalogue?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-neutral-500 hover:text-rose-400 transition-colors cursor-pointer text-xs font-medium uppercase tracking-wider">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-neutral-500">
                                No creations catalogued yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile & Tablet Cards (under lg screens: 100% width, zero horizontal scroll, zero overlap) -->
        <div class="lg:hidden divide-y divide-white/5">
            @forelse($products as $product)
                <div class="p-4 space-y-3">
                    <div class="flex items-start gap-3">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-14 h-20 object-cover rounded bg-neutral-900 shrink-0 border border-white/10">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="font-serif text-sm text-white font-medium truncate">
                                    {{ $product->name }}
                                </h3>
                                <span class="font-mono text-sm text-[#c5a880] font-semibold shrink-0">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-neutral-400 mt-0.5 truncate">{{ $product->category->name ?? 'Uncategorized' }} &middot; {{ $product->material }}</p>
                            
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                @if($product->tag)
                                    <span class="inline-block px-2 py-0.5 text-[9px] uppercase tracking-wider rounded-full bg-neutral-900 border border-[#c5a880]/40 text-[#c5a880] font-medium">
                                        {{ $product->tag }}
                                    </span>
                                @endif
                                
                                @php
                                    $badge = $product->stock_badge;
                                @endphp
                                @if($badge['color'] === 'rose')
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[9px] uppercase tracking-wider bg-rose-950/80 text-rose-300 border border-rose-800">
                                        <span>Out of Stock</span>
                                    </span>
                                @elseif($badge['color'] === 'amber')
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[9px] uppercase tracking-wider bg-amber-950/80 text-amber-300 border border-amber-800">
                                        <span>Low Stock ({{ $product->stock_quantity }} left)</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded text-[9px] uppercase tracking-wider bg-emerald-950/80 text-emerald-300 border border-emerald-800">
                                        <span>{{ $product->stock_quantity }} in stock</span>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Bar -->
                    <div class="pt-2 border-t border-white/5 flex items-center justify-end space-x-4 text-xs">
                        <a 
                            href="{{ route('admin.products.edit', $product) }}" 
                            class="text-neutral-400 hover:text-[#c5a880] uppercase tracking-wider transition-colors"
                        >
                            Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Remove {{ addslashes($product->name) }} from the catalogue?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-neutral-600 hover:text-rose-400 uppercase tracking-wider transition-colors cursor-pointer">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-neutral-500 text-xs">
                    No creations catalogued yet.
                </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
            <div class="p-4 border-t border-white/10 flex justify-center">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
