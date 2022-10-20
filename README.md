To protect your account with two-factor authentication

Remenber abount adding variables to .env file
2FA_ENABLED=
SMS_API_TOKEN=




```php
'providers' => [
    TwoFactorVerificationServiceProvider::class,
]
```

`php artisan vendor:publish --provider="Assghard\Laravel2fa\TwoFactorVerificationServiceProvider"`

config/2fa.php
migrations/create_user_2fa_codes_table.php
migrations/add_phone_number_field_to_users_table.php



TODO: 

User.php model
Add `phone_number` field to fillable array
Add `use UserTwoFactorVerificationTrait`;

