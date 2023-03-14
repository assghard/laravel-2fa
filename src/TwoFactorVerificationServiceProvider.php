<?php

namespace Assghard\Laravel2fa;

use Illuminate\Support\ServiceProvider;

class TwoFactorVerificationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishables();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/2fa.php', '2fa');
    }

    protected function registerPublishables(): void
    {
        $this->publishes([
            __DIR__.'/../config/2fa.php' => config_path('2fa.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../lang/en/2fa.php' => lang_path('en/2fa.php'),
        ], 'config');

        if (! class_exists('CreateUser2faCodesTable')) {
            $this->publishes([
                __DIR__.'/../database/migrations/create_user_2fa_codes_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_user_2fa_codes_table.php'),
                __DIR__.'/../database/migrations/add_phone_number_field_to_users_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_add_phone_number_field_to_users_table.php'),
            ], 'migrations');
        }
    }
}