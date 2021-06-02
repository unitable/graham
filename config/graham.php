<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Billable Model
    |--------------------------------------------------------------------------
    */

    'model' => env('GRAHAM_MODEL', \App\Models\User::class),

    /*
    |--------------------------------------------------------------------------
    | Currency
    |--------------------------------------------------------------------------
    |
    | This is the default currency that will be used when generating charges
    | from your application.
    |
    */

    'currency' => env('GRAHAM_CURRENCY', 'USD'),

    /*
    |--------------------------------------------------------------------------
    | Intent days
    |--------------------------------------------------------------------------
    |
    | The wait time in days before cancel an intent subscription.
    |
    */

    'intent_days' => 7,

    /*
    |--------------------------------------------------------------------------
    | Renewal days
    |--------------------------------------------------------------------------
    |
    | The wait time in days before create a new renewal invoice.
    |
    */

    'renewal_days' => 7,

    /*
    |--------------------------------------------------------------------------
    | Incomplete days
    |--------------------------------------------------------------------------
    |
    | The wait time in days before cancel an incomplete subscription.
    | Be careful about processing payments at weekends.
    |
    */

    'incomplete_days' => 7

];
