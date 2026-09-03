@extends('layouts.store')
@section('title', $product->name.' | Esencia Store')
@section('description', $product->short_description ?: 'Conoce '.$product->name.' en Esencia Store.')
@section('content')
<div class="container breadcrumbs"><a href="{{ route('home') }}">Inicio</a><span>/</span><a href="{{ route('shop', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a><span>/</span><span>{{ $product->name }}</span></div>
<section class="product-detail"><div class="container product-detail-grid">
    <div class="gallery-main">@if($product->image)<img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" width="800" height="900">@else<span class="placeholder-mark">ES</span><p>Imagen próximamente</p>@endif</div>
    <div class="product-info"><p class="eyebrow">{{ $product->brand ?: 'HGW' }} @if($product->code) · Cód. {{ $product->code }} @endif</p><h1>{{ $product->name }}</h1>
        @if($product->short_description)<p class="product-intro">{{ $product->short_description }}</p>@endif
        <p class="detail-price">{{ $product->price ? '$ '.number_format($product->price, 0, ',', '.') : 'Precio por confirmar' }}</p>
        @if($product->presentation || $product->net_content)<dl class="product-facts">@if($product->presentation)<div><dt>Presentación</dt><dd>{{ $product->presentation }}</dd></div>@endif @if($product->net_content)<div><dt>Contenido neto</dt><dd>{{ $product->net_content }}</dd></div>@endif</dl>@endif
        <div class="availability-box"><i></i><div><strong>Disponibilidad sujeta a confirmación</strong><span>Confirmaremos este producto al coordinar tu pedido.</span></div></div>
        <form class="add-form" method="post" action="{{ route('cart.store', $product) }}">@csrf<label>Cantidad<input type="number" name="quantity" value="1" min="1" max="99" required></label><button class="button button-primary" type="submit">Agregar al carrito</button><button class="button button-ghost button-full buy-now" type="submit" name="buy_now" value="1">Comprar ahora</button></form>
        <p class="source-note">Referencia interna: página {{ $product->source_page }} del catálogo.</p>
    </div>
</div></section>
@php($sections = ['Descripción' => $product->description, 'Ingredientes' => $product->ingredients, 'Modo de uso' => $product->usage, 'Precauciones' => $product->precautions])
@if(collect($sections)->filter()->isNotEmpty() || $product->catalog_benefits)<section class="product-content"><div class="container info-grid">@foreach($sections as $title => $content)@if($content)<article><h2>{{ $title }}</h2><p>{{ $content }}</p></article>@endif @endforeach @if($product->catalog_benefits)<article><h2>Beneficios del catálogo</h2><ul>@foreach($product->catalog_benefits as $benefit)<li>{{ $benefit }}</li>@endforeach</ul><small>Información proporcionada en el catálogo del fabricante.</small></article>@endif</div></section>@endif
@if($related->isNotEmpty())<section class="section"><div class="container"><div class="section-heading"><div><p class="eyebrow">Sigue explorando</p><h2>También te puede interesar</h2></div></div><div class="product-grid">@foreach($related as $item)<x-product-card :product="$item" />@endforeach</div></div></section>@endif
@endsection
@push('head')<script type="application/ld+json">{!! json_encode(array_filter(['@context'=>'https://schema.org','@type'=>'Product','name'=>$product->name,'sku'=>$product->code,'description'=>$product->short_description,'brand'=>$product->brand ? ['@type'=>'Brand','name'=>$product->brand] : null,'offers'=>$product->price ? ['@type'=>'Offer','priceCurrency'=>'COP','price'=>$product->price,'availability'=>'https://schema.org/LimitedAvailability'] : null]), JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!}</script>@endpush
