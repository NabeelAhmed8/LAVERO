@extends('admin.layout')

@section('title', 'Atelier Dashboard — LAVÉRO CMS')

@section('admin_content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-1">Maison Executive Overview</span>
            <h1 class="font-serif text-3xl sm:text-4xl text-white font-light uppercase tracking-wide">
                Atelier CMS Dashboard
            </h1>
        </div>
        <div class="flex items-center space-x-3">
            <a 
                href="{{ route('admin.products.create') }}" 
                class="py-2.5 px-5 bg-white hover:bg-[#c5a880] text-black hover:text-white rounded text-xs uppercase tracking-widest font-semibold transition-colors flex items-center space-x-2"
            >
                <span>+</span>
                <span>New Creation</span>
            </a>
            <a 
                href="{{ route('shop.index') }}" 
                target="_blank"
                class="py-2.5 px-4 border border-white/20 hover:border-white text-neutral-300 hover:text-white rounded text-xs uppercase tracking-widest transition-colors"
            >
                Browse Shop
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Sales -->
        <div class="p-6 rounded bg-[#0f0f0f] border border-white/10">
            <span class="text-xs uppercase tracking-widest text-neutral-400 block mb-2">Total Settled Sales</span>
            <span class="font-serif text-3xl text-white font-medium">${{ number_format($totalSales, 2) }}</span>
            <span class="block text-[11px] text-[#c5a880] mt-2">Stripe & Direct Orders</span>
        </div>

        <!-- Orders -->
        <div class="p-6 rounded bg-[#0f0f0f] border border-white/10">
            <span class="text-xs uppercase tracking-widest text-neutral-400 block mb-2">Client Orders</span>
            <span class="font-serif text-3xl text-white font-medium">{{ $totalOrders }}</span>
            <span class="block text-[11px] text-neutral-400 mt-2">Bespoke Courier Shipments</span>
        </div>

        <!-- Products -->
        <div class="p-6 rounded bg-[#0f0f0f] border border-white/10">
            <span class="text-xs uppercase tracking-widest text-neutral-400 block mb-2">Active Creations</span>
            <span class="font-serif text-3xl text-white font-medium">{{ $totalProducts }}</span>
            <span class="block text-[11px] text-neutral-400 mt-2">Live in Catalogue</span>
        </div>

        <!-- Inquiries -->
        <div class="p-6 rounded bg-[#0f0f0f] border border-white/10">
            <span class="text-xs uppercase tracking-widest text-neutral-400 block mb-2">Private Inquiries</span>
            <span class="font-serif text-3xl text-white font-medium">{{ $totalInquiries }}</span>
            <span class="block text-[11px] text-neutral-400 mt-2">Salon Fitting Bookings</span>
        </div>
    </div>

    <!-- Recent Orders & Recent Inquiries Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left: Recent Orders -->
        <div class="lg:col-span-8 rounded bg-[#0f0f0f] border border-white/10 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="font-serif text-xl text-white font-light uppercase tracking-wider">
                    Recent Client Orders
                </h3>
                <a href="{{ route('admin.orders') }}" class="text-xs uppercase tracking-widest text-[#c5a880] hover:underline">
                    View All Orders &rarr;
                </a>
            </div>

            @if($recentOrders->isEmpty())
                <p class="text-xs text-neutral-500 py-8 text-center">No orders registered yet.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="text-neutral-500 uppercase tracking-widest border-b border-white/5 pb-2">
                            <tr>
                                <th class="pb-3 font-normal">Order #</th>
                                <th class="pb-3 font-normal">Client</th>
                                <th class="pb-3 font-normal">Items</th>
                                <th class="pb-3 font-normal">Total</th>
                                <th class="pb-3 font-normal">Status</th>
                                <th class="pb-3 font-normal">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-neutral-300">
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="py-3 font-mono text-white">{{ $order->order_number }}</td>
                                    <td class="py-3">{{ $order->customer_name }}</td>
                                    <td class="py-3 text-neutral-400">{{ $order->items->count() }} piece(s)</td>
                                    <td class="py-3 text-white font-medium">${{ number_format($order->total, 2) }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 text-[10px] uppercase tracking-wider rounded
                                            {{ $order->fulfillment_status === 'delivered' ? 'bg-emerald-950 text-emerald-300 border border-emerald-800' : '' }}
                                            {{ $order->fulfillment_status === 'shipped' ? 'bg-sky-950 text-sky-300 border border-sky-800' : '' }}
                                            {{ $order->fulfillment_status === 'processing' ? 'bg-amber-950 text-amber-300 border border-amber-800' : '' }}
                                            {{ $order->fulfillment_status === 'cancelled' ? 'bg-rose-950 text-rose-300 border border-rose-800' : '' }}"
                                        >
                                            {{ $order->fulfillment_status }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-neutral-500">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Right: Recent Concierge Inquiries -->
        <div class="lg:col-span-4 rounded bg-[#0f0f0f] border border-white/10 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h3 class="font-serif text-xl text-white font-light uppercase tracking-wider">
                    Salon Inquiries
                </h3>
                <a href="{{ route('admin.inquiries') }}" class="text-xs uppercase tracking-widest text-[#c5a880] hover:underline">
                    All &rarr;
                </a>
            </div>

            @if($recentInquiries->isEmpty())
                <p class="text-xs text-neutral-500 py-8 text-center">No fitting inquiries received.</p>
            @else
                <div class="space-y-4">
                    @foreach($recentInquiries as $inquiry)
                        <div class="p-3.5 rounded bg-black/40 border border-white/5 space-y-1.5">
                            <div class="flex justify-between items-start text-xs">
                                <span class="text-white font-medium">{{ $inquiry->name }}</span>
                                <span class="text-[10px] text-[#c5a880] uppercase">{{ $inquiry->salon }}</span>
                            </div>
                            <p class="text-xs text-neutral-400 line-clamp-2">{{ $inquiry->message }}</p>
                            <span class="text-[10px] text-neutral-600 block">{{ $inquiry->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
