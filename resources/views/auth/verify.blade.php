@extends('layouts.app')

@section('title', 'Verify Access Code — LAVÉRO')

@section('content')
<div class="min-h-[calc(100vh-140px)] flex flex-col justify-center items-center px-4 py-4 sm:py-6" data-motion="fade-up">
    <div class="w-full max-w-md">
        <div class="text-center mb-6">
            <span class="text-[10px] uppercase tracking-[0.35em] text-[#c5a880] block font-medium">
                Confidential Client Protocol
            </span>
            <h1 class="font-serif text-2xl sm:text-3xl text-white font-light uppercase tracking-wide mt-1">
                Verify Email
            </h1>
            <p class="text-[11px] text-neutral-400 mt-1 uppercase tracking-widest font-light">
                Enter the 6-digit authentication code sent to your email
            </p>
        </div>

        <div class="bg-neutral-950 p-6 sm:p-8 rounded border border-white/10 shadow-2xl">
            @if($errors->any())
                <div class="mb-4 p-3 rounded bg-red-950/40 border border-red-500/40 text-red-300 text-xs">
                    @foreach($errors->all() as $error)
                        <p>&bull; {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('verify.submit') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div>
                    <label for="verification_code" class="block text-[11px] uppercase tracking-widest text-neutral-400 mb-2 text-center">6-Digit Code</label>
                    <input 
                        type="text" 
                        id="verification_code"
                        name="code" 
                        maxlength="6"
                        required 
                        autofocus
                        autocomplete="one-time-code"
                        placeholder="123456" 
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-center text-2xl font-mono tracking-[0.4em] text-white placeholder-neutral-700 outline-none focus:border-[#c5a880] transition-colors"
                    >
                </div>

                <button 
                    type="submit" 
                    data-magnetic
                    class="w-full py-3.5 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-[0.25em] font-semibold transition-all duration-300 shadow-xl cursor-pointer"
                >
                    Confirm Code &amp; Activate
                </button>
            </form>

            <div class="mt-5 pt-4 border-t border-white/10 text-center">
                <form action="{{ route('verify.resend') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
                    <span class="text-xs text-neutral-500">Did not receive your code?</span>
                    <button type="submit" class="text-xs text-[#c5a880] hover:underline ml-1 font-medium bg-transparent border-none cursor-pointer">
                        Resend
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
