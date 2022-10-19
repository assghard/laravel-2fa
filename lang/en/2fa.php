<?php

return [
    'methods' => [
        'Email' => 'Two factor verification via e-mail address',
        'Sms' => 'Two factor verification via phone number',
    ],
    'descriptions' => [
        'Email' => 'Verification code will be sent to your email address',
        'Sms' => 'Verification code will be sent to your phone number in SMS message',
    ],
    'messages' => [
        '2fa_code' => 'Your 2FA verification code is ',
        '2fa_email_subject' => '2FA verification code',
    ]
];