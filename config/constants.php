<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stripe
    |--------------------------------------------------------------------------
    |
    | Stripe publishable key
    | Stripe secret key
    |
    |
    */

    'STRIPE_SECRET_KEY' => env('STRIPE_SECRET_KEY', ''),
    'STRIPE_PUBLISHABLE_KEY' => env('STRIPE_PUBLISHABLE_KEY', ''),

];
