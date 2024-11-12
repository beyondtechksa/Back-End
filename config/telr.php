<?php
return [
    // The current mode is live|production or test
    'test_mode' => 1,

    // The currency of store

    'currency' => 'SAR',

    // The sale endpoint that receive the params
    // @see https://telr.com/support/knowledge-base/hosted-payment-page-integration-guide
    'sale' => [
        'endpoint' => 'https://secure.telr.com/gateway/order.json',
    ],

    // The hosted payment page use the following params as it explained in the integration guide
    // @see https://telr.com/support/knowledge-base/hosted-payment-page-integration-guide/#request-method-and-format
    'create' => [
        'ivp_method' => "create",
        'ivp_store' => config('app.store'),
        'ivp_authkey' => config('app.authKey'),
        'return_auth' => '/cart/checkout/payment',
        'return_can' => '/cart/checkout/payment',
        'return_decl' => '/cart/checkout/payment',
        'ivp_cart' => '',
    ]
];
