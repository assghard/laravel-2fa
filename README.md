# assghard Laravel 2FA - Two Factor Authentication package for Laravel

Protect user accounts with Two-Factor Authentication. Send 2FA verification codes for user via E-mail or SMS provider. 

The package is helpful if you don't use Jetstream but need 2FA.

All classes are extendable, so you can extend and override everything and customize and adjust it to the project. 
There in no certain conception or implementation of 2FA. This package provides only a basic set of useful classes and methods, so you can implement 2FA how you want in way you want

### Features: 

 * Customiation: package includes a set of helpful stuff using for 2FA verification. You can implement them how you want and customize everything. Every class can be extended and overridden
 * Resent 2FA code: once per minute
 * Flexible config
 * Every code is valid for X minutes (`config('2fa.user_code_valid_time')`). If user use "Resend" function and recive N codes - every code is valid for X minutes

## Requirements

- Laravel 8.x to 9.x
- PHP >= 8.0

### Laravel and PHP support

| Laravel version | PHP version | Release       | Installation                                  |
|:---------------:|:-----------:|:-------------:|:---------------------------------------------:|
| 10.x            | PHP >=8.1   | WIP           | `composer require assghard/laravel-2fa`       |
| 8.x to 9.X      | PHP >=8.0   | 0.1.2         | `composer require assghard/laravel-2fa:0.1.2` |



## Installation

## Installation and usage

* Install latest release: run `composer require assghard/laravel-2fa`

    - To install older version run: `composer require assghard/laravel-2fa:VERSION`

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

# migrations
migrations/create_user_2fa_codes_table.php
migrations/add_phone_number_field_to_users_table.php

# translations: add or delete languages you don't need
lang/en/2fa.php
lang/pl/2fa.php

```
* Remenber abount adding variables to `.env` nad `.env.example` files

```
# To enable 2FA for users
2FA_ENABLED=true

# uncomment if you are going to use sending 2FA SMS codes
# SMS_API_TOKEN=
# SMS_API_NAME_FORM=
```

* Run command: `php artisan migrate`
* Add stuff to your User model:

```php
/*
 * =========================================
 * Basic usage
 * =========================================
 */

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

* Send a 2FA code after user is successfully logged in.

`App\Http\Controllers\Auth\AuthenticatedSessionController`
```php
use Assghard\Laravel2fa\Services\TwoFactorVerificationService;
use Assghard\Laravel2fa\Enums\TwoFactorVerificationMethodsEnum;
...
class AuthenticatedSessionController extends Controller
{
    ...

    $sent = (new TwoFactorVerificationService())->sendUserTwoFactorVerificationCode($user, $verificationMethodFromEnum);
    dd($sent);
    // And do everything you want after sending code

```

* You will need a Controller: `php artisan make:controller Auth\TwoFactroVerificationAuthController`
* Also you will need x3 routes for 2FA verification. Add them to Laravel `auth.php` routes.

```php
    Route::middleware('auth')->group(function () {
        ...
        Route::group(['prefix' => '2fa'], function () {
            Route::get('verify', [TwoFactroVerificationAuthController::class, 'verify'])->name('2fa.verify');
            Route::post('verify', [TwoFactroVerificationAuthController::class, 'confirm'])->name('2fa.verify.confirm')->middleware('throttle:2fa_verify_confirm');
            Route::get('resend', [TwoFactroVerificationAuthController::class, 'resend'])->name('2fa.resend-code');
    });
```
* Make a new `2fa_verify_confirm` throttle in project `RouteServiceProvider`

```php
    // 2fa_verify_confirm is a name of throttle and middleware
    RateLimiter::for('2fa_verify_confirm', function (Request $request) {
        return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
    });
```

* Probably, you will need a middleware to check if code is verified. 

Run command `php artisan make:middleware User2faCodeVerified`
```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class User2faCodeVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // And do something like this or not :)
        // Database, session or other approach - the choice is yours

        $user = auth()->user();
        if ($user->two_factor_verification_codes()->count() > 0) {
            return redirect()->route('2fa.verify');
        }
```

If you are going to assign 2FA method to user and user (or Admin) can change the method (in this case remember to create a migration for `tfa_method` field): 
```php
/*
 * =========================================
 * NOT Basic usage (Example of customization)
 * =========================================
 */

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

## Package components

* Migration stubs
* Translations
* Model
* Enum
* Email and SMS providers
* Traits
* Notification providers
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

    /**
     * SMS API config
     */
    'sms' => [
        /**
         * API token from https://www.smsapi.com/en
         */
        'api_token' => env('SMS_API_TOKEN', null),

        /**
         * Sender name
         */
        'name_from' => env('SMS_API_NAME_FORM', null),
    ],
```

## TODO list: 

* Testing
* Refactoring
* Testing
* Replace str() helper by Str Facade to support older versions of Laravel?
* Add user IP address field to `user_2fa_codes` table? Make code is valid only for certain IP address?


## Bugs and suggestions

If you find a bug, please open an issue or fork this project and make Pull request

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.