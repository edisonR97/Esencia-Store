<?php

return [
    'name' => 'Esencia Store',
    'slogan' => 'Todo lo esencial, en un solo lugar.',
    'country' => 'Colombia',
    'currency' => 'COP',
    'whatsapp' => env('WHATSAPP_NUMBER', ''),
    'email' => env('STORE_EMAIL', ''),
    'social' => [
        'instagram' => env('STORE_INSTAGRAM', ''),
        'facebook' => env('STORE_FACEBOOK', ''),
        'tiktok' => env('STORE_TIKTOK', ''),
    ],
    'payment_methods' => [
        'nequi' => false,
        'daviplata' => false,
        'transferencia' => false,
        'contra_entrega' => false,
    ],
];
