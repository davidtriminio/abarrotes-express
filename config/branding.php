<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Brand Name
    |--------------------------------------------------------------------------
    |
    | Falls back to APP_NAME so existing config('app.name') usages elsewhere
    | (mail templates, etc.) stay in sync automatically. Override BRAND_NAME
    | in .env only if the storefront name must differ from the app name.
    |
    */

    'name' => env('BRAND_NAME', env('APP_NAME', 'Laravel')),

    'tagline' => env('BRAND_TAGLINE', 'Tu tienda en línea de confianza'),

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    |
    | Paths are relative to the "public" directory, same convention as the
    | files already under public/imagen. Resolve them with asset().
    |
    */

    'logo' => env('BRAND_LOGO_PATH', 'imagen/logo.png'),
    'logo_admin' => env('BRAND_LOGO_ADMIN_PATH', 'imagen/logo-admin.png'),
    'favicon' => env('BRAND_FAVICON_PATH', 'imagen/favicon.ico'),
    'favicon_admin' => env('BRAND_FAVICON_ADMIN_PATH', 'imagen/favicon-admin.ico'),

    'primary_color' => env('BRAND_PRIMARY_COLOR', '#3B82F6'),

    'contact' => [
        'email' => env('BRAND_CONTACT_EMAIL'),
        'phone' => env('BRAND_CONTACT_PHONE'),
        'address' => env('BRAND_ADDRESS'),
    ],

];
