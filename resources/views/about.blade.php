@extends('layouts.store')
@section('title', 'Nosotros | Esencia Store')
@section('content')
<section class="story-hero"><div class="container story-grid"><div><p class="eyebrow">Nuestra esencia</p><h1>Lo esencial, elegido con intención.</h1><p>Creamos Esencia Store para reunir en un solo lugar productos de cuidado personal, bienestar y uso cotidiano, con una experiencia de compra sencilla y cercana.</p><a class="button button-primary" href="{{ route('shop') }}">Conocer productos</a></div><div class="story-art"><span>ES</span><p>Todo lo esencial,<br>en un solo lugar.</p></div></div></section>
<section class="section"><div class="container values-grid"><article><span>01</span><h2>Claridad</h2><p>Presentamos la información disponible en el catálogo sin inventar promesas, precios ni características.</p></article><article><span>02</span><h2>Cercanía</h2><p>Cada pedido se confirma personalmente para validar disponibilidad y coordinar la entrega.</p></article><article><span>03</span><h2>Sencillez</h2><p>Diseñamos una experiencia clara para encontrar, comparar y solicitar productos fácilmente.</p></article></div></section>
@endsection
