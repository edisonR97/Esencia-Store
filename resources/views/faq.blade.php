@extends('layouts.store')
@section('title', 'Preguntas frecuentes | Esencia Store')
@section('content')
<section class="page-hero"><div class="container"><p class="eyebrow">Estamos para ayudarte</p><h1>Preguntas frecuentes</h1><p>Respuestas claras para comprar con tranquilidad.</p></div></section>
<section class="section"><div class="container faq-list">
@foreach([
['¿Cómo realizo un pedido?', 'Agrega los productos al carrito, completa tus datos y envía la solicitud por WhatsApp. Revisaremos contigo los detalles antes de confirmar.'],
['¿Cómo confirmo la disponibilidad?', 'La disponibilidad se valida al recibir tu solicitud. La tienda no muestra cantidades de inventario que no hayan sido verificadas.'],
['¿Cómo se coordina el envío?', 'El método, plazo y valor del envío se acuerdan contigo según la ubicación y los productos solicitados.'],
['¿Qué métodos de pago están disponibles?', 'Los métodos de pago se informarán durante la confirmación del pedido. No publicamos cuentas ni opciones que aún no estén configuradas.'],
['¿Cómo puedo comunicarme con Esencia Store?', config('store.whatsapp') ? 'Puedes iniciar tu pedido mediante el botón de WhatsApp en el checkout.' : 'El canal de WhatsApp está pendiente de configuración por parte de la tienda.'],
] as [$question, $answer])<details><summary>{{ $question }}<span>+</span></summary><p>{{ $answer }}</p></details>@endforeach
</div></section>
@endsection
