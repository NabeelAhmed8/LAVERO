@extends('layouts.app')

@section('title', 'Legal Notice & Imprint — LAVÉRO')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 md:px-16 max-w-4xl mx-auto" data-motion="fade-up">
    <div class="mb-12 pb-8 border-b border-white/10">
        <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
            Corporate Registry &middot; Mentions Légales
        </span>
        <h1 class="font-serif text-3xl md:text-5xl text-white font-light uppercase tracking-wide">
            Legal Notice
        </h1>
        <p class="text-xs text-neutral-400 mt-3 uppercase tracking-widest font-light">
            Corporate Imprint &amp; Regulatory Disclosures
        </p>
    </div>

    <div class="space-y-10 text-sm text-neutral-300 font-light leading-relaxed">
        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">1. Company Identity</h3>
            <p>
                <strong>Maison LAVÉRO S.A.</strong><br>
                Société Anonyme with share capital of &euro;2,500,000<br>
                Registered Office: 18 Rue Saint-Honoré, 75001 Paris, France<br>
                Paris Trade and Companies Registry (RCS Paris): 849 203 118<br>
                EU VAT Identification: FR 38 849203118
            </p>
        </section>

        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">2. Atelier Management</h3>
            <p>
                <strong>Publishing Director:</strong> Chief Executive Officer &middot; Maison LAVÉRO<br>
                <strong>Artistic &amp; Silhouette Direction:</strong> LAVÉRO Creative Atelier, Milan
            </p>
        </section>

        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">3. Concierge &amp; Host</h3>
            <p>
                Hosting and infrastructure provided by European cloud nodes adhering to strict ISO/IEC 27001 data center physical and digital security protocols.
            </p>
        </section>

        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">4. Official Inquiries</h3>
            <p>
                For official legal notices or compliance correspondence, please address inquiries to <span class="text-[#c5a880]">legal@lavero-couture.com</span>.
            </p>
        </section>
    </div>
</div>
@endsection
