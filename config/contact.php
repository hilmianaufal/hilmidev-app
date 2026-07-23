<?php

return [
    'whatsapp' => [
        /*
        |--------------------------------------------------------------------------
        | Nomor WhatsApp
        |--------------------------------------------------------------------------
        |
        | Gunakan format internasional tanpa tanda +, spasi, atau strip.
        | Contoh nomor Indonesia: 6281234567890
        |
        */
        'number' => env('WHATSAPP_NUMBER', ''),

        'message' => env(
            'WHATSAPP_MESSAGE',
            'Halo HilmiDev, saya ingin berkonsultasi mengenai website, source code, atau membership.'
        ),

        'label' => env(
            'WHATSAPP_LABEL',
            'Chat WhatsApp'
        ),
    ],
];
