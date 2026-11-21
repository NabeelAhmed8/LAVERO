@extends('layouts.app')

@section('title', 'Client Dossier & Profile — LAVÉRO')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto" x-data="{ activeTab: '{{ request()->query('tab', request()->has('page') ? 'orders' : 'overview') }}' }" data-motion="fade-up">
    <!-- Header -->
    <div class="mb-10 pb-6 border-b border-white/10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
                Confidential Client Registry
            </span>
            <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl text-white font-light uppercase tracking-wide">
                Client Dossier
            </h1>
        </div>
        <div class="flex flex-wrap items-center gap-3 sm:gap-4">
            <div class="px-3.5 py-1.5 rounded-full bg-neutral-900 border border-white/10 text-xs uppercase tracking-wider text-neutral-300">
                Client ID: <span class="font-mono text-white font-semibold">LAV-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            @if($user->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-[#c5a880]/40 bg-[#c5a880]/10 hover:bg-[#c5a880] hover:text-black text-[#c5a880] text-xs uppercase tracking-widest rounded transition-all duration-200">
                    Atelier CMS
                </a>
            @endif
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 border border-white/15 hover:border-red-400/50 text-neutral-300 hover:text-red-400 text-xs uppercase tracking-wider rounded transition-colors cursor-pointer">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Error Alerts -->
    @if($errors->any())
        <div class="mb-8 p-4 rounded-lg bg-red-950/40 border border-red-500/40 text-red-300 text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-2">
                    <span class="text-red-400">&bull;</span> {{ $error }}
                </p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-start">
        <!-- 1. LEFT SIDEBAR: Client Identification & Navigation -->
        <aside class="lg:col-span-4 space-y-6">
            <!-- Client Identification Card -->
            <div class="bg-neutral-950 p-6 rounded-xl border border-white/10 space-y-5">
                <div class="flex items-center space-x-4 pb-5 border-b border-white/10">
                    <div class="w-14 h-14 shrink-0 rounded-full bg-neutral-900 border border-[#c5a880]/60 text-[#c5a880] font-serif text-2xl flex items-center justify-center font-light uppercase shadow-inner">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="font-serif text-lg text-white leading-snug truncate">{{ $user->name }}</h2>
                        <p class="text-xs text-neutral-400 mt-0.5 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-neutral-400 uppercase tracking-wider">Atelier Tier</span>
                        @if($user->is_admin)
                            <span class="px-2 py-0.5 rounded bg-[#c5a880]/20 text-[#c5a880] uppercase tracking-wider font-semibold text-[11px] border border-[#c5a880]/40">
                                Atelier Director
                            </span>
                        @elseif(($totalOrdersCount ?? $orders->count()) >= 3)
                            <span class="px-2 py-0.5 rounded bg-amber-500/15 text-[#c5a880] uppercase tracking-wider font-semibold text-[11px] border border-[#c5a880]/40">
                                Haute Couture Patron
                            </span>
                        @elseif(($totalOrdersCount ?? $orders->count()) >= 1)
                            <span class="px-2 py-0.5 rounded bg-white/10 text-white uppercase tracking-wider font-semibold text-[11px]">
                                Private Client VIP
                            </span>
                        @else
                            <span class="px-2 py-0.5 rounded bg-white/5 text-neutral-300 uppercase tracking-wider font-semibold text-[11px]">
                                Private Client
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-white/5 text-neutral-400">
                        <span class="uppercase tracking-wider">Member Since</span>
                        <span class="font-mono text-neutral-300">{{ $user->created_at ? $user->created_at->format('M Y') : 'Active' }}</span>
                    </div>

                    <div class="flex items-center justify-between pt-2 border-t border-white/5 text-neutral-400">
                        <span class="uppercase tracking-wider">Total Commissions</span>
                        <span class="font-mono text-[#c5a880] font-semibold">{{ $totalOrdersCount ?? $orders->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Tab Pills -->
            <nav class="bg-neutral-950 p-2 sm:p-2.5 rounded-xl border border-white/10 flex lg:flex-col overflow-x-auto lg:overflow-visible gap-1.5 text-xs uppercase tracking-widest font-medium no-scrollbar">
                <button 
                    type="button"
                    @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-between gap-3 cursor-pointer"
                >
                    <span>1. Identification</span>
                    <span class="hidden lg:inline text-[11px] opacity-70">&rarr;</span>
                </button>

                <button 
                    type="button"
                    @click="activeTab = 'address'" 
                    :class="activeTab === 'address' ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-between gap-3 cursor-pointer"
                >
                    <span>2. Delivery Address</span>
                    <span class="hidden lg:inline text-[11px] opacity-70">&rarr;</span>
                </button>

                <button 
                    type="button"
                    @click="activeTab = 'payment'" 
                    :class="activeTab === 'payment' ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-between gap-3 cursor-pointer"
                >
                    <span>3. Payment Methods</span>
                    <span class="hidden lg:inline text-[11px] opacity-70">&rarr;</span>
                </button>

                <button 
                    type="button"
                    @click="activeTab = 'orders'" 
                    :class="activeTab === 'orders' ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-between gap-3 cursor-pointer"
                >
                    <span class="flex items-center gap-2">
                        <span>4. Order Dossiers</span>
                        @if(($totalOrdersCount ?? $orders->count()) > 0)
                            <span :class="activeTab === 'orders' ? 'bg-black text-[#c5a880]' : 'bg-[#c5a880]/20 text-[#c5a880]'" class="px-1.5 py-0.2 rounded-full text-[10px] font-mono">
                                {{ $totalOrdersCount ?? $orders->count() }}
                            </span>
                        @endif
                    </span>
                    <span class="hidden lg:inline text-[11px] opacity-70">&rarr;</span>
                </button>

                <button 
                    type="button"
                    @click="activeTab = 'security'" 
                    :class="activeTab === 'security' ? 'bg-[#c5a880] text-black font-semibold shadow-sm' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex items-center justify-between gap-3 cursor-pointer"
                >
                    <span>5. Account Security</span>
                    <span class="hidden lg:inline text-[11px] opacity-70">&rarr;</span>
                </button>
            </nav>
        </aside>

        <!-- 2. RIGHT CONTENT AREA: Dynamic Tab Views -->
        <main class="lg:col-span-8">
            <!-- TAB 1: IDENTIFICATION & GENERAL PROFILE -->
            <div x-show="activeTab === 'overview'" x-cloak class="bg-neutral-950 p-6 sm:p-8 rounded-xl border border-white/10 space-y-8">
                <div class="pb-4 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl text-white uppercase tracking-wider">Personal Identification</h2>
                        <p class="text-xs text-neutral-400 mt-1">Primary details registered for your bespoke commissions</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono {{ $user->email_verified_at ? 'bg-emerald-950/80 text-emerald-300 border border-emerald-500/40' : 'bg-amber-950/80 text-amber-300 border border-amber-500/40' }}">
                        <span>{{ $user->email_verified_at ? '✓ Authenticated' : 'Pending Verification' }}</span>
                    </span>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="profile_name" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Legal Name</label>
                            <input 
                                type="text" 
                                id="profile_name"
                                name="name" 
                                autocomplete="name"
                                value="{{ old('name', $user->name) }}" 
                                required
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                            >
                        </div>

                        <div>
                            <label for="profile_email" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Email Address</label>
                            <input 
                                type="email" 
                                id="profile_email"
                                name="email" 
                                autocomplete="email"
                                value="{{ old('email', $user->email) }}" 
                                required
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                            >
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                        <span class="text-[11px] text-neutral-500">All dossier changes are recorded confidentially.</span>
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded-lg text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Save Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: DELIVERY ADDRESSES -->
            <div x-show="activeTab === 'address'" x-cloak class="bg-neutral-950 p-6 sm:p-8 rounded-xl border border-white/10 space-y-8">
                <div class="pb-4 border-b border-white/10">
                    <h2 class="font-serif text-xl sm:text-2xl text-white uppercase tracking-wider">White-Glove Delivery Destination</h2>
                    <p class="text-xs text-neutral-400 mt-1">Default express shipping destination for physical garment deliveries</p>
                </div>

                @if($user->address)
                    <div class="p-5 rounded-lg bg-neutral-900/80 border border-white/15 flex justify-between items-start">
                        <div class="space-y-1.5 text-xs">
                            <span class="text-[10px] uppercase tracking-widest text-[#c5a880] font-semibold block">Primary Destination On File</span>
                            <p class="text-base font-serif text-white">{{ $user->address }}</p>
                            <p class="text-neutral-300 font-medium">{{ $user->city }}, {{ $user->postal_code }} &middot; {{ $user->country }}</p>
                        </div>
                        <span class="text-[10px] uppercase tracking-widest px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 rounded text-emerald-300 font-semibold">
                            Active
                        </span>
                    </div>
                @else
                    <div class="p-4 rounded-lg bg-neutral-900/40 border border-dashed border-white/15 text-xs text-neutral-400">
                        No delivery address currently registered. Please provide your residential destination below.
                    </div>
                @endif

                <form action="{{ route('profile.address') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="profile_address" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Street Address &amp; Suite</label>
                        <input 
                            type="text" 
                            id="profile_address"
                            name="address" 
                            autocomplete="street-address"
                            value="{{ old('address', $user->address) }}" 
                            required
                            placeholder="e.g. 742 Avenue Montaigne, Suite 4B"
                            class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label for="profile_city" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">City</label>
                            <input 
                                type="text" 
                                id="profile_city"
                                name="city" 
                                autocomplete="address-level2"
                                value="{{ old('city', $user->city) }}" 
                                required
                                placeholder="Paris"
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                            >
                        </div>

                        <div>
                            <label for="profile_country" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Country</label>
                            <input 
                                type="text" 
                                id="profile_country"
                                name="country" 
                                autocomplete="country-name"
                                value="{{ old('country', $user->country) }}" 
                                required
                                placeholder="France"
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                            >
                        </div>

                        <div>
                            <label for="profile_postal_code" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Postal Code</label>
                            <input 
                                type="text" 
                                id="profile_postal_code"
                                name="postal_code" 
                                autocomplete="postal-code"
                                value="{{ old('postal_code', $user->postal_code) }}" 
                                required
                                placeholder="75008"
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                            >
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex justify-end">
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded-lg text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Save Destination Address
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: PAYMENT METHODS -->
            <div x-show="activeTab === 'payment'" x-cloak class="bg-neutral-950 p-6 sm:p-8 rounded-xl border border-white/10 space-y-8">
                <div class="pb-4 border-b border-white/10">
                    <h2 class="font-serif text-xl sm:text-2xl text-white uppercase tracking-wider">Client Payment Methods</h2>
                    <p class="text-xs text-neutral-400 mt-1">Encrypted cards on file and private atelier wire invoicing protocols</p>
                </div>

                <!-- Existing Saved Card or Empty State -->
                @if($user->card_last_four)
                    <div class="p-6 rounded-xl bg-gradient-to-br from-neutral-900 via-neutral-900 to-neutral-950 border border-white/20 flex flex-col justify-between max-w-md min-h-[170px] shadow-lg">
                        <div class="flex justify-between items-start">
                            <span class="text-xs uppercase tracking-widest text-[#c5a880] font-semibold block">
                                {{ $user->card_brand ?? 'Visa Signature' }}
                            </span>
                            <span class="text-[10px] uppercase font-mono tracking-widest px-2 py-0.5 rounded bg-white/10 text-neutral-300">
                                Primary
                            </span>
                        </div>
                        <div>
                            <p class="font-mono text-xl sm:text-2xl text-white tracking-[0.25em] py-2">
                                &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; {{ $user->card_last_four }}
                            </p>
                            <div class="flex justify-between items-end text-[11px] text-neutral-400 pt-1">
                                <span>Cardholder: <strong class="text-white font-medium uppercase">{{ $user->name }}</strong></span>
                                <span class="font-mono text-[#c5a880]">Encrypted</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-6 rounded-xl border border-dashed border-white/15 bg-neutral-900/30 max-w-md text-xs text-neutral-400 space-y-2">
                        <p class="text-neutral-300 font-medium font-serif text-sm">No payment method stored</p>
                        <p>Save your card details below to facilitate rapid checkout during limited-quantity couture drops.</p>
                    </div>
                @endif

                <!-- Update Payment Method Form -->
                <form action="{{ route('profile.payment') }}" method="POST" class="space-y-6 pt-4 border-t border-white/10">
                    @csrf
                    @method('PUT')

                    <h3 class="text-xs uppercase tracking-widest text-neutral-300 font-medium">Add / Update Card on File</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="profile_card_number" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Card Number</label>
                            <input 
                                type="text" 
                                id="profile_card_number"
                                name="card_number" 
                                required
                                autocomplete="cc-number"
                                placeholder="4532 •••• •••• 8821" 
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors font-mono"
                            >
                        </div>
                        <div>
                            <label for="profile_card_brand" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Card Network / Type</label>
                            <select 
                                id="profile_card_brand"
                                name="card_brand" 
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors cursor-pointer"
                            >
                                <option value="Visa Signature Infinite" {{ $user->card_brand === 'Visa Signature Infinite' ? 'selected' : '' }}>Visa Signature Infinite</option>
                                <option value="Mastercard Black Edition" {{ $user->card_brand === 'Mastercard Black Edition' ? 'selected' : '' }}>Mastercard Black Edition</option>
                                <option value="American Express Centurion" {{ $user->card_brand === 'American Express Centurion' ? 'selected' : '' }}>American Express Centurion</option>
                                <option value="Mastercard World Elite" {{ $user->card_brand === 'Mastercard World Elite' ? 'selected' : '' }}>Mastercard World Elite</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <span class="text-[11px] text-neutral-500 font-mono">End-to-end tokenized via PCI-DSS standards.</span>
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded-lg text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Update Payment Card
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 4: ORDER & COMMISSION DOSSIERS (DYNAMIC) -->
            <div x-show="activeTab === 'orders'" x-cloak class="bg-neutral-950 p-6 sm:p-8 rounded-xl border border-white/10 space-y-8">
                <div class="pb-4 border-b border-white/10 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h2 class="font-serif text-xl sm:text-2xl text-white uppercase tracking-wider">Order History &amp; Commissions</h2>
                        <p class="text-xs text-neutral-400 mt-1">Track bespoke atelier tailoring and dispatch dossiers</p>
                    </div>
                    @if(($totalOrdersCount ?? $orders->count()) > 0)
                        <span class="text-xs uppercase tracking-widest text-[#c5a880] font-mono">
                            {{ $totalOrdersCount ?? $orders->count() }} Recorded {{ Str::plural('Dossier', $totalOrdersCount ?? $orders->count()) }}
                        </span>
                    @endif
                </div>

                @if($orders->isNotEmpty())
                    <!-- Summary Stats Bar -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pb-2">
                        <div class="bg-neutral-900/60 p-4 rounded-lg border border-white/10 text-xs">
                            <span class="text-neutral-400 uppercase tracking-widest block text-[10px]">Total Commissions</span>
                            <span class="text-lg font-serif text-white font-medium mt-1 block">{{ $totalOrdersCount ?? $orders->count() }}</span>
                        </div>
                        <div class="bg-neutral-900/60 p-4 rounded-lg border border-white/10 text-xs">
                            <span class="text-neutral-400 uppercase tracking-widest block text-[10px]">In Progress</span>
                            <span class="text-lg font-serif text-[#c5a880] font-medium mt-1 block">
                                {{ $activeOrdersCount ?? $orders->whereIn('fulfillment_status', ['processing', 'shipped'])->count() }} Active
                            </span>
                        </div>
                        <div class="bg-neutral-900/60 p-4 rounded-lg border border-white/10 text-xs">
                            <span class="text-neutral-400 uppercase tracking-widest block text-[10px]">Total Investment</span>
                            <span class="text-lg font-serif text-white font-medium mt-1 block">
                                ${{ number_format($totalLifetimeSpent ?? $orders->sum('total'), 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Highlighted Active Dispatch (Latest Order Tracker) -->
                    @php
                        $latestOrder = $orders->first();
                        $latestStatus = $latestOrder->fulfillment_status ?? 'processing';
                        $step = match($latestStatus) {
                            'processing' => 1,
                            'shipped' => 2,
                            'delivered' => 3,
                            default => 1,
                        };
                    @endphp

                    @if($latestOrder && $latestStatus !== 'delivered' && $latestStatus !== 'cancelled')
                        <div class="p-6 rounded-xl bg-gradient-to-b from-neutral-900/80 to-neutral-900/40 border border-[#c5a880]/30 space-y-4">
                            <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-white/10">
                                <div>
                                    <span class="text-[10px] uppercase tracking-widest text-[#c5a880] font-semibold block">Live Dispatch Tracker</span>
                                    <h3 class="font-mono text-base text-white font-medium">{{ $latestOrder->order_number }}</h3>
                                </div>
                                <span class="px-3 py-1 text-[11px] uppercase tracking-widest rounded-full border
                                    {{ $latestStatus === 'shipped' ? 'bg-sky-950/80 text-sky-300 border-sky-500/40' : 'bg-amber-950/80 text-amber-300 border-amber-500/40' }}
                                ">
                                    {{ $latestStatus === 'shipped' ? 'Dispatched with Courier' : 'In Atelier Tailoring' }}
                                </span>
                            </div>

                            <div class="py-2 space-y-3">
                                <div class="grid grid-cols-3 text-center text-[10px] uppercase tracking-widest font-medium">
                                    <span class="{{ $step >= 1 ? 'text-[#c5a880]' : 'text-neutral-600' }}">1. Atelier Tailoring</span>
                                    <span class="{{ $step >= 2 ? 'text-[#c5a880]' : 'text-neutral-600' }}">2. Courier Transit</span>
                                    <span class="{{ $step >= 3 ? 'text-emerald-400' : 'text-neutral-600' }}">3. Client Handover</span>
                                </div>
                                <div class="h-1.5 bg-neutral-800 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#c5a880] to-emerald-400 transition-all duration-700" style="width: {{ $step === 1 ? '33%' : ($step === 2 ? '66%' : '100%') }};"></div>
                                </div>
                                @if(!empty($latestOrder->tracking_number))
                                    <div class="flex items-center justify-between text-xs text-neutral-400 pt-1">
                                        <span>Courier Tracking Reference:</span>
                                        <span class="font-mono text-white bg-black/40 px-2.5 py-1 rounded border border-white/10 select-all">{{ $latestOrder->tracking_number }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- All Orders Dossiers List -->
                    <div class="space-y-4">
                        <h3 class="text-xs uppercase tracking-widest text-neutral-400 font-medium">All Dossier Records</h3>

                        <div class="space-y-4">
                            @foreach($orders as $order)
                                @php
                                    $orderStatus = $order->fulfillment_status ?? 'processing';
                                @endphp
                                <div class="p-5 sm:p-6 rounded-xl bg-neutral-900/50 border border-white/10 space-y-4 hover:border-white/20 transition-all">
                                    <!-- Order Header -->
                                    <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-white/10">
                                        <div class="space-y-0.5">
                                            <div class="flex items-center gap-3">
                                                <span class="font-mono text-sm sm:text-base text-white font-medium">{{ $order->order_number }}</span>
                                                <span class="text-xs text-neutral-400 font-mono">{{ $order->created_at->format('M j, Y') }}</span>
                                            </div>
                                            <p class="text-[11px] text-neutral-400">
                                                Payment: <span class="capitalize text-neutral-300">{{ $order->payment_method }}</span> &middot; 
                                                Delivery: <span class="text-neutral-300">{{ $order->city }}, {{ $order->country }}</span>
                                            </p>
                                        </div>

                                        <span class="px-2.5 py-1 text-[10px] uppercase tracking-wider rounded-full border
                                            {{ $orderStatus === 'delivered' ? 'bg-emerald-950/80 text-emerald-300 border-emerald-500/40' : '' }}
                                            {{ $orderStatus === 'shipped' ? 'bg-sky-950/80 text-sky-300 border-sky-500/40' : '' }}
                                            {{ $orderStatus === 'processing' ? 'bg-amber-950/80 text-amber-300 border-amber-500/40' : '' }}
                                            {{ $orderStatus === 'cancelled' ? 'bg-rose-950/80 text-rose-300 border-rose-500/40' : '' }}
                                        ">
                                            {{ match($orderStatus) {
                                                'processing' => 'In Atelier Tailoring',
                                                'shipped' => 'Dispatched',
                                                'delivered' => 'Delivered',
                                                'cancelled' => 'Cancelled',
                                                default => ucfirst($orderStatus),
                                            } }}
                                        </span>
                                    </div>

                                    <!-- Order Items Grid / List -->
                                    <div class="divide-y divide-white/5">
                                        @foreach($order->items as $item)
                                            <div class="py-2.5 flex items-center justify-between text-xs gap-4">
                                                <div class="flex items-center space-x-3.5 min-w-0">
                                                    @if(!empty($item->image))
                                                        <img src="{{ $item->image }}" alt="{{ $item->product_name }}" class="w-12 h-14 object-cover rounded bg-neutral-900 shrink-0 border border-white/10">
                                                    @else
                                                        <div class="w-12 h-14 rounded bg-neutral-900 border border-white/10 flex items-center justify-center text-neutral-600 font-serif shrink-0">L</div>
                                                    @endif
                                                    <div class="min-w-0">
                                                        <p class="font-serif text-sm text-white truncate">{{ $item->product_name }}</p>
                                                        <p class="text-[11px] text-neutral-400 mt-0.5">
                                                            Size: <span class="text-neutral-200 uppercase font-mono">{{ $item->size }}</span> &middot; Qty: <span class="text-neutral-200 font-mono">{{ $item->quantity }}</span>
                                                        </p>
                                                    </div>
                                                </div>
                                                <span class="text-white font-mono font-medium shrink-0">
                                                    ${{ number_format($item->price * $item->quantity, 2) }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Order Footer -->
                                    <div class="pt-3 border-t border-white/10 flex flex-wrap items-center justify-between gap-3 text-xs">
                                        @if(!empty($order->tracking_number))
                                            <span class="text-[11px] text-neutral-400 font-mono">
                                                Tracking: <span class="text-neutral-200 select-all">{{ $order->tracking_number }}</span>
                                            </span>
                                        @else
                                            <span class="text-[11px] text-neutral-500 font-mono">Tracking generated at dispatch</span>
                                        @endif

                                        <div class="flex items-center gap-3">
                                            <span class="text-[11px] uppercase tracking-widest text-neutral-400">Total:</span>
                                            <span class="font-serif text-base text-[#c5a880] font-semibold font-mono">
                                                ${{ number_format($order->total ?? $order->subtotal, 2) }} USD
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Orders Pagination Links -->
                        @if($orders->hasPages())
                            <div class="pt-6 border-t border-white/10 flex justify-center">
                                {{ $orders->appends(['tab' => 'orders'])->links() }}
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-16 text-neutral-500 space-y-4">
                        <div class="w-16 h-16 rounded-full bg-neutral-900 border border-white/10 flex items-center justify-center mx-auto text-neutral-500">
                            <svg class="w-8 h-8 stroke-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <p class="font-serif text-lg text-white">No active garment commissions recorded</p>
                            <p class="text-xs text-neutral-400 max-w-sm mx-auto">Your couture acquisitions and bespoke salon tailoring will be recorded in this dossier.</p>
                        </div>
                        <a href="{{ route('shop.index') }}" class="inline-block mt-2 px-6 py-2.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-widest font-semibold transition-colors">
                            Explore Runway Collection &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <!-- TAB 5: ACCOUNT SECURITY -->
            <div x-show="activeTab === 'security'" x-cloak class="bg-neutral-950 p-6 sm:p-8 rounded-xl border border-white/10 space-y-8" x-data="{ showCurr: false, showNew: false }">
                <div class="pb-4 border-b border-white/10">
                    <h2 class="font-serif text-xl sm:text-2xl text-white uppercase tracking-wider">Account Security</h2>
                    <p class="text-xs text-neutral-400 mt-1">Manage confidential master password and authentication credentials</p>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <h3 class="text-xs uppercase tracking-widest text-neutral-300 font-medium">Update Master Password</h3>

                    <div>
                        <label for="current_password" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Current Password</label>
                        <div class="relative max-w-md">
                            <input 
                                :type="showCurr ? 'text' : 'password'" 
                                id="current_password"
                                name="current_password" 
                                required
                                autocomplete="current-password"
                                placeholder="••••••••••••"
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 pr-12 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                            >
                            <button 
                                type="button" 
                                @click="showCurr = !showCurr"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white p-1 cursor-pointer"
                                aria-label="Toggle password visibility"
                            >
                                <svg x-show="!showCurr" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showCurr" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="new_password" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">New Password (Min. 8)</label>
                            <div class="relative">
                                <input 
                                    :type="showNew ? 'text' : 'password'" 
                                    id="new_password"
                                    name="password" 
                                    required
                                    autocomplete="new-password"
                                    placeholder="••••••••••••"
                                    class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 pr-12 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                                >
                                <button 
                                    type="button" 
                                    @click="showNew = !showNew"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white p-1 cursor-pointer"
                                    aria-label="Toggle password visibility"
                                >
                                    <svg x-show="!showNew" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="showNew" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label for="new_password_confirmation" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2 font-medium">Confirm New Password</label>
                            <input 
                                :type="showNew ? 'text' : 'password'" 
                                id="new_password_confirmation"
                                name="password_confirmation" 
                                required
                                autocomplete="new-password"
                                placeholder="••••••••••••"
                                class="w-full bg-neutral-900 border border-white/10 rounded-lg px-4 py-3 text-sm text-white placeholder-neutral-500 outline-none focus:border-[#c5a880] focus:ring-1 focus:ring-[#c5a880] transition-colors"
                            >
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                        <span class="text-[11px] text-neutral-500">Requires your existing password to verify identity.</span>
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded-lg text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection
