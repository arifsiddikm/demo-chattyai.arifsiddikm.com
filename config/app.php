<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => env('APP_NAME', 'Chatty AI'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => 'Asia/Jakarta',

    // FIX: setLocale error — gunakan 'id' bukan 'id_ID'
    'locale' => env('APP_LOCALE', 'id'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'id_ID'),

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => 'file',
    ],

    'providers' => Illuminate\Support\ServiceProvider::defaultProviders()->merge([
        // App\Providers\AppServiceProvider::class,
    ])->toArray(),

];
