# assghard Laravel 2FA - Two Factor Authentication package for Laravel

Protect user accounts with Two-Factor Authentication. Send 2FA verification codes for user via E-mail or SMS provider. 
The package is helpful if you don't use Jetstream but need 2FA

## Work In Progress


### Features: 
 * Customiation: package includes a set of helpful stuff using for 2FA verification. You can implement them how you want and customize everything. Every class can be extended and overridden
 * Resent 2FA code: once per minute
 * Flexible config

## Installation
* Run `composer require assghard/laravel-2fa`
* Add provider in `config -> app.php` providers section:
```php
'providers' => [
    TwoFactorVerificationServiceProvider::class,
]
```
* Publish vendor: `php artisan vendor:publish --provider="Assghard\Laravel2fa\TwoFactorVerificationServiceProvider"`
That command will add a few files to your project: 
```php
config/2fa.php #2FA config
migrations/create_user_2fa_codes_table.php
migrations/add_phone_number_field_to_users_table.php
```
* Run command: `php artisan migrate`
* Add Stuff to your User model:

```php
use Assghard\Laravel2fa\Traits\UserTwoFactorVerificationTrait; # Add trait in use section
use Assghard\Laravel2fa\Enums\TwoFactorVerificationMethodsEnum; # available 2FA methods Enum
...

class User extends Authenticatable implements MustVerifyEmail
{
    use UserTwoFactorVerificationTrait; // Use trait for 2FA
    ...

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ # Add fillable fields
        ...
        'phone_number', # only if you are going to send 2FA codes via SMS message
    ];
```

If you are going to assign 2FA method to user and user (or Admin) can change the method: 
```php
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        ...
        'tfa_method' // 2FA method
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        ...
        'tfa_method' => TwoFactorVerificationMethodsEnum::class, // leverage enums for 2FA method casting
    ];
```

* Remenber abount adding variables to `.env` nad `.env.example` files

```
# To enable 2FA for users
2FA_ENABLED=true

# uncomment if you are going to use sending 2FA SMS codes
# SMS_API_TOKEN=
# SMS_API_NAME_FORM=
```

## Components
* Flexible config
```php
    /**
     * Enable 2FA for Users. After enabling this feature 2FA will be required
     */
    'enabled' => env('2FA_ENABLED', false),

    /**
     * Default 2FA method
     * TwoFactorVerificationMethodsEnum::cases()
     */
    'default_method' => TwoFactorVerificationMethodsEnum::Email,

    /**
     * After successful login all user codes are deleting, so user will have limit reseted
     */
    'daily_user_codes_limit' => 25,

    /**
     * Single code valid time in minutes.
     * expires_at = now() + user_code_valid_time
     */
    'user_code_valid_time' => 10,

    'code' => [
        /**
         * Default 2FA code length
         */
        'length' => 6,
    
        /**
         * Default 2FA code length
         */
        'use_letters' => false,
    ],
```

* Migrations
* Translations
* Model
* Enum
* Email and SMS providers
* Traits


## TODO: 

* Replace str() helper by Str Facade to support older versions of Laravel?


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.