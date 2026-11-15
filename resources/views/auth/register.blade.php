@extends('layouts.app')

@section('title', 'Client Registration — LAVÉRO')

@section('content')
<div class="min-h-[calc(100vh-140px)] flex flex-col justify-center items-center px-4 py-4 sm:py-6" data-motion="fade-up">
    <div class="w-full max-w-lg">
        <!-- Header -->
        <div class="text-center mb-6">
            <span class="text-[10px] uppercase tracking-[0.35em] text-[#c5a880] block font-medium">
                Maison Membership
            </span>
            <h1 class="font-serif text-2xl sm:text-3xl text-white font-light uppercase tracking-wide mt-1">
                Client Registration
            </h1>
            <p class="text-[11px] text-neutral-400 mt-1 uppercase tracking-widest font-light">
                Create an account for bespoke tailoring, invitations &amp; orders
            </p>
        </div>

        <!-- Register Card -->
        <div class="bg-neutral-950 p-6 sm:p-8 rounded border border-white/10 shadow-2xl">
            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-950/40 border border-red-500/40 text-red-300 text-xs">
                    @foreach($errors->all() as $error)
                        <p>&bull; {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-4" x-data="{ showPass: false, showConfirm: false }">
                @csrf

                <div>
                    <label for="name" class="block text-[11px] uppercase tracking-widest text-neutral-400 mb-1.5">Full Name</label>
                    <input 
                        type="text" 
                        id="name"
                        name="name" 
                        value="{{ old('name') }}" 
                        required 
                        autofocus
                        autocomplete="name"
                        placeholder="Madame / Monsieur" 
                        class="w-full bg-neutral-900 border border-white/10 rounded px-3.5 py-2.5 text-xs sm:text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                    >
                </div>

                <div>
                    <label for="email" class="block text-[11px] uppercase tracking-widest text-neutral-400 mb-1.5">Email Address</label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email"
                        placeholder="client@private.com" 
                        class="w-full bg-neutral-900 border border-white/10 rounded px-3.5 py-2.5 text-xs sm:text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                    >
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="password" class="block text-[11px] uppercase tracking-widest text-neutral-400 mb-1.5">Password (Min. 8)</label>
                        <div class="relative">
                            <input 
                                :type="showPass ? 'text' : 'password'" 
                                id="password"
                                name="password" 
                                required 
                                autocomplete="new-password"
                                placeholder="••••••••••••" 
                                class="w-full bg-neutral-900 border border-white/10 rounded px-3.5 py-2.5 pr-11 text-xs sm:text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                            <button 
                                type="button" 
                                @click="showPass = !showPass"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white p-1 transition-colors cursor-pointer"
                                aria-label="Toggle password visibility"
                            >
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-[11px] uppercase tracking-widest text-neutral-400 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input 
                                :type="showConfirm ? 'text' : 'password'" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                required 
                                autocomplete="new-password"
                                placeholder="••••••••••••" 
                                class="w-full bg-neutral-900 border border-white/10 rounded px-3.5 py-2.5 pr-11 text-xs sm:text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                            >
                            <button 
                                type="button" 
                                @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 hover:text-white p-1 transition-colors cursor-pointer"
                                aria-label="Toggle confirm password visibility"
                            >
                                <svg x-show="!showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" x-cloak class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <button 
                    type="submit" 
                    data-magnetic
                    class="w-full py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-[0.25em] font-semibold transition-all duration-300 shadow-xl cursor-pointer"
                >
                    Create Account &amp; Enter
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-white/10 text-center">
                <p class="text-xs text-neutral-400">
                    Already registered with the Maison? 
                    <a href="{{ route('login') }}" class="text-[#c5a880] hover:underline uppercase tracking-wider ml-1 font-medium">
                        Sign In
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
