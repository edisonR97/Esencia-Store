<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request, CartController $cartController): View
    {
        $buyNow = $request->string('mode')->toString() === 'buy-now';

        return view('checkout.index', [
            'cart' => $buyNow ? $cartController->buyNowContents() : $cartController->contents(),
            'checkoutMode' => $buyNow ? 'buy-now' : 'cart',
        ]);
    }

    public function store(Request $request, CartController $cartController): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:120'],
            'phone' => ['required', 'regex:/^[0-9+()\s-]{7,20}$/'],
            'email' => ['nullable', 'email', 'max:160'],
            'department' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:180'],
            'neighborhood' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
            'checkout_mode' => ['nullable', 'in:cart,buy-now'],
        ], ['phone.regex' => 'Ingresa un número de teléfono válido.']);

        $buyNow = ($data['checkout_mode'] ?? 'cart') === 'buy-now';
        $cart = $buyNow ? $cartController->buyNowContents() : $cartController->contents();
        abort_if($cart['items']->isEmpty(), 422, 'El carrito está vacío.');
        $number = preg_replace('/\D+/', '', (string) config('store.whatsapp'));
        if (! $number) {
            return back()->withInput()->withErrors(['checkout' => 'Configura WHATSAPP_NUMBER en el archivo .env para enviar el pedido.']);
        }

        $lines = [
            '*ESENCIA STORE | SOLICITUD DE PEDIDO*',
            '================================',
            '',
            'Hola, equipo de Esencia Store.',
            'Mi nombre es *'.$data['name'].'* y deseo solicitar la confirmación de este pedido.',
            '',
            'Fecha de solicitud: '.now('America/Bogota')->format('d/m/Y - h:i A'),
            '',
            '*PRODUCTOS SOLICITADOS*',
            '--------------------------------',
        ];
        foreach ($cart['items'] as $index => $item) {
            $product = $item['product'];
            $lines[] = '*'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT).' | '.$product->name.'*';
            $lines[] = 'Código: '.($product->code ?: 'No disponible');
            $lines[] = 'Cantidad: '.$item['quantity'].' '.($item['quantity'] === 1 ? 'unidad' : 'unidades');
            $lines[] = 'Valor unitario: '.($product->price ? '$ '.number_format($product->price, 0, ',', '.').' COP' : 'Por confirmar');
            $lines[] = '*Subtotal: '.($product->price ? '$ '.number_format($item['lineTotal'], 0, ',', '.').' COP' : 'Por confirmar').'*';
            $lines[] = '';
        }
        array_push($lines,
            '*RESUMEN DEL PEDIDO*',
            '--------------------------------',
            'Total de unidades: '.$cart['count'],
            'Subtotal de productos: $ '.number_format($cart['subtotal'], 0, ',', '.').' COP',
            'Costo de envío: Por confirmar',
            '*TOTAL PROVISIONAL: $ '.number_format($cart['subtotal'], 0, ',', '.').' COP*',
            '_El total final puede variar según el costo de envío._',
            '',
            '*INFORMACIÓN DE CONTACTO*',
            '--------------------------------',
            'Cliente: '.$data['name'],
            'Teléfono: '.$data['phone'],
            'Correo: '.($data['email'] ?? 'No indicado'),
            '',
            '*INFORMACIÓN DE ENTREGA*',
            '--------------------------------',
            'Departamento: '.$data['department'],
            'Ciudad: '.$data['city'],
            'Dirección: '.$data['address'],
            'Barrio: '.($data['neighborhood'] ?? 'No indicado'),
            'Indicaciones: '.($data['notes'] ?? 'Ninguna'),
            '',
            '*AGRADEZCO CONFIRMAR:*',
            '1. Disponibilidad de los productos',
            '2. Costo de envío',
            '3. Valor total definitivo',
            '4. Medios de pago disponibles',
            '5. Fecha estimada de entrega',
            '',
            'Quedo atento(a) a su confirmación para continuar con la compra.',
            'Muchas gracias.');

        if ($buyNow) {
            session()->forget('buy_now');
        }

        return redirect()->away('https://wa.me/'.$number.'?text='.rawurlencode(implode("\n", $lines)));
    }
}
