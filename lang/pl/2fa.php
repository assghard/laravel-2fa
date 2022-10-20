<?php

return [
    'methods' => [
        'Email' => 'Uwierzytelnianie dwuetapowe używając adresu e-mail',
        'Sms' => 'Uwierzytelnianie dwuetapowe używając numeru telefonu',
    ],
    'descriptions' => [
        'Email' => 'Kod weryfikacji zostanie wysłany na Twój adres email',
        'Sms' => 'Kod weryfikacji zostanie wysłany na Twój numer telefonu w postaci SMS',
    ],
    'messages' => [
        '2fa_code' => 'Twój kod weryfikacji dwuetapowej to ',
        '2fa_email_subject' => 'Kod weryfikacji dwuetapowej (2FA)',
    ]
];