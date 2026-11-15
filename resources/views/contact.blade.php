@extends('layouts.app')

@section('title', 'Private Client Care & Atelier Appointments — LAVÉRO')

@section('content')
<div class="pt-28 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="max-w-2xl mb-16" data-motion="fade-up">
        <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
            Private Client Concierge
        </span>
        <h1 class="font-serif text-4xl sm:text-6xl text-white font-light tracking-tight uppercase">
            Atelier Appointments
        </h1>
        <p class="text-sm text-neutral-300 mt-4 leading-relaxed font-light">
            Our client advisors offer private showings, bespoke measurements, and dedicated styling at our Paris and Milan salons.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
        <!-- Left: Form -->
        <div class="lg:col-span-7 bg-neutral-950/80 p-8 md:p-12 rounded border border-white/10" data-motion="fade-up">
            <h3 class="font-serif text-2xl text-white font-light mb-6">
                Request a Private Appointment
            </h3>

            @if(session('success'))
                <div class="mb-6 p-4 rounded bg-[#c5a880]/10 border border-[#c5a880]/40 text-[#c5a880] text-xs">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="contact-name" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Full Name *</label>
                    <input 
                        type="text" 
                        id="contact-name"
                        name="name" 
                        required 
                        autocomplete="name"
                        placeholder="Madame / Monsieur" 
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                    >
                </div>

                <div>
                    <label for="contact-email" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Email Address *</label>
                    <input 
                        type="email" 
                        id="contact-email"
                        name="email" 
                        required 
                        autocomplete="email"
                        placeholder="client@domain.com" 
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                    >
                </div>

                <div>
                    <label for="contact-salon" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Preferred Atelier *</label>
                    <select 
                        id="contact-salon"
                        name="salon" 
                        required 
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white outline-none focus:border-[#c5a880] transition-colors cursor-pointer"
                    >
                        <option value="Paris">Paris Salon &middot; 18 Rue Saint-Honoré</option>
                        <option value="Milano">Milano Salon &middot; 12 Via Montenapoleone</option>
                        <option value="Digital">Digital Styling &middot; Video Consultation</option>
                    </select>
                </div>

                <div>
                    <label for="contact-message" class="block text-xs uppercase tracking-widest text-neutral-400 mb-2">Garment of Interest / Notes</label>
                    <textarea 
                        id="contact-message"
                        name="message" 
                        rows="4" 
                        placeholder="Inquire about bespoke sizing, specific runway pieces, or travel dates..."
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-sm text-white placeholder-neutral-600 outline-none focus:border-[#c5a880] transition-colors"
                    ></textarea>
                </div>

                <button 
                    type="submit" 
                    data-magnetic
                    class="w-full py-4 bg-white text-black hover:bg-[#c5a880] hover:text-white rounded text-xs uppercase tracking-[0.25em] font-semibold transition-colors duration-300"
                >
                    Submit Fitting Request
                </button>
            </form>
        </div>

        <!-- Right: Salon Addresses & Concierge Info -->
        <div class="lg:col-span-5 space-y-12" data-motion="fade-up">
            <div>
                <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2 font-medium">Paris Atelier</span>
                <h4 class="font-serif text-2xl text-white font-light">Salon Saint-Honoré</h4>
                <p class="text-sm text-neutral-400 mt-2 font-light">
                    18 Rue Saint-Honoré, 75001 Paris, France<br>
                    Monday &ndash; Saturday &middot; By Private Appointment
                </p>
                <p class="text-xs text-neutral-400 mt-2">concierge.paris@lavero-couture.com</p>
            </div>

            <div class="pt-8 border-t border-white/10">
                <span class="text-xs uppercase tracking-[0.3em] text-[#c5a880] block mb-2 font-medium">Milano Atelier</span>
                <h4 class="font-serif text-2xl text-white font-light">Salon Montenapoleone</h4>
                <p class="text-sm text-neutral-400 mt-2 font-light">
                    12 Via Montenapoleone, 20121 Milano, Italy<br>
                    Tuesday &ndash; Sunday &middot; By Private Appointment
                </p>
                <p class="text-xs text-neutral-400 mt-2">concierge.milano@lavero-couture.com</p>
            </div>

            <div class="pt-8 border-t border-white/10 text-xs text-neutral-400 space-y-2">
                <p class="uppercase tracking-widest text-neutral-300">White-Glove Guarantee</p>
                <p class="font-light leading-relaxed">
                    All bespoke garments include lifetime maintenance, button replacement, and minor alteration at any LAVÉRO salon worldwide.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
