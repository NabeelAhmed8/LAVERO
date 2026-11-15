@extends('layouts.app')

@section('title', 'Privacy Policy — LAVÉRO')

@section('content')
<div class="pt-32 pb-24 px-4 sm:px-6 md:px-16 max-w-4xl mx-auto" data-motion="fade-up">
    <div class="mb-12 pb-8 border-b border-white/10">
        <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
            Confidentiality &middot; Maison Standards
        </span>
        <h1 class="font-serif text-3xl md:text-5xl text-white font-light uppercase tracking-wide">
            Privacy Policy
        </h1>
        <p class="text-xs text-neutral-400 mt-3 uppercase tracking-widest font-light">
            Last Updated: November 2026 &middot; Paris &amp; Milan Ateliers
        </p>
    </div>

    <div class="space-y-10 text-sm text-neutral-300 font-light leading-relaxed">
        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">1. Client Confidentiality</h3>
            <p>
                Maison LAVÉRO respects the intimate nature of bespoke tailoring and private client relationships. We treat your personal credentials, measurements, and order dossiers with absolute discretion under European GDPR standards.
            </p>
        </section>

        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">2. Cryptographic Security</h3>
            <p>
                Passwords are never stored in plaintext and are cryptographically hashed using industry-standard Bcrypt salt mechanisms. Authentication sessions utilize encrypted JWT tokens transmitted via secure, HttpOnly cookies to prevent unauthorized interception.
            </p>
        </section>

        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">3. Atelier Data Collection</h3>
            <p>
                We only collect data strictly necessary to fulfill your couture commissions, coordinate express white-glove delivery, and dispatch your private salon booking invitations. We never rent or monetize client records to third-party ad networks.
            </p>
        </section>

        <section>
            <h3 class="font-serif text-xl text-white font-normal uppercase tracking-wider mb-3">4. Client Rights &amp; Concierge Inquiries</h3>
            <p>
                You may request complete erasure or export of your client record at any time by contacting our data protection officer at <span class="text-[#c5a880]">dpo@lavero-couture.com</span>.
            </p>
        </section>
    </div>
</div>
@endsection
