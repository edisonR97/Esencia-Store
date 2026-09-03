@extends('layouts.store')

@section('content')
<section class="hero">
    <div class="hero-orb orb-one"></div><div class="hero-orb orb-two"></div>
    <div class="container hero-grid">
        <div class="hero-copy">
            <p class="eyebrow">Cuidado · Bienestar · Hogar</p>
            <h1>Esenciales para sentirte bien <em>cada día.</em></h1>
            <p class="hero-lead">Descubre productos para tu cuidado personal, bienestar y hogar, reunidos en un solo lugar.</p>
            <div class="button-row"><a class="button button-primary" href="{{ route('shop') }}">Explorar productos <span>→</span></a><a class="button button-ghost" href="#categorias">Ver categorías</a></div>
            <div class="hero-note"><span>Selección basada en catálogo</span><span>Pedido coordinado personalmente</span></div>
        </div>
        <div class="hero-art" aria-label="Selección Esencia Store">
            <div class="art-card art-card-back"><span>BIENESTAR</span></div>
            <div class="art-card art-card-main"><span class="art-kicker">Una selección para ti</span><strong>Tu rutina,<br>más esencial.</strong><span class="art-leaf">⌁</span></div>
            <div class="floating-label"><i></i><span>Catálogo HGW<br><strong>2026</strong></span></div>
        </div>
    </div>
</section>

<section class="trust-strip"><div class="container trust-grid"><div><strong>Selección cuidada</strong><span>Productos del catálogo verificado</span></div><div><strong>Compra sencilla</strong><span>Arma tu pedido sin complicaciones</span></div><div><strong>Atención cercana</strong><span>Confirmamos disponibilidad contigo</span></div></div></section>

<section class="section" id="categorias"><div class="container"><div class="section-heading"><div><p class="eyebrow">Explora a tu manera</p><h2>Encuentra lo que necesitas</h2></div><p>Productos organizados para acompañar tus rutinas cotidianas.</p></div>
    <div class="category-grid">
        @forelse($categories as $category)<a class="category-card" href="{{ route('shop', ['category' => $category->slug]) }}"><span class="category-index">0{{ $loop->iteration }}</span><div><h3>{{ $category->name }}</h3><p>{{ $category->products_count }} {{ Str::plural('producto', $category->products_count) }}</p></div><span class="round-arrow">↗</span></a>
        @empty <div class="empty-inline">Las categorías aparecerán al finalizar la extracción verificada del catálogo.</div>@endforelse
    </div>
</div></section>

@if($featured->isNotEmpty())<section class="section surface"><div class="container"><div class="section-heading"><div><p class="eyebrow">Selección Esencia</p><h2>Productos destacados</h2></div><a class="text-link" href="{{ route('shop') }}">Ver todo →</a></div><div class="product-grid">@foreach($featured as $product)<x-product-card :product="$product" />@endforeach</div></div></section>@endif

<section class="editorial"><div class="container editorial-inner"><div><p class="eyebrow">Pequeños rituales</p><h2>Cuidarte puede ser sencillo.</h2><p>Encuentra productos para acompañar tus rutinas de cuidado, bienestar y hogar.</p><a class="button button-light" href="{{ route('shop') }}">Descubrir el catálogo</a></div><div class="editorial-shape"><span>ESENCIA</span><strong>Lo cotidiano<br>también inspira.</strong></div></div></section>

@if($latest->isNotEmpty())<section class="section"><div class="container"><div class="section-heading"><div><p class="eyebrow">Del catálogo</p><h2>Más para descubrir</h2></div></div><div class="product-grid">@foreach($latest as $product)<x-product-card :product="$product" />@endforeach</div></div></section>@endif

<section class="cta-section"><div class="container cta-card"><div><p class="eyebrow">Todo en un solo lugar</p><h2>Explora el catálogo completo.</h2></div><a class="button button-primary" href="{{ route('shop') }}">Ir a la tienda →</a></div></section>
@endsection
