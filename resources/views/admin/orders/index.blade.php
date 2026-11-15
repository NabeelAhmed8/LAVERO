@extends('admin.layout')

@section('title', 'Client Orders — LAVÉRO CMS')

@section('admin_content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
        <div>
            <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-1">Maison Acquisitions</span>
            <h1 class="font-serif text-2xl sm:text-3xl text-white font-light uppercase tracking-wide">
                Client Orders &amp; Courier Tracking ({{ $orders->total() }})
            </h1>
        </div>
    </div>

    <!-- Dynamic Status Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2 text-xs">
        <a 
            href="{{ route('admin.orders') }}" 
            class="px-3.5 py-1.5 rounded uppercase tracking-wider transition-colors {{ empty($currentStatus) ? 'bg-[#c5a880] text-black font-semibold' : 'bg-white/5 text-neutral-400 hover:text-white hover:bg-white/10' }}"
        >
            All Orders ({{ $counts['all'] }})
        </a>
        <a 
            href="{{ route('admin.orders', ['status' => 'processing']) }}" 
            class="px-3.5 py-1.5 rounded uppercase tracking-wider transition-colors {{ $currentStatus === 'processing' ? 'bg-amber-400 text-black font-semibold' : 'bg-white/5 text-neutral-400 hover:text-white hover:bg-white/10' }}"
        >
            Processing ({{ $counts['processing'] }})
        </a>
        <a 
            href="{{ route('admin.orders', ['status' => 'shipped']) }}" 
            class="px-3.5 py-1.5 rounded uppercase tracking-wider transition-colors {{ $currentStatus === 'shipped' ? 'bg-sky-400 text-black font-semibold' : 'bg-white/5 text-neutral-400 hover:text-white hover:bg-white/10' }}"
        >
            Shipped ({{ $counts['shipped'] }})
        </a>
        <a 
            href="{{ route('admin.orders', ['status' => 'delivered']) }}" 
            class="px-3.5 py-1.5 rounded uppercase tracking-wider transition-colors {{ $currentStatus === 'delivered' ? 'bg-emerald-400 text-black font-semibold' : 'bg-white/5 text-neutral-400 hover:text-white hover:bg-white/10' }}"
        >
            Delivered ({{ $counts['delivered'] }})
        </a>
        <a 
            href="{{ route('admin.orders', ['status' => 'cancelled']) }}" 
            class="px-3.5 py-1.5 rounded uppercase tracking-wider transition-colors {{ $currentStatus === 'cancelled' ? 'bg-rose-400 text-black font-semibold' : 'bg-white/5 text-neutral-400 hover:text-white hover:bg-white/10' }}"
        >
            Cancelled ({{ $counts['cancelled'] }})
        </a>
    </div>

    <!-- Orders Dossier List (No Horizontal Scroll) -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div 
                x-data="{
                    status: '{{ $order->fulfillment_status }}',
                    tracking: '{{ addslashes($order->tracking_number ?? '') }}',
                    isSaving: false,
                    savedMessage: '',
                    async updateStatus() {
                        this.isSaving = true;
                        try {
                            const res = await fetch('{{ route('admin.orders.updateStatus', $order) }}', {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    fulfillment_status: this.status,
                                    tracking_number: this.tracking
                                })
                            });
                            if (res.ok) {
                                this.savedMessage = 'Saved';
                                setTimeout(() => this.savedMessage = '', 2500);
                            }
                        } catch (e) {
                            console.error(e);
                        } finally {
                            this.isSaving = false;
                        }
                    }
                }"
                class="rounded-lg bg-[#0e0e0e] border border-white/10 hover:border-white/20 transition-all overflow-hidden"
            >
                <!-- Card Header: Order Meta, Payment & Total -->
                <div class="px-5 py-3.5 bg-black/60 border-b border-white/10 flex flex-wrap items-center justify-between gap-3 text-xs">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="font-mono text-white text-sm font-semibold tracking-wide">
                            #{{ $order->order_number }}
                        </span>
                        <span class="text-neutral-600">&bull;</span>
                        <span class="text-neutral-400">
                            {{ $order->created_at->format('M d, Y · H:i') }} UTC
                        </span>
                        <span class="px-2.5 py-0.5 text-[10px] uppercase tracking-wider rounded font-medium border {{ $order->payment_status === 'paid' ? 'bg-emerald-950/80 text-emerald-300 border-emerald-800' : 'bg-amber-950/80 text-amber-300 border-amber-800' }}">
                            {{ strtoupper($order->payment_status) }} ({{ strtoupper($order->payment_method) }})
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-neutral-400 uppercase tracking-widest">Total:</span>
                        <span class="font-serif text-base sm:text-lg text-[#c5a880] font-medium">
                            ${{ number_format($order->total, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Card Body: 3 Balanced Columns with Clean Alignment -->
                <div class="p-5 grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
                    <!-- 1. Client & Destination -->
                    <div class="lg:col-span-4 space-y-2">
                        <span class="text-[10px] uppercase tracking-[0.25em] text-[#c5a880] font-semibold block">
                            Client Details
                        </span>
                        <div class="bg-black/30 p-3.5 rounded border border-white/5 space-y-1.5 text-xs">
                            <p class="text-white font-medium text-sm">{{ $order->customer_name }}</p>
                            <p class="text-neutral-400 font-mono text-[11px] truncate">{{ $order->email }}</p>
                            <div class="pt-2 mt-1 border-t border-white/5 text-neutral-300">
                                <span class="text-[10px] uppercase tracking-wider text-neutral-500 block mb-0.5">Shipping Destination</span>
                                <p class="text-xs leading-relaxed text-neutral-300">{{ $order->address }}</p>
                                <p class="text-[11px] text-neutral-400">{{ $order->city }}, {{ $order->country }} ({{ $order->postal_code }})</p>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Pieces Acquired -->
                    <div class="lg:col-span-4 space-y-2">
                        <span class="text-[10px] uppercase tracking-[0.25em] text-neutral-400 font-semibold block">
                            Pieces Acquired ({{ $order->items->sum('quantity') }})
                        </span>
                        <div class="bg-black/30 p-3.5 rounded border border-white/5 space-y-2 max-h-48 overflow-y-auto pr-1">
                            @foreach($order->items as $item)
                                <div class="flex items-center justify-between text-xs py-1 border-b border-white/5 last:border-0">
                                    <div class="min-w-0 pr-2">
                                        <span class="text-white block truncate">{{ $item->product_name }}</span>
                                        <span class="text-[11px] text-neutral-400">Size: {{ $item->size }} &times; Qty: {{ $item->quantity }}</span>
                                    </div>
                                    <span class="text-neutral-300 font-mono text-[11px] shrink-0 font-medium">
                                       ${{ number_format($item->price * $item->quantity, 2) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- 3. Tracking & Status Controls -->
                    <div class="lg:col-span-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] uppercase tracking-[0.25em] text-[#c5a880] font-semibold block">
                                Tracking &amp; Status
                            </span>
                            <span x-show="savedMessage" x-cloak class="text-emerald-400 text-[10px] font-mono lowercase">
                                &check; <span x-text="savedMessage"></span>
                            </span>
                        </div>
                        
                        <form 
                            action="{{ route('admin.orders.updateStatus', $order) }}" 
                            method="POST" 
                            @submit.prevent="updateStatus"
                            class="bg-black/30 p-3.5 rounded border border-white/5 space-y-3"
                        >
                            @csrf
                            @method('PUT')
                            
                            <div>
                                <label for="fulfillment_status_{{ $order->id }}" class="text-[10px] uppercase tracking-wider text-neutral-400 block mb-1">
                                    Fulfillment Status
                                </label>
                                <select 
                                    id="fulfillment_status_{{ $order->id }}"
                                    name="fulfillment_status" 
                                    x-model="status"
                                    @change="updateStatus" 
                                    class="w-full bg-neutral-900 border text-xs rounded px-3 py-2 focus:outline-none cursor-pointer font-medium transition-colors"
                                    :class="{
                                        'text-emerald-400 border-emerald-800 bg-emerald-950/20': status === 'delivered',
                                        'text-sky-400 border-sky-800 bg-sky-950/20': status === 'shipped',
                                        'text-amber-400 border-amber-800 bg-amber-950/20': status === 'processing',
                                        'text-rose-400 border-rose-800 bg-rose-950/20': status === 'cancelled'
                                    }"
                                >
                                    <option value="processing">• Processing (Atelier Prep)</option>
                                    <option value="shipped">• Shipped (Dispatched)</option>
                                    <option value="delivered">• Delivered (To Client)</option>
                                    <option value="cancelled">• Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label for="tracking_number_{{ $order->id }}" class="text-[10px] uppercase tracking-wider text-neutral-400 block mb-1">
                                    Courier Tracking #
                                </label>
                                <div class="flex items-center gap-1.5">
                                    <input 
                                        type="text" 
                                        id="tracking_number_{{ $order->id }}"
                                        name="tracking_number" 
                                        autocomplete="off"
                                        x-model="tracking" 
                                        placeholder="e.g. DHL-981240" 
                                        class="flex-1 min-w-0 bg-neutral-900 border border-white/10 rounded px-2.5 py-1.5 text-xs text-white placeholder-neutral-600 focus:border-[#c5a880] focus:outline-none"
                                    >
                                    <button 
                                        type="submit" 
                                        :disabled="isSaving"
                                        class="px-3 py-1.5 bg-white/10 hover:bg-[#c5a880] hover:text-black rounded text-[10px] uppercase tracking-wider font-semibold transition-colors cursor-pointer shrink-0 disabled:opacity-50"
                                    >
                                        <span x-show="!isSaving">Save</span>
                                        <span x-show="isSaving" x-cloak>&bull;&bull;&bull;</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-lg bg-[#0e0e0e] border border-white/10 p-12 text-center text-neutral-500">
                No client orders recorded for this filter.
            </div>
        @endforelse

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="pt-4 flex justify-center">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
