@extends('layouts.app')

@section('title', 'The Atelier & Craftsmanship — LAVÉRO')

@section('content')
<div class="pt-28 pb-24 px-4 sm:px-6 md:px-16 max-w-7xl mx-auto">
    <!-- Hero Header -->
    <div class="max-w-3xl mb-20" data-motion="fade-up">
        <span class="text-xs uppercase tracking-[0.35em] text-[#c5a880] block mb-2 font-medium">
            Heritage &amp; Architecture
        </span>
        <h1 class="font-serif text-4xl sm:text-6xl text-white font-light tracking-tight leading-tight uppercase">
            Maison LAVÉRO
        </h1>
        <p class="text-base text-neutral-300 mt-6 leading-relaxed font-light">
            Founded with a singular pursuit: to create modern clothing with the structural discipline of brutalist architecture and the intimate softness of bespoke couture.
        </p>
    </div>

    <!-- Editorial Full Bleed Banner -->
    <div class="aspect-[21/9] w-full rounded overflow-hidden bg-neutral-900 border border-white/5 mb-24" data-motion="fade-in">
        <img 
            src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=2000&q=85" 
            alt="Maison Lavero Atelier" 
            class="w-full h-full object-cover"
        >
    </div>

    <!-- Narrative Pillars -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-24 border-b border-white/10" data-motion="fade-up">
        <div>
            <span class="font-serif text-4xl text-[#c5a880] block mb-4">01</span>
            <h3 class="font-serif text-2xl text-white font-light mb-3">Two Master Ateliers</h3>
            <p class="text-xs text-neutral-400 leading-relaxed font-light">
                Our patternmakers operate between Paris (Rue Saint-Honoré) and Milan (Via Montenapoleone). Every garment is cut individually, never stacked or laser-cut in mass batches.
            </p>
        </div>

        <div>
            <span class="font-serif text-4xl text-[#c5a880] block mb-4">02</span>
            <h3 class="font-serif text-2xl text-white font-light mb-3">Mill Traceability</h3>
            <p class="text-xs text-neutral-400 leading-relaxed font-light">
                We maintain direct relationships with family mills in Biella, Northern Italy. Our wools and silks are unblended with synthetic polymers to guarantee generational longevity.
            </p>
        </div>

        <div>
            <span class="font-serif text-4xl text-[#c5a880] block mb-4">03</span>
            <h3 class="font-serif text-2xl text-white font-light mb-3">Numbered Editions</h3>
            <p class="text-xs text-neutral-400 leading-relaxed font-light">
                To prevent deadstock and preserve true exclusivity, seasonal capsules are capped at 50 to 100 numbered pieces worldwide. Once an edition is retired, it is never reissued.
            </p>
        </div>
    </div>

    <!-- Atelier Quotes -->
    <div class="py-24 text-center max-w-3xl mx-auto" data-motion="fade-up">
        <blockquote class="font-serif text-2xl sm:text-4xl text-neutral-200 font-light italic leading-relaxed">
            &ldquo;We do not follow fashion trends. We sculpt timeless silhouettes that outlive the season.&rdquo;
        </blockquote>
        <span class="block text-xs uppercase tracking-[0.3em] text-[#c5a880] mt-6">
            LAVÉRO Studio Direction &middot; Paris
        </span>
    </div>
</div>
@endsection
