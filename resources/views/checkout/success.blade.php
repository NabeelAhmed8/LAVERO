@extends('layouts.app')

@section('title', 'Order Confirmed — LAVÉRO')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 md:px-16 max-w-4xl mx-auto text-center" data-motion="fade-up">
    <!-- Success Monogram -->
    <div class="w-16 h-16 rounded-full bg-[#c5a880]/20 border border-[#c5a880] text-[#c5a880] mx-auto flex items-center justify-center mb-6">
        <svg class="w-8 h-8 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
        </svg>
    </div>

    <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
        Order Confirmed &middot; Maison LAVÉRO
    </span>

    <h1 class="font-serif text-3xl md:text-5xl text-white font-light uppercase tracking-wide">
        Thank You, {{ $order['customer_name'] }}
    </h1>

    <p class="text-sm text-neutral-400 mt-4 leading-relaxed font-light max-w-lg mx-auto">
        Your bespoke order has been assigned to our Parisian atelier. A private courier tracking dossier will be dispatched to <span class="text-white">{{ $order['email'] }}</span>.
    </p>

    <!-- Order Receipt Box -->
    <div class="mt-12 bg-neutral-950 p-8 rounded border border-white/10 text-left max-w-2xl mx-auto space-y-6">
        <div class="flex justify-between items-center pb-4 border-b border-white/10 text-xs uppercase tracking-widest text-neutral-400">
            <span>Dossier Reference</span>
            <span class="text-white font-mono font-medium">{{ $order['order_number'] }}</span>
        </div>

        <div class="flex justify-between items-center pb-4 border-b border-white/10 text-xs uppercase tracking-widest text-neutral-400">
            <span>Payment Method</span>
            <span class="text-[#c5a880] font-medium tracking-wide">{{ $order['payment_method'] ?? 'Cash on Delivery' }}</span>
        </div>

        <div class="space-y-4">
            @foreach($order['items'] as $item)
                <div class="flex justify-between items-center text-xs">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $item['image'] }}" class="w-12 h-16 object-cover rounded bg-neutral-900">
                        <div>
                            <p class="font-serif text-sm text-white">{{ $item['name'] }}</p>
                            <p class="text-neutral-400">Size: {{ $item['size'] }} &middot; Qty: {{ $item['quantity'] }}</p>
                        </div>
                    </div>
                    <span class="text-white font-medium">
                        ${{ number_format($item['price'] * $item['quantity'], 2) }}
                    </span>
                </div>
            @endforeach
        </div>

        <div class="pt-4 border-t border-white/10 flex justify-between items-baseline">
            <span class="text-xs uppercase tracking-widest text-neutral-400">Total Paid (USD)</span>
            <span class="font-serif text-xl text-[#c5a880] font-semibold">
                ${{ number_format($order['subtotal'], 2) }}
            </span>
        </div>
    </div>

    <div class="mt-12">
        <a 
            href="{{ route('home') }}" 
            data-magnetic
            class="inline-block px-8 py-4 bg-white text-black text-xs uppercase tracking-[0.25em] font-semibold rounded hover:bg-[#c5a880] hover:text-white transition-colors"
        >
            Return to Homepage
        </a>
    </div>
</div>
@endsection
