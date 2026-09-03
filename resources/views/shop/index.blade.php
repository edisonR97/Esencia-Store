@extends('layouts.store')
@section('title', 'Tienda | Esencia Store')
@section('content')
<section class="page-hero"><div class="container"><p class="eyebrow">Catálogo HGW 2026</p><h1>Encuentra tu próximo esencial.</h1><p>Explora productos de cuidado, bienestar y uso cotidiano.</p></div></section>
<section class="shop-section"><div class="container shop-layout">
    <div class="filters-backdrop" data-filter-close aria-hidden="true"></div>
    <aside class="filters" data-filters aria-label="Filtros de productos"><div class="filter-title"><strong>Filtrar productos</strong><button type="button" data-filter-close aria-label="Cerrar filtros">×</button></div>
        <form method="get" action="{{ route('shop') }}" data-filter-form>
            <label class="filter-search">Buscar<input name="q" value="{{ request('q') }}" placeholder="Nombre o código"></label>
            <fieldset><legend>Categoría</legend><label><input type="radio" name="category" value="" @checked(!request('category'))> Todas</label>@foreach($categories as $category)<label><input type="radio" name="category" value="{{ $category->slug }}" @checked(request('category') === $category->slug)> {{ $category->name }} <small>{{ $category->products_count }}</small></label>@endforeach</fieldset>
            @if($brands->isNotEmpty())<label>Marca<select name="brand"><option value="">Todas</option>@foreach($brands as $brand)<option @selected(request('brand') === $brand)>{{ $brand }}</option>@endforeach</select></label>@endif
            <fieldset><legend>Precio</legend><div class="price-inputs"><input type="number" name="min_price" min="0" value="{{ request('min_price') }}" placeholder="Mínimo"><input type="number" name="max_price" min="0" value="{{ request('max_price') }}" placeholder="Máximo"></div></fieldset>
            <label>Disponibilidad<select name="availability"><option value="">Todas</option><option value="confirm" @selected(request('availability') === 'confirm')>Sujeta a confirmación</option><option value="coming_soon" @selected(request('availability') === 'coming_soon')>Próximamente</option></select></label>
            <button class="button button-primary button-full">Aplicar filtros</button><a class="clear-filters" href="{{ route('shop') }}">Limpiar filtros</a>
        </form>
    </aside>
    <div class="shop-results">
        <div class="shop-toolbar"><div><button class="filter-trigger" type="button" data-filter-open>☷ Filtros</button><strong>{{ $products->total() }}</strong> resultados</div><form method="get">@foreach(request()->except('sort', 'page') as $key => $value)<input type="hidden" name="{{ $key }}" value="{{ $value }}">@endforeach<label>Ordenar <select name="sort" onchange="this.form.submit()"><option value="">Recomendados</option><option value="name" @selected(request('sort') === 'name')>Nombre A–Z</option><option value="price-asc" @selected(request('sort') === 'price-asc')>Precio menor</option><option value="price-desc" @selected(request('sort') === 'price-desc')>Precio mayor</option></select></label></form></div>
        @if($products->isNotEmpty())<div class="product-grid">@foreach($products as $product)<x-product-card :product="$product" />@endforeach</div>{{ $products->links() }}
        @else
            <div class="empty-state">
                <span>⌕</span>
                <h2>No encontramos productos{{ request('q') ? ' para “'.request('q').'”' : '' }}.</h2>
                <p>Revisa la búsqueda o explora nuestras categorías.</p>
                <a class="button button-primary" href="{{ route('shop') }}">Ver todos los productos</a>
            </div>
        @endif
    </div>
</div></section>
@endsection
