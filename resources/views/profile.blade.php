@extends('layouts.app')

@section('title', 'Client Dossier & Profile — LAVÉRO')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto" x-data="{ activeTab: 'overview' }" data-motion="fade-up">
    <!-- Header -->
    <div class="mb-10 pb-6 border-b border-white/10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
                Confidential Client Registry
            </span>
            <h1 class="font-serif text-3xl md:text-5xl text-white font-light uppercase tracking-wide">
                Client Dossier
            </h1>
        </div>
        <div class="flex items-center space-x-4">
            <span class="text-xs uppercase tracking-widest text-neutral-400">
                Client ID: <span class="font-mono text-white">LAV-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</span>
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 border border-white/15 hover:border-red-400 text-neutral-300 hover:text-red-400 text-xs uppercase tracking-wider rounded transition-colors cursor-pointer">
                    Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Error/Success Alerts -->
    @if($errors->any())
        <div class="mb-8 p-4 rounded bg-red-950/40 border border-red-500/40 text-red-300 text-xs">
            @foreach($errors->all() as $error)
                <p>&bull; {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- 1. LEFT SIDEBAR: Client Monogram & Tab Navigation -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Client Identification Card -->
            <div class="bg-neutral-950 p-6 rounded border border-white/10 space-y-5">
                <div class="flex items-center space-x-4 pb-5 border-b border-white/10">
                    <div class="w-14 h-14 rounded-full bg-[#161616] border border-[#c5a880]/50 text-[#c5a880] font-serif text-2xl flex items-center justify-center font-light uppercase">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-serif text-lg text-white leading-snug">{{ $user->name }}</h3>
                        <p class="text-xs text-neutral-400 mt-0.5 truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <span class="text-neutral-400 uppercase tracking-wider">Atelier Tier</span>
                    <span class="text-[#c5a880] uppercase tracking-wider font-semibold">Private Salon VIP</span>
                </div>
            </div>

            <!-- Tab Pills -->
            <nav class="bg-neutral-950 p-2 sm:p-3 rounded border border-white/10 flex lg:flex-col overflow-x-auto lg:overflow-visible gap-1.5 lg:gap-1 text-xs uppercase tracking-widest font-medium no-scrollbar">
                <button 
                    @click="activeTab = 'overview'" 
                    :class="activeTab === 'overview' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-3.5 py-2.5 sm:px-4 sm:py-3 rounded transition-colors flex items-center justify-between gap-3"
                >
                    <span>1. Identification</span>
                    <span class="hidden lg:inline text-[10px] opacity-60">&rarr;</span>
                </button>

                <button 
                    @click="activeTab = 'address'" 
                    :class="activeTab === 'address' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-3.5 py-2.5 sm:px-4 sm:py-3 rounded transition-colors flex items-center justify-between gap-3"
                >
                    <span>2. Delivery Address</span>
                    <span class="hidden lg:inline text-[10px] opacity-60">&rarr;</span>
                </button>

                <button 
                    @click="activeTab = 'payment'" 
                    :class="activeTab === 'payment' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-3.5 py-2.5 sm:px-4 sm:py-3 rounded transition-colors flex items-center justify-between gap-3"
                >
                    <span>3. Payment Methods</span>
                    <span class="hidden lg:inline text-[10px] opacity-60">&rarr;</span>
                </button>

                <button 
                    @click="activeTab = 'orders'" 
                    :class="activeTab === 'orders' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-3.5 py-2.5 sm:px-4 sm:py-3 rounded transition-colors flex items-center justify-between gap-3"
                >
                    <span>4. Order Dossiers</span>
                    <span class="hidden lg:inline text-[10px] opacity-60">&rarr;</span>
                </button>

                <button 
                    @click="activeTab = 'security'" 
                    :class="activeTab === 'security' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white hover:bg-neutral-900'"
                    class="shrink-0 lg:w-full text-left px-3.5 py-2.5 sm:px-4 sm:py-3 rounded transition-colors flex items-center justify-between gap-3"
                >
                    <span>5. Account Security</span>
                    <span class="hidden lg:inline text-[10px] opacity-60">&rarr;</span>
                </button>
            </nav>
        </div>

        <!-- 2. RIGHT CONTENT AREA: Dynamic Tab Views -->
        <div class="lg:col-span-8">
            <!-- TAB 1: IDENTIFICATION & GENERAL PROFILE -->
            <div x-show="activeTab === 'overview'" x-cloak class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-8">
                <div class="pb-4 border-b border-white/10 flex justify-between items-center">
                    <div>
                        <h3 class="font-serif text-xl text-white uppercase tracking-widest">Personal Identification</h3>
                        <p class="text-xs text-neutral-400 mt-1">Primary details registered for your bespoke commissions</p>
                    </div>
                    <span class="text-xs text-emerald-400 font-mono">
                        {{ $user->email_verified_at ? '✓ Authenticated' : 'Pending Verification' }}
                    </span>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="profile_name" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Legal Name</label>
                            <input 
                                type="text" 
                                id="profile_name"
                                name="name" 
                                autocomplete="name"
                                value="{{ old('name', $user->name) }}" 
                                required
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div>

                        <div>
                            <label for="profile_email" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Email Address</label>
                            <input 
                                type="email" 
                                id="profile_email"
                                name="email" 
                                autocomplete="email"
                                value="{{ old('email', $user->email) }}" 
                                required
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex justify-end">
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Save Details
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 2: DELIVERY ADDRESSES -->
            <div x-show="activeTab === 'address'" x-cloak class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-8">
                <div class="pb-4 border-b border-white/10">
                    <h3 class="font-serif text-xl text-white uppercase tracking-widest">White-Glove Delivery Destination</h3>
                    <p class="text-xs text-neutral-400 mt-1">Default express shipping destination for physical garment deliveries</p>
                </div>

                @if($user->address)
                    <div class="p-5 rounded bg-neutral-900/60 border border-white/10 flex justify-between items-start">
                        <div class="space-y-1 text-xs">
                            <span class="text-[10px] uppercase tracking-widest text-[#c5a880] font-semibold">Primary Address On File</span>
                            <p class="text-sm font-serif text-white">{{ $user->address }}</p>
                            <p class="text-neutral-400">{{ $user->city }}, {{ $user->postal_code }} &middot; {{ $user->country }}</p>
                        </div>
                        <span class="text-[10px] uppercase tracking-widest px-2 py-1 bg-white/10 rounded text-neutral-300">Active</span>
                    </div>
                @endif

                <form action="{{ route('profile.address') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="profile_address" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Street Address &amp; Suite</label>
                        <input 
                            type="text" 
                            id="profile_address"
                            name="address" 
                            autocomplete="street-address"
                            value="{{ old('address', $user->address) }}" 
                            required
                            placeholder="742 Avenue Montaigne, Suite 4B"
                            class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <label for="profile_city" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">City</label>
                            <input 
                                type="text" 
                                id="profile_city"
                                name="city" 
                                autocomplete="address-level2"
                                value="{{ old('city', $user->city) }}" 
                                required
                                placeholder="Paris"
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div>

                        <div>
                            <label for="profile_country" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Country</label>
                            <input 
                                type="text" 
                                id="profile_country"
                                name="country" 
                                autocomplete="country-name"
                                value="{{ old('country', $user->country) }}" 
                                required
                                placeholder="France"
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div>

                        <div>
                            <label for="profile_postal_code" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Postal Code</label>
                            <input 
                                type="text" 
                                id="profile_postal_code"
                                name="postal_code" 
                                autocomplete="postal-code"
                                value="{{ old('postal_code', $user->postal_code) }}" 
                                required
                                placeholder="75008"
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex justify-end">
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Save Destination Address
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: PAYMENT METHODS -->
            <div x-show="activeTab === 'payment'" x-cloak class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-8">
                <div class="pb-4 border-b border-white/10">
                    <h3 class="font-serif text-xl text-white uppercase tracking-widest">Client Payment Methods</h3>
                    <p class="text-xs text-neutral-400 mt-1">Encrypted cards on file and private atelier wire invoicing protocols</p>
                </div>

                <!-- Existing Saved Card -->
                <div class="p-6 rounded bg-gradient-to-r from-neutral-900 to-neutral-950 border border-white/15 flex justify-between items-center max-w-md">
                    <div>
                        <span class="text-[10px] uppercase tracking-widest text-[#c5a880] font-semibold block mb-1">
                            {{ $user->card_brand ?? 'Visa Signature Infinite' }}
                        </span>
                        <p class="font-mono text-lg text-white tracking-widest">
                            &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; {{ $user->card_last_four ?? '4242' }}
                        </p>
                        <p class="text-[11px] text-neutral-400 mt-1">Expires: 12/28 &middot; Cardholder: {{ $user->name }}</p>
                    </div>
                    <span class="text-xs uppercase font-serif tracking-widest text-neutral-400">Primary</span>
                </div>

                <!-- Update Payment Method Form -->
                <form action="{{ route('profile.payment') }}" method="POST" class="space-y-6 pt-4 border-t border-white/10">
                    @csrf
                    @method('PUT')

                    <h4 class="text-xs uppercase tracking-widest text-neutral-300 font-medium">Add / Update Card on File</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="profile_card_number" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Card Number</label>
                            <input 
                                type="text" 
                                id="profile_card_number"
                                name="card_number" 
                                required
                                autocomplete="cc-number"
                                placeholder="•••• •••• •••• 8821" 
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div>
                        <div>
                            <label for="profile_card_brand" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Card Network / Type</label>
                            <select 
                                id="profile_card_brand"
                                name="card_brand" 
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white outline-none focus:border-[#c5a880] transition-colors cursor-pointer"
                            >
                                <option value="Visa Signature Infinite">Visa Signature Infinite</option>
                                <option value="Mastercard Black Edition">Mastercard Black Edition</option>
                                <option value="American Express Centurion">American Express Centurion</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Update Payment Preference
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 4: ORDER & COMMISSION DOSSIERS -->
            <div x-show="activeTab === 'orders'" x-cloak class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-6">
                <div class="pb-4 border-b border-white/10">
                    <h3 class="font-serif text-xl text-white uppercase tracking-widest">Order History &amp; Commissions</h3>
                    <p class="text-xs text-neutral-400 mt-1">Track bespoke atelier tailoring and dispatch statuses</p>
                </div>

                @if($recentOrder)
                    @php
                        $status = $recentOrder['fulfillment_status'] ?? 'processing';
                        $step = match($status) {
                            'processing' => 1,
                            'shipped' => 2,
                            'delivered' => 3,
                            default => 1,
                        };
                    @endphp
                    <div class="p-6 rounded bg-neutral-900/60 border border-white/10 space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-white/10">
                            <div>
                                <span class="text-[10px] uppercase tracking-widest text-[#c5a880] block font-semibold">Active Dispatch</span>
                                <h4 class="font-mono text-sm text-white font-medium">{{ $recentOrder['order_number'] }}</h4>
                            </div>
                            <span class="px-2.5 py-1 text-[10px] uppercase tracking-widest rounded-full border
                                {{ $status === 'delivered' ? 'bg-emerald-950/80 text-emerald-300 border-emerald-500/40' : '' }}
                                {{ $status === 'shipped' ? 'bg-sky-950/80 text-sky-300 border-sky-500/40' : '' }}
                                {{ $status === 'processing' ? 'bg-amber-950/80 text-amber-300 border-amber-500/40' : '' }}
                                {{ $status === 'cancelled' ? 'bg-rose-950/80 text-rose-300 border-rose-500/40' : '' }}
                            ">
                                {{ match($status) {
                                    'processing' => 'In Atelier Tailoring',
                                    'shipped' => 'Dispatched with Courier',
                                    'delivered' => 'Delivered to Client',
                                    'cancelled' => 'Cancelled',
                                    default => ucfirst($status),
                                } }}
                            </span>
                        </div>

                        <!-- 3-Step Live Dispatch Tracker -->
                        @if($status !== 'cancelled')
                            <div class="py-3 px-2 border-b border-white/5 space-y-2">
                                <div class="grid grid-cols-3 text-center text-[10px] uppercase tracking-widest text-neutral-400">
                                    <span class="{{ $step >= 1 ? 'text-[#c5a880] font-semibold' : 'text-neutral-600' }}">1. Atelier Prep</span>
                                    <span class="{{ $step >= 2 ? 'text-[#c5a880] font-semibold' : 'text-neutral-600' }}">2. Dispatched</span>
                                    <span class="{{ $step >= 3 ? 'text-emerald-400 font-semibold' : 'text-neutral-600' }}">3. Delivered</span>
                                </div>
                                <div class="h-1 bg-white/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-[#c5a880] to-emerald-400 transition-all duration-700" style="width: {{ $step === 1 ? '33%' : ($step === 2 ? '66%' : '100%') }};"></div>
                                </div>
                                @if(!empty($recentOrder['tracking_number']))
                                    <p class="text-[10px] text-neutral-500 font-mono text-right pt-1">
                                        Tracking: <span class="text-neutral-300">{{ $recentOrder['tracking_number'] }}</span>
                                    </p>
                                @endif
                            </div>
                        @endif

                        <div class="divide-y divide-white/5 space-y-2">
                            @foreach($recentOrder['items'] as $item)
                                <div class="pt-2 flex justify-between items-center text-xs">
                                    <div class="flex items-center space-x-3">
                                        <img src="{{ $item['image'] }}" class="w-10 h-12 object-cover rounded bg-neutral-950">
                                        <div>
                                            <p class="font-serif text-sm text-white">{{ $item['name'] }}</p>
                                            <p class="text-[11px] text-neutral-400">Size: {{ $item['size'] }} &middot; Qty: {{ $item['quantity'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-white font-medium">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="pt-3 border-t border-white/10 flex justify-between items-baseline text-xs uppercase tracking-widest">
                            <span class="text-neutral-400">Dossier Total</span>
                            <span class="font-serif text-base text-[#c5a880] font-semibold">${{ number_format($recentOrder['subtotal'], 2) }} USD</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-16 text-neutral-500 space-y-3">
                        <svg class="w-10 h-10 mx-auto text-neutral-600 stroke-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <p class="font-serif text-lg text-neutral-300">No active garment commissions recorded</p>
                        <a href="{{ route('shop.index') }}" class="inline-block text-xs uppercase tracking-widest text-[#c5a880] hover:underline">
                            Explore Runway Collection &rarr;
                        </a>
                    </div>
                @endif
            </div>

            <!-- TAB 5: ACCOUNT SECURITY -->
            <div x-show="activeTab === 'security'" x-cloak class="bg-neutral-950 p-5 sm:p-8 rounded border border-white/10 space-y-8" x-data="{ showCurr: false, showNew: false }">
                <div class="pb-4 border-b border-white/10">
                    <h3 class="font-serif text-xl text-white uppercase tracking-widest">Account Security</h3>
                    <p class="text-xs text-neutral-400 mt-1">Manage master password and authentication credentials</p>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <h4 class="text-xs uppercase tracking-widest text-neutral-300 font-medium">Update Master Password</h4>

                    <div>
                        <label for="current_password" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Current Password</label>
                        <div class="relative max-w-md">
                            <input 
                                :type="showCurr ? 'text' : 'password'" 
                                id="current_password"
                                name="current_password" 
                                required
                                autocomplete="current-password"
                                placeholder="••••••••••••"
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 pr-12 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                            <button 
                                type="button" 
                                @click="showCurr = !showCurr"
                                class="absolute right-3.5 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white p-1"
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
                            <label for="new_password" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">New Password (Min. 8)</label>
                            <div class="relative">
                                <input 
                                    :type="showNew ? 'text' : 'password'" 
                                    id="new_password"
                                    name="password" 
                                    required
                                    autocomplete="new-password"
                                    placeholder="••••••••••••"
                                    class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 pr-12 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                                >
                                <button 
                                    type="button" 
                                    @click="showNew = !showNew"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white p-1"
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
                            <label for="new_password_confirmation" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Confirm New Password</label>
                            <input 
                                :type="showNew ? 'text' : 'password'" 
                                id="new_password_confirmation"
                                name="password_confirmation" 
                                required
                                autocomplete="new-password"
                                placeholder="••••••••••••"
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div> 
                                required
                                placeholder="••••••••••••"
                                class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button 
                            type="submit" 
                            data-magnetic
                            class="px-8 py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-[0.2em] font-semibold transition-colors cursor-pointer"
                        >
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
