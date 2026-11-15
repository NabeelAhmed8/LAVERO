<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Atelier CMS — LAVÉRO')</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:400,500,600,700|inter:300,400,500,600" rel="stylesheet" />
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>[x-cloak] { display: none !important; }</style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#080808] text-[#e8e8e8] font-sans antialiased min-h-screen flex flex-col md:flex-row overflow-x-hidden">
    <!-- Admin Sidebar -->
    <aside class="w-full md:w-64 bg-[#0d0d0d] border-b md:border-b-0 md:border-r border-white/10 flex flex-col justify-between shrink-0">
        <div>
            <!-- Brand Header -->
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="block">
                        <span class="font-serif text-xl tracking-[0.3em] font-light text-white uppercase">LAVÉRO</span>
                        <span class="block text-[8px] uppercase tracking-[0.35em] text-[#c5a880]">Atelier CMS</span>
                    </a>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="text-[10px] uppercase tracking-widest text-neutral-400 hover:text-white transition-colors" title="View Storefront">
                    Store &rarr;
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 text-xs uppercase tracking-[0.2em]">
                <a 
                    href="{{ route('admin.dashboard') }}" 
                    data-admin-nav
                    class="flex items-center space-x-3 px-4 py-3 rounded transition-colors {{ Route::is('admin.dashboard') ? 'bg-[#c5a880]/15 text-[#c5a880] border border-[#c5a880]/30 font-semibold' : 'text-neutral-400 hover:text-white hover:bg-white/5' }}"
                >
                    <svg class="w-4 h-4 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Overview</span>
                </a>

                <a 
                    href="{{ route('admin.products') }}" 
                    data-admin-nav
                    class="flex items-center space-x-3 px-4 py-3 rounded transition-colors {{ Route::is('admin.products*') ? 'bg-[#c5a880]/15 text-[#c5a880] border border-[#c5a880]/30 font-semibold' : 'text-neutral-400 hover:text-white hover:bg-white/5' }}"
                >
                    <svg class="w-4 h-4 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Creations</span>
                </a>

                <a 
                    href="{{ route('admin.orders') }}" 
                    data-admin-nav
                    class="flex items-center space-x-3 px-4 py-3 rounded transition-colors {{ Route::is('admin.orders*') ? 'bg-[#c5a880]/15 text-[#c5a880] border border-[#c5a880]/30 font-semibold' : 'text-neutral-400 hover:text-white hover:bg-white/5' }}"
                >
                    <svg class="w-4 h-4 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Client Orders</span>
                </a>

                <a 
                    href="{{ route('admin.inquiries') }}" 
                    data-admin-nav
                    class="flex items-center space-x-3 px-4 py-3 rounded transition-colors {{ Route::is('admin.inquiries*') ? 'bg-[#c5a880]/15 text-[#c5a880] border border-[#c5a880]/30 font-semibold' : 'text-neutral-400 hover:text-white hover:bg-white/5' }}"
                >
                    <svg class="w-4 h-4 stroke-[1.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span>Concierge Inquiries</span>
                </a>
            </nav>
        </div>

        <!-- User / Sign Out Footer -->
        <div class="p-5 border-t border-white/10 flex items-center justify-between text-xs">
            <div>
                <span class="block text-white font-medium truncate max-w-[130px]">{{ Auth::user()->name }}</span>
                <span class="text-[10px] text-[#c5a880] uppercase tracking-wider">Atelier Director</span>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-neutral-500 hover:text-red-400 uppercase text-[10px] tracking-widest transition-colors cursor-pointer">
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Canvas -->
    <main id="admin-main" class="flex-1 min-w-0 p-6 sm:p-10 lg:p-12 overflow-y-auto overflow-x-hidden relative">
        <!-- Top progress bar for instant feedback -->
        <div id="admin-loader" class="fixed top-0 left-0 right-0 h-0.5 bg-[#c5a880] z-50 transition-all duration-300 opacity-0 pointer-events-none" style="width: 0%;"></div>

        <div id="admin-content-wrapper">
            <!-- Flash Message -->
            @if(session('success'))
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-init="setTimeout(() => show = false, 4000)"
                    class="mb-8 bg-[#141414] border border-[#c5a880]/50 text-white px-5 py-3.5 rounded flex items-center justify-between text-xs tracking-wider"
                >
                    <div class="flex items-center space-x-3">
                        <span class="w-2 h-2 rounded-full bg-[#c5a880]"></span>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-neutral-500 hover:text-white">&times;</button>
                </div>
            @endif

            @yield('admin_content')
        </div>
    </main>

    <!-- Atelier CMS Dynamic SPA Engine (Zero-Refresh Navigation) -->
    <script>
        (function() {
            const mainWrapper = document.getElementById('admin-content-wrapper');
            const loader = document.getElementById('admin-loader');
            if (!mainWrapper) return;

            function showLoader() {
                if (loader) {
                    loader.style.opacity = '1';
                    loader.style.width = '70%';
                }
                if (mainWrapper) {
                    mainWrapper.style.transition = 'opacity 0.15s ease';
                    mainWrapper.style.opacity = '0.35';
                }
            }

            function hideLoader() {
                if (loader) {
                    loader.style.width = '100%';
                    setTimeout(() => {
                        loader.style.opacity = '0';
                        loader.style.width = '0%';
                    }, 200);
                }
                if (mainWrapper) {
                    mainWrapper.style.opacity = '1';
                }
            }

            function updateActiveSidebar(pathname) {
                document.querySelectorAll('nav a[data-admin-nav]').forEach(link => {
                    const href = link.getAttribute('href');
                    if (!href) return;
                    const linkUrl = new URL(href, window.location.origin);
                    
                    let isMatch = false;
                    if (linkUrl.pathname === '/admin') {
                        isMatch = pathname === '/admin' || pathname === '/admin/';
                    } else {
                        isMatch = pathname.startsWith(linkUrl.pathname);
                    }

                    const baseClasses = 'flex items-center space-x-3 px-4 py-3 rounded transition-colors';
                    if (isMatch) {
                        link.className = `${baseClasses} bg-[#c5a880]/15 text-[#c5a880] border border-[#c5a880]/30 font-semibold`;
                    } else {
                        link.className = `${baseClasses} text-neutral-400 hover:text-white hover:bg-white/5`;
                    }
                });
            }

            async function loadUrl(url, push = true) {
                showLoader();
                try {
                    const response = await fetch(url, {
                        headers: {
                            'X-Admin-SPA': '1',
                            'Accept': 'text/html'
                        }
                    });

                    if (!response.ok) {
                        window.location.href = url;
                        return;
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newContent = doc.getElementById('admin-content-wrapper');
                    if (newContent) {
                        if (window.Alpine && typeof window.Alpine.destroyTree === 'function') {
                            window.Alpine.destroyTree(mainWrapper);
                        }
                        mainWrapper.innerHTML = newContent.innerHTML;
                        document.title = doc.title || document.title;
                        if (push) {
                            window.history.pushState({}, '', url);
                        }
                        updateActiveSidebar(new URL(url, window.location.origin).pathname);
                        
                        if (window.Alpine) {
                            window.Alpine.initTree(mainWrapper);
                        }
                        const mainEl = document.getElementById('admin-main');
                        if (mainEl) mainEl.scrollTop = 0;
                    } else {
                        window.location.href = url;
                    }
                } catch (err) {
                    console.error('SPA load error:', err);
                    window.location.href = url;
                } finally {
                    hideLoader();
                }
            }

            // Intercept internal link clicks inside admin
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (!link) return;

                const href = link.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;
                if (link.getAttribute('target') === '_blank') return;
                if (link.hasAttribute('download') || link.getAttribute('data-no-spa') !== null) return;

                const url = new URL(link.href, window.location.origin);
                // Only intercept internal /admin routes
                if (url.origin === window.location.origin && url.pathname.startsWith('/admin')) {
                    e.preventDefault();
                    loadUrl(url.href);
                }
            });

            // Intercept form submissions inside admin-content-wrapper (like Delete, Add, Filters)
            document.addEventListener('submit', async function(e) {
                const form = e.target;
                if (!form.closest('#admin-content-wrapper')) return;
                if (form.hasAttribute('data-no-spa')) return;
                
                // Allow forms handled by Alpine @submit.prevent to run natively
                if (form.getAttribute('@submit.prevent') || form.getAttribute('x-on:submit.prevent')) return;

                const action = form.getAttribute('action') || window.location.href;
                const method = (form.getAttribute('method') || 'GET').toUpperCase();
                const url = new URL(action, window.location.origin);

                if (url.origin !== window.location.origin || !url.pathname.startsWith('/admin')) {
                    return;
                }

                e.preventDefault();
                showLoader();

                try {
                    let res;
                    if (method === 'GET') {
                        const formData = new FormData(form);
                        const params = new URLSearchParams(formData);
                        const targetUrl = url.pathname + (params.toString() ? '?' + params.toString() : '');
                        await loadUrl(targetUrl);
                        return;
                    } else {
                        const formData = new FormData(form);
                        res = await fetch(action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Admin-SPA': '1',
                                'Accept': 'text/html'
                            }
                        });
                    }

                    const finalUrl = res.url || action;
                    const html = await res.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newContent = doc.getElementById('admin-content-wrapper');
                    
                    if (newContent) {
                        if (window.Alpine && typeof window.Alpine.destroyTree === 'function') {
                            window.Alpine.destroyTree(mainWrapper);
                        }
                        mainWrapper.innerHTML = newContent.innerHTML;
                        document.title = doc.title || document.title;
                        window.history.pushState({}, '', finalUrl);
                        updateActiveSidebar(new URL(finalUrl, window.location.origin).pathname);
                        
                        if (window.Alpine) {
                            window.Alpine.initTree(mainWrapper);
                        }
                        const mainEl = document.getElementById('admin-main');
                        if (mainEl) mainEl.scrollTop = 0;
                    } else {
                        window.location.href = finalUrl;
                    }
                } catch (err) {
                    console.error('SPA form submit error:', err);
                    form.submit();
                } finally {
                    hideLoader();
                }
            });

            // Handle browser back and forward buttons
            window.addEventListener('popstate', function() {
                if (window.location.pathname.startsWith('/admin')) {
                    loadUrl(window.location.href, false);
                }
            });
        })();
    </script>
</body>
</html>
