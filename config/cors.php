<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:3000'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    // Expõe X-New-Token para o frontend ler via JS após renovação via cookie
    'exposed_headers' => ['X-New-Token'],

    'max_age' => 0,

    // Necessário para o browser enviar/receber cookies HTTP-Only entre domínios
    'supports_credentials' => true,
];
