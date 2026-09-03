<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Esencia Store | Cuidado personal, bienestar y hogar')</title>
    <meta name="description" content="@yield('description', 'Encuentra productos de cuidado personal, bienestar y uso cotidiano en Esencia Store.')">
    <meta name="theme-color" content="#173d2b">
    <link rel="canonical" href="{{ url()->current() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body>
    <a class="skip-link" href="#contenido">Saltar al contenido</a>
    <div class="announcement">Bienvenido a Esencia Store <span>·</span> Todo lo esencial, en un solo lugar</div>
    <header class="site-header" data-header>
        <div class="container header-row">
            <button class="icon-button mobile-only" type="button" data-menu-open aria-label="Abrir menú">☰</button>
            <a class="brand" href="{{ route('home') }}" aria-label="Esencia Store, inicio">
                <span class="brand-mark">E</span><span>ESENCIA <strong>STORE</strong></span>
            </a>
            <nav class="desktop-nav" aria-label="Navegación principal">
                <a href="{{ route('home') }}">Inicio</a>
                <a href="{{ route('shop') }}">Tienda</a>
                <div class="nav-dropdown">
                    <button type="button">Categorías <span>⌄</span></button>
                    <div class="dropdown-panel">
                        @forelse($navCategories as $category)
                            <a href="{{ route('shop', ['category' => $category->slug]) }}">{{ $category->name }}</a>
                        @empty
                            <span>Catálogo en preparación</span>
                        @endforelse
                    </div>
                </div>
                <a href="{{ route('about') }}">Nosotros</a>
            </nav>
            <div class="header-actions">
                <button class="icon-button" type="button" data-search-open aria-label="Buscar productos">⌕</button>
                <a class="cart-link" href="{{ route('cart.index') }}" aria-label="Carrito con {{ $cartCount }} productos">Bolsa <span>{{ $cartCount }}</span></a>
            </div>
        </div>
    </header>

    <aside class="mobile-menu" data-menu aria-hidden="true">
        <div class="drawer-backdrop" data-menu-close></div>
        <div class="drawer-panel">
            <button class="drawer-close" type="button" data-menu-close aria-label="Cerrar menú">×</button>
            <p class="eyebrow">Esencia Store</p>
            <nav aria-label="Navegación móvil">
                <a href="{{ route('home') }}">Inicio</a><a href="{{ route('shop') }}">Tienda</a>
                <a href="{{ route('about') }}">Nosotros</a><a href="{{ route('faq') }}">Preguntas frecuentes</a>
            </nav>
        </div>
    </aside>

    <section class="search-overlay" data-search aria-hidden="true" role="dialog" aria-modal="true" aria-label="Buscar productos">
        <button class="search-backdrop" data-search-close aria-label="Cerrar búsqueda"></button>
        <div class="search-panel">
            <div class="container search-inner">
                <div class="search-top"><p class="eyebrow">Encuentra tu esencial</p><button data-search-close aria-label="Cerrar">×</button></div>
                <label for="global-search">Busca por producto, código, categoría o marca</label>
                <input id="global-search" type="search" autocomplete="off" placeholder="Ej. shampoo o 1177" data-search-input data-url="{{ route('search') }}">
                <div class="search-results" data-search-results><p>Escribe para comenzar a buscar.</p></div>
            </div>
        </div>
    </section>

    <main id="contenido">@yield('content')</main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div><a class="brand brand-light" href="{{ route('home') }}"><span class="brand-mark">E</span><span>ESENCIA <strong>STORE</strong></span></a><p>{{ config('store.slogan') }}</p></div>
            <div><h2>Tienda</h2><a href="{{ route('shop') }}">Productos</a><a href="{{ route('shop') }}#categorias">Categorías</a></div>
            <div><h2>Información</h2><a href="{{ route('about') }}">Nosotros</a><a href="{{ route('faq') }}">Preguntas frecuentes</a></div>
            <div><h2>Servicio</h2><a href="{{ route('faq') }}">Disponibilidad</a><a href="{{ route('checkout.index') }}">Coordinar pedido</a></div>
        </div>
        <div class="container footer-bottom"><span>© {{ date('Y') }} Esencia Store</span><span>Precios y disponibilidad sujetos a confirmación.</span></div>
    </footer>

    @if(session('toast'))<div class="toast" role="status">{{ session('toast') }}</div>@endif
</body>
</html>
