@extends('admin.layout')

@section('title', ($isEdit ? 'Edit Creation' : 'New Creation') . ' — LAVÉRO CMS')

@section('admin_content')
<div class="w-full max-w-5xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-white/10 pb-6">
        <div>
            <a href="{{ route('admin.products') }}" class="text-xs uppercase tracking-[0.25em] text-[#c5a880] hover:underline inline-flex items-center gap-1.5 mb-2">
                <span>&larr;</span>
                <span>Return to Creations</span>
            </a>
            <h1 class="font-serif text-2xl sm:text-3xl text-white font-light uppercase tracking-wide">
                {{ $isEdit ? 'Edit Piece: ' . $product->name : 'Catalogue a New Creation' }}
            </h1>
        </div>

        <div class="flex items-center gap-3">
            <a 
                href="{{ route('admin.products') }}" 
                class="py-2.5 px-5 border border-white/15 hover:border-white text-neutral-300 hover:text-white rounded text-xs uppercase tracking-widest transition-colors"
            >
                Discard
            </a>
            <button 
                type="submit" 
                form="creation-form"
                class="py-2.5 px-6 bg-white hover:bg-[#c5a880] text-black hover:text-white rounded text-xs uppercase tracking-widest font-semibold transition-colors shadow-lg cursor-pointer"
            >
                {{ $isEdit ? 'Save Changes' : 'Publish Creation' }}
            </button>
        </div>
    </div>

    <!-- Error Summary Alert -->
    @if($errors->any())
        <div class="p-4 rounded-lg bg-red-950/40 border border-red-800/60 text-red-200 text-xs space-y-1.5">
            <span class="font-semibold block uppercase tracking-wider text-red-300">Please review the following errors:</span>
            <ul class="list-disc list-inside space-y-1 text-neutral-300">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Main Form -->
    <form 
        id="creation-form"
        action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}" 
        method="POST" 
        enctype="multipart/form-data"
        autocomplete="off"
        class="space-y-8"
    >
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <!-- 1. GENERAL GARMENT IDENTITY & PRICING -->
        <div class="rounded-lg bg-[#0f0f0f] border border-white/10 p-6 sm:p-8 space-y-6">
            <div class="border-b border-white/10 pb-4">
                <span class="text-xs uppercase tracking-[0.25em] text-[#c5a880] font-semibold block">
                    01. Garment Identity &amp; Pricing
                </span>
                <p class="text-xs text-neutral-400 mt-0.5">Primary title, atelier department, and acquisition value</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="sm:col-span-2 space-y-2">
                    <label for="name" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                        Creation Title *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $product->name) }}" 
                        required 
                        placeholder="e.g. L'Éclipse Double-Breasted Wool Trench"
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-[#c5a880] focus:outline-none transition-colors"
                    >
                </div>

                <!-- Category -->
                <div class="space-y-2">
                    <label for="category_id" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                        Atelier Department / Category *
                    </label>
                    <select 
                        id="category_id" 
                        name="category_id" 
                        required
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-[#c5a880] focus:outline-none transition-colors cursor-pointer"
                    >
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price (Laser Aligned) -->
                <div class="space-y-2">
                    <label for="price" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                        Acquisition Price (USD) *
                    </label>
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-[#c5a880] font-mono text-sm pointer-events-none">$</span>
                        <input 
                            type="number" 
                            step="0.01" 
                            id="price" 
                            name="price" 
                            value="{{ old('price', $product->price) }}" 
                            required 
                            placeholder="1850.00"
                            class="w-full bg-neutral-900 border border-white/10 rounded pl-9 pr-4 py-3 text-white font-mono text-sm focus:border-[#c5a880] focus:outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                        >
                    </div>
                </div>

                <!-- Material / Composition -->
                <div class="sm:col-span-2 space-y-2">
                    <label for="material" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                        Fabric Composition &amp; Provenance *
                    </label>
                    <input 
                        type="text" 
                        id="material" 
                        name="material" 
                        value="{{ old('material', $product->material) }}" 
                        required 
                        placeholder="e.g. 100% Super 150s Italian Virgin Wool &amp; Mulberry Silk Lining"
                        class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-[#c5a880] focus:outline-none transition-colors"
                    >
                </div>
            </div>
        </div>

        <!-- 2. EDITORIAL TAGS & INDUSTRY-STANDARD CLOTHING SIZES -->
        <div class="rounded-lg bg-[#0f0f0f] border border-white/10 p-6 sm:p-8 space-y-6" x-data="{
            currentTag: '{{ old('tag', $product->tag ?? '') }}',
            tagPresets: ['Runway Drop', 'New Arrival', 'Atelier Exclusive', 'Bespoke Order', 'Archival Piece', 'Private Salon'],
            setTag(t) {
                this.currentTag = (this.currentTag === t) ? '' : t;
            },
            sizeSystem: 'alpha',
            alphaSizes: ['XXS', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL'],
            numericSizes: ['34', '36', '38', '40', '42', '44', '46', '48', '50', '52'],
            universalSizes: ['One Size'],
            sizesString: '{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : ($product->sizes ?? 'S, M, L')) }}',
            get selectedSizesList() {
                return this.sizesString.split(',').map(s => s.trim()).filter(Boolean);
            },
            toggleSize(size) {
                let list = [...this.selectedSizesList];
                if (list.includes(size)) {
                    list = list.filter(s => s !== size);
                } else {
                    list.push(size);
                }
                this.sizesString = list.join(', ');
            },
            applyPreset(type) {
                if (type === 'core_alpha') {
                    this.sizesString = 'S, M, L, XL';
                } else if (type === 'full_alpha') {
                    this.sizesString = 'XS, S, M, L, XL, 2XL';
                } else if (type === 'eu_tailored') {
                    this.sizesString = '38, 40, 42, 44, 46';
                } else if (type === 'one_size') {
                    this.sizesString = 'One Size';
                }
            }
        }">
            <div class="border-b border-white/10 pb-4">
                <span class="text-xs uppercase tracking-[0.25em] text-[#c5a880] font-semibold block">
                    02. Editorial Badging &amp; Industry Garment Sizing
                </span>
                <p class="text-xs text-neutral-400 mt-0.5">Industry-standard ready-to-wear &amp; couture size grids with runway badge presets</p>
            </div>

            <div class="space-y-6">
                <!-- Editorial Tag Block -->
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <label for="tag" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                            Editorial Tag (Floating Runway Badge)
                        </label>
                        <!-- Live Tag Pill Preview -->
                        <div class="flex items-center space-x-2 self-start sm:self-auto">
                            <span class="text-[10px] uppercase tracking-widest text-neutral-500">Live Badge:</span>
                            <span 
                                class="px-2.5 py-0.5 text-[10px] uppercase tracking-widest rounded-full border transition-all whitespace-nowrap"
                                :class="currentTag ? 'bg-neutral-900 border-[#c5a880]/60 text-[#c5a880] font-semibold shadow-sm' : 'border-white/10 text-neutral-600'"
                                x-text="currentTag ? currentTag : 'No Active Tag'"
                            ></span>
                        </div>
                    </div>

                    <div class="space-y-2.5">
                        <input 
                            type="text" 
                            id="tag" 
                            name="tag" 
                            x-model="currentTag"
                            placeholder="Type custom editorial badge or click a preset..."
                            class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-[#c5a880] focus:outline-none transition-colors"
                        >

                        <!-- Preset Clickable Tag Chips -->
                        <div class="flex flex-wrap items-center gap-2 pt-1">
                            <span class="text-[10px] uppercase tracking-wider text-neutral-500 mr-1">Presets:</span>
                            <template x-for="preset in tagPresets" :key="preset">
                                <button 
                                    type="button" 
                                    @click="setTag(preset)"
                                    class="px-2.5 py-1 rounded text-[11px] uppercase tracking-wider transition-all cursor-pointer border"
                                    :class="currentTag === preset ? 'bg-[#c5a880] text-black border-[#c5a880] font-semibold shadow' : 'bg-neutral-900 text-neutral-400 border-white/10 hover:text-white hover:border-white/20'"
                                >
                                    <span x-text="preset"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Industry Standard Garment Sizing Block -->
                <div class="space-y-3 pt-5 border-t border-white/5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div>
                            <label for="sizes" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                                Industry Garment Size Selector
                            </label>
                            <p class="text-[11px] text-neutral-500">Standard international alpha or Italian/French numeric sizing</p>
                        </div>

                        <!-- System Switch Tabs -->
                        <div class="flex items-center space-x-1 bg-black/60 p-1 rounded border border-white/10 text-[10px] uppercase tracking-wider self-start sm:self-auto">
                            <button 
                                type="button" 
                                @click="sizeSystem = 'alpha'"
                                :class="sizeSystem === 'alpha' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white'"
                                class="px-2.5 py-1 rounded transition-colors cursor-pointer"
                            >
                                Alpha (XS—2XL)
                            </button>
                            <button 
                                type="button" 
                                @click="sizeSystem = 'numeric'"
                                :class="sizeSystem === 'numeric' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white'"
                                class="px-2.5 py-1 rounded transition-colors cursor-pointer"
                            >
                                EU/IT (34—52)
                            </button>
                            <button 
                                type="button" 
                                @click="sizeSystem = 'universal'"
                                :class="sizeSystem === 'universal' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white'"
                                class="px-2.5 py-1 rounded transition-colors cursor-pointer"
                            >
                                Universal
                            </button>
                        </div>
                    </div>

                    <!-- Size Pills Display Based on Selected System -->
                    <div class="space-y-3 pt-1">
                        <!-- Alpha Sizing Grid -->
                        <div x-show="sizeSystem === 'alpha'" class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <template x-for="sz in alphaSizes" :key="sz">
                                    <button 
                                        type="button" 
                                        @click="toggleSize(sz)"
                                        class="min-w-12 py-2 px-3 rounded text-xs uppercase tracking-wider transition-all cursor-pointer border text-center font-medium"
                                        :class="selectedSizesList.includes(sz) ? 'bg-[#c5a880] text-black border-[#c5a880] font-bold shadow-md shadow-[#c5a880]/15' : 'bg-neutral-900 text-neutral-400 border-white/10 hover:text-white hover:bg-neutral-800'"
                                    >
                                        <span x-text="sz"></span>
                                    </button>
                                </template>
                            </div>
                            <!-- Quick Batch Presets -->
                            <div class="flex items-center gap-2 pt-1 text-[10px] text-neutral-500 uppercase tracking-wider">
                                <span>Quick Sets:</span>
                                <button type="button" @click="applyPreset('core_alpha')" class="text-[#c5a880] hover:underline cursor-pointer">S, M, L, XL</button>
                                <span>&middot;</span>
                                <button type="button" @click="applyPreset('full_alpha')" class="text-[#c5a880] hover:underline cursor-pointer">All (XS—2XL)</button>
                            </div>
                        </div>

                        <!-- Numeric Sizing Grid (European Tailoring) -->
                        <div x-show="sizeSystem === 'numeric'" class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <template x-for="sz in numericSizes" :key="sz">
                                    <button 
                                        type="button" 
                                        @click="toggleSize(sz)"
                                        class="min-w-12 py-2 px-3 rounded text-xs uppercase tracking-wider transition-all cursor-pointer border text-center font-medium"
                                        :class="selectedSizesList.includes(sz) ? 'bg-[#c5a880] text-black border-[#c5a880] font-bold shadow-md shadow-[#c5a880]/15' : 'bg-neutral-900 text-neutral-400 border-white/10 hover:text-white hover:bg-neutral-800'"
                                    >
                                        <span x-text="sz"></span>
                                    </button>
                                </template>
                            </div>
                            <div class="flex items-center gap-2 pt-1 text-[10px] text-neutral-500 uppercase tracking-wider">
                                <span>Quick Sets:</span>
                                <button type="button" @click="applyPreset('eu_tailored')" class="text-[#c5a880] hover:underline cursor-pointer">Core Tailored (38—46)</button>
                            </div>
                        </div>

                        <!-- Universal Sizing -->
                        <div x-show="sizeSystem === 'universal'" class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <template x-for="sz in universalSizes" :key="sz">
                                    <button 
                                        type="button" 
                                        @click="toggleSize(sz)"
                                        class="py-2 px-4 rounded text-xs uppercase tracking-wider transition-all cursor-pointer border text-center font-medium"
                                        :class="selectedSizesList.includes(sz) ? 'bg-[#c5a880] text-black border-[#c5a880] font-bold shadow-md shadow-[#c5a880]/15' : 'bg-neutral-900 text-neutral-400 border-white/10 hover:text-white hover:bg-neutral-800'"
                                    >
                                        <span x-text="sz"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Synchronized Sizing Input -->
                        <div class="pt-2">
                            <input 
                                type="text" 
                                id="sizes" 
                                name="sizes" 
                                x-model="sizesString"
                                placeholder="e.g. S, M, L, XL"
                                class="w-full bg-neutral-900/60 border border-white/10 rounded px-4 py-2.5 text-neutral-300 text-xs focus:border-[#c5a880] focus:outline-none transition-colors"
                            >
                            <div class="flex items-center justify-between text-[10px] text-neutral-500 mt-1">
                                <span>Click size pills to toggle or type directly</span>
                                <span><span class="text-white font-mono" x-text="selectedSizesList.length"></span> sizes configured</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. COMPACT & DYNAMIC VISUAL ASSETS (SMALLER & REFINED) -->
        <div class="rounded-lg bg-[#0f0f0f] border border-white/10 p-6 sm:p-8 space-y-6">
            <div class="border-b border-white/10 pb-4">
                <span class="text-xs uppercase tracking-[0.25em] text-[#c5a880] font-semibold block">
                    03. Garment Visual Assets (Compact &amp; Dynamic)
                </span>
                <p class="text-xs text-neutral-400 mt-0.5">High-resolution garment editorial photographs with instant compact previews</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                <!-- Primary Image (Compact) -->
                <div class="bg-black/40 border border-white/10 rounded-lg p-5 space-y-4" x-data="{
                    tab: '{{ old('image_file') ? 'upload' : (old('image', $product->image) ? 'url' : 'upload') }}',
                    preview: '{{ old('image', $product->image ?? '') }}',
                    fileName: '',
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.fileName = file.name;
                            const reader = new FileReader();
                            reader.onload = ev => this.preview = ev.target.result;
                            reader.readAsDataURL(file);
                        }
                    },
                    clearImage() {
                        this.preview = '';
                        this.fileName = '';
                        const input = $refs.primaryFileInput;
                        if (input) input.value = '';
                    }
                }">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <label class="block text-xs uppercase tracking-widest text-[#c5a880] font-semibold">
                            Primary Garment Image *
                        </label>
                        <div class="flex items-center space-x-1 bg-black/80 p-0.5 rounded border border-white/10 text-[10px] uppercase tracking-wider">
                            <button 
                                type="button" 
                                @click="tab = 'upload'" 
                                :class="tab === 'upload' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white'"
                                class="px-2 py-0.5 rounded transition-colors cursor-pointer"
                            >
                                Upload
                            </button>
                            <button 
                                type="button" 
                                @click="tab = 'url'" 
                                :class="tab === 'url' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white'"
                                class="px-2 py-0.5 rounded transition-colors cursor-pointer"
                            >
                                URL
                            </button>
                        </div>
                    </div>

                    <!-- Compact Dynamic Thumbnail & Inputs Row -->
                    <div class="flex items-center gap-4">
                        <!-- Small 3:4 Thumbnail -->
                        <div class="w-16 h-20 shrink-0 bg-neutral-950 rounded border border-white/15 overflow-hidden flex items-center justify-center relative group shadow">
                            <template x-if="preview">
                                <div class="w-full h-full relative">
                                    <img :src="preview" class="w-full h-full object-cover">
                                    <button 
                                        type="button" 
                                        @click="clearImage" 
                                        class="absolute inset-0 bg-black/75 text-rose-400 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer"
                                        title="Clear Image"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </template>
                            <template x-if="!preview">
                                <svg class="w-6 h-6 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </template>
                        </div>

                        <!-- Action controls -->
                        <div class="flex-1 min-w-0 space-y-2">
                            <div x-show="tab === 'upload'">
                                <label for="image_file" class="inline-flex items-center gap-2 px-3 py-2 bg-neutral-900 hover:bg-neutral-800 border border-white/15 hover:border-[#c5a880] rounded text-xs text-white cursor-pointer transition-colors w-full justify-center truncate">
                                    <input 
                                        type="file" 
                                        id="image_file"
                                        name="image_file" 
                                        x-ref="primaryFileInput"
                                        accept="image/*" 
                                        @change="handleFile"
                                        class="hidden"
                                    >
                                    <svg class="w-4 h-4 text-[#c5a880] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="truncate" x-text="fileName ? fileName : (preview ? 'Change File' : 'Browse Garment Image')"></span>
                                </label>
                                <p class="text-[10px] text-neutral-500 mt-1">PNG, JPG, WEBP max 10MB</p>
                            </div>

                            <div x-show="tab === 'url'" class="space-y-1">
                                <label for="image" class="sr-only">Primary Garment Image URL</label>
                                <input 
                                    type="url" 
                                    id="image" 
                                    name="image" 
                                    x-model="preview"
                                    value="{{ old('image', $product->image) }}" 
                                    placeholder="https://images.unsplash.com/..."
                                    class="w-full bg-neutral-900 border border-white/10 rounded px-3 py-2 text-white text-xs focus:border-[#c5a880] focus:outline-none transition-colors"
                                >
                                <p class="text-[10px] text-neutral-500">External HTTPS image URL</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hover Image (Compact) -->
                <div class="bg-black/40 border border-white/10 rounded-lg p-5 space-y-4" x-data="{
                    tab: '{{ old('hover_image_file') ? 'upload' : (old('hover_image', $product->hover_image) ? 'url' : 'upload') }}',
                    preview: '{{ old('hover_image', $product->hover_image ?? '') }}',
                    fileName: '',
                    handleFile(e) {
                        const file = e.target.files[0];
                        if (file) {
                            this.fileName = file.name;
                            const reader = new FileReader();
                            reader.onload = ev => this.preview = ev.target.result;
                            reader.readAsDataURL(file);
                        }
                    },
                    clearImage() {
                        this.preview = '';
                        this.fileName = '';
                        const input = $refs.hoverFileInput;
                        if (input) input.value = '';
                    }
                }">
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <label class="block text-xs uppercase tracking-widest text-neutral-300 font-semibold">
                            Alternate / Hover Image
                        </label>
                        <div class="flex items-center space-x-1 bg-black/80 p-0.5 rounded border border-white/10 text-[10px] uppercase tracking-wider">
                            <button 
                                type="button" 
                                @click="tab = 'upload'" 
                                :class="tab === 'upload' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white'"
                                class="px-2 py-0.5 rounded transition-colors cursor-pointer"
                            >
                                Upload
                            </button>
                            <button 
                                type="button" 
                                @click="tab = 'url'" 
                                :class="tab === 'url' ? 'bg-[#c5a880] text-black font-semibold' : 'text-neutral-400 hover:text-white'"
                                class="px-2 py-0.5 rounded transition-colors cursor-pointer"
                            >
                                URL
                            </button>
                        </div>
                    </div>

                    <!-- Compact Dynamic Thumbnail & Inputs Row -->
                    <div class="flex items-center gap-4">
                        <!-- Small 3:4 Thumbnail -->
                        <div class="w-16 h-20 shrink-0 bg-neutral-950 rounded border border-white/15 overflow-hidden flex items-center justify-center relative group shadow">
                            <template x-if="preview">
                                <div class="w-full h-full relative">
                                    <img :src="preview" class="w-full h-full object-cover">
                                    <button 
                                        type="button" 
                                        @click="clearImage" 
                                        class="absolute inset-0 bg-black/75 text-rose-400 text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer"
                                        title="Clear Hover Image"
                                    >
                                        &times;
                                    </button>
                                </div>
                            </template>
                            <template x-if="!preview">
                                <svg class="w-6 h-6 text-neutral-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </template>
                        </div>

                        <!-- Action controls -->
                        <div class="flex-1 min-w-0 space-y-2">
                            <div x-show="tab === 'upload'">
                                <label for="hover_image_file" class="inline-flex items-center gap-2 px-3 py-2 bg-neutral-900 hover:bg-neutral-800 border border-white/15 hover:border-[#c5a880] rounded text-xs text-white cursor-pointer transition-colors w-full justify-center truncate">
                                    <input 
                                        type="file" 
                                        id="hover_image_file"
                                        name="hover_image_file" 
                                        x-ref="hoverFileInput"
                                        accept="image/*" 
                                        @change="handleFile"
                                        class="hidden"
                                    >
                                    <svg class="w-4 h-4 text-[#c5a880] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="truncate" x-text="fileName ? fileName : (preview ? 'Change File' : 'Browse Hover Image')"></span>
                                </label>
                                <p class="text-[10px] text-neutral-500 mt-1">Editorial reverse/hover angle</p>
                            </div>

                            <div x-show="tab === 'url'" class="space-y-1">
                                <label for="hover_image" class="sr-only">Alternate / Hover Garment Image URL</label>
                                <input 
                                    type="url" 
                                    id="hover_image" 
                                    name="hover_image" 
                                    x-model="preview"
                                    value="{{ old('hover_image', $product->hover_image) }}" 
                                    placeholder="https://images.unsplash.com/..."
                                    class="w-full bg-neutral-900 border border-white/10 rounded px-3 py-2 text-white text-xs focus:border-[#c5a880] focus:outline-none transition-colors"
                                >
                                <p class="text-[10px] text-neutral-500">External HTTPS image URL</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. ATELIER EDITORIAL NARRATIVE -->
        <div class="rounded-lg bg-[#0f0f0f] border border-white/10 p-6 sm:p-8 space-y-4">
            <div class="border-b border-white/10 pb-4">
                <span class="text-xs uppercase tracking-[0.25em] text-[#c5a880] font-semibold block">
                    04. Editorial Provenance &amp; Drape Narrative
                </span>
                <p class="text-xs text-neutral-400 mt-0.5">High-fashion runway commentary, silhouette construction, and styling details</p>
            </div>

            <div class="space-y-2">
                <label for="description" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                    Atelier Editorial Description *
                </label>
                <textarea 
                    id="description" 
                    name="description" 
                    rows="4" 
                    required 
                    placeholder="Describe the silhouette, lapel architecture, cloth drape, and Italian atelier craftsmanship..."
                    class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-white text-sm focus:border-[#c5a880] focus:outline-none transition-colors leading-relaxed"
                >{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- 5. INVENTORY & STOCK AVAILABILITY (NO NATIVE SPINNER ARROWS) -->
        <div class="rounded-lg bg-[#0f0f0f] border border-white/10 p-6 sm:p-8 space-y-6" x-data="{
            quantity: {{ old('stock_quantity', $product->stock_quantity ?? 10) }},
            status: '{{ old('stock_status', $product->stock_status ?? ($isEdit && !$product->in_stock ? 'out_of_stock' : 'in_stock')) }}',
            syncFromQty() {
                const q = parseInt(this.quantity) || 0;
                if (q <= 0) {
                    this.status = 'out_of_stock';
                } else if (q <= 5) {
                    this.status = 'low_stock';
                } else if (this.status === 'out_of_stock') {
                    this.status = 'in_stock';
                }
            },
            syncFromStatus() {
                if (this.status === 'out_of_stock') {
                    this.quantity = 0;
                } else if (this.status === 'low_stock' && (this.quantity > 5 || this.quantity <= 0)) {
                    this.quantity = 2;
                } else if (this.status === 'in_stock' && this.quantity <= 0) {
                    this.quantity = 10;
                }
            }
        }">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-white/10 pb-4">
                <div>
                    <span class="text-xs uppercase tracking-[0.25em] text-[#c5a880] font-semibold block">
                        05. Inventory &amp; Stock Availability
                    </span>
                    <p class="text-xs text-neutral-400 mt-0.5">Control live boutique quantities, client badges, and dispatch status</p>
                </div>

                <!-- Dynamic Live Badge Preview -->
                <div class="flex items-center space-x-2 self-start sm:self-auto">
                    <span class="text-[10px] uppercase tracking-widest text-neutral-500">Live Status:</span>
                    <span 
                        class="px-2.5 py-1 text-[11px] uppercase tracking-wider rounded font-medium border transition-colors whitespace-nowrap"
                        :class="{
                            'bg-emerald-950/80 text-emerald-300 border-emerald-800': status === 'in_stock',
                            'bg-amber-950/80 text-amber-300 border-amber-800': status === 'low_stock',
                            'bg-rose-950/80 text-rose-300 border-rose-800': status === 'out_of_stock'
                        }"
                        x-text="status === 'out_of_stock' ? 'Out of Stock · Bespoke' : (status === 'low_stock' ? 'Low in Stock (' + quantity + ' left)' : 'In Stock (' + quantity + ' available)')"
                    ></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Stock Status Dropdown -->
                <div class="space-y-2">
                    <label for="stock_status" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                        Availability Status *
                    </label>
                    <select 
                        id="stock_status" 
                        name="stock_status" 
                        x-model="status"
                        @change="syncFromStatus"
                        class="w-full bg-neutral-900 border text-xs rounded px-4 py-3 text-white focus:outline-none cursor-pointer transition-colors"
                        :class="{
                            'text-emerald-300 border-emerald-800/80': status === 'in_stock',
                            'text-amber-300 border-amber-800/80': status === 'low_stock',
                            'text-rose-300 border-rose-800/80': status === 'out_of_stock'
                        }"
                    >
                        <option value="in_stock">• In Stock (Ready for Dispatch)</option>
                        <option value="low_stock">• Low in Stock (Few Pieces Left)</option>
                        <option value="out_of_stock">• Out of Stock (Sold Out / Bespoke Order)</option>
                    </select>
                    <p class="text-[10px] text-neutral-500">Determines boutique display badge and courier availability</p>
                </div>

                <!-- Quantity Remaining Input (NO INTERNAL ARROWS) -->
                <div class="space-y-2">
                    <label for="stock_quantity" class="block text-xs uppercase tracking-widest text-neutral-300 font-medium">
                        Units / Quantity Remaining *
                    </label>
                    <div class="flex items-center space-x-2">
                        <input 
                            type="number" 
                            id="stock_quantity" 
                            name="stock_quantity" 
                            min="0" 
                            max="9999"
                            x-model.number="quantity"
                            @input="syncFromQty"
                            class="w-full bg-neutral-900 border border-white/10 rounded px-4 py-3 text-white font-mono text-sm focus:border-[#c5a880] focus:outline-none transition-colors [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            placeholder="e.g. 2"
                        >
                        <div class="flex flex-col space-y-1 shrink-0">
                            <button 
                                type="button" 
                                @click="quantity = Math.max(0, quantity - 1); syncFromQty()" 
                                class="px-3 py-1 bg-white/5 hover:bg-white/15 text-neutral-300 rounded text-xs transition-colors cursor-pointer"
                                title="Decrease Qty"
                            >
                                &minus;
                            </button>
                            <button 
                                type="button" 
                                @click="quantity = quantity + 1; syncFromQty()" 
                                class="px-3 py-1 bg-white/5 hover:bg-white/15 text-neutral-300 rounded text-xs transition-colors cursor-pointer"
                                title="Increase Qty"
                            >
                                +
                            </button>
                        </div>
                    </div>
                    <p class="text-[10px] text-neutral-500">e.g. Setting to 2 will display "Only 2 pieces remaining" to clients</p>
                </div>
            </div>

            <!-- Homepage Feature Checkbox -->
            <div class="pt-4 border-t border-white/5">
                <label for="featured" class="flex items-center space-x-3 cursor-pointer">
                    <input 
                        type="checkbox" 
                        id="featured"
                        name="featured" 
                        value="1" 
                        {{ old('featured', $product->featured) ? 'checked' : '' }}
                        class="w-4 h-4 rounded bg-neutral-900 border-white/20 text-[#c5a880] focus:ring-0"
                    >
                    <span class="text-xs uppercase tracking-wider text-neutral-300">
                        Feature this creation on the Maison Homepage Runway Spotlight
                    </span>
                </label>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="pt-6 border-t border-white/10 flex items-center justify-end space-x-4">
            <a 
                href="{{ route('admin.products') }}" 
                class="py-3 px-6 border border-white/15 hover:border-white text-neutral-300 hover:text-white rounded text-xs uppercase tracking-widest transition-colors"
            >
                Cancel
            </a>
            <button 
                type="submit" 
                class="py-3 px-8 bg-white hover:bg-[#c5a880] text-black hover:text-white rounded text-xs uppercase tracking-widest font-semibold transition-colors shadow-lg cursor-pointer"
            >
                {{ $isEdit ? 'Update Piece' : 'Publish Creation' }}
            </button>
        </div>
    </form>
</div>
@endsection
