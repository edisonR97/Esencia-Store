@props(['product'])
<article class="product-card">
    <a class="product-image" href="{{ route('products.show', $product) }}">
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" loading="lazy" width="520" height="620">
        @else
            <span class="placeholder-mark">ES</span><span>Imagen próximamente</span>
        @endif
    </a>
    <div class="product-body">
        <p class="product-meta">{{ $product->brand ?: 'HGW' }} @if($product->code) · Cód. {{ $product->code }} @endif</p>
        <h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3>
        @if($product->presentation)<p class="presentation">{{ $product->presentation }}</p>@endif
        <div class="product-price-row">
            <strong>{{ $product->price ? '$ '.number_format($product->price, 0, ',', '.') : 'Precio por confirmar' }}</strong>
            <form method="post" action="{{ route('cart.store', $product) }}">@csrf<button type="submit" aria-label="Agregar {{ $product->name }} al carrito">+</button></form>
        </div>
        <p class="availability"><i></i> Disponibilidad sujeta a confirmación</p>
    </div>
</article>
