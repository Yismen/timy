<?php

return [
    /**
     * Here you can specify a list of middleware to apply to all routes. use "," or "|" to separate the list.
     */
    'midlewares' => 'web|auth',
    /**
     * Seeder
     */
    'initial_dispositions' => [
        ['name' => 'On Hold', 'payable' => 0, 'invoiceable' => 0],
        ['name' => 'Working', 'payable' => 1, 'invoiceable' => 1],
        ['name' => 'Training', 'payable' => 1, 'invoiceable' => 1],
        ['name' => 'Coaching', 'payable' => 1, 'invoiceable' => 1],
        ['name' => 'Meeting', 'payable' => 1, 'invoiceable' => 0],
        ['name' => 'Bathroom', 'payable' => 1, 'invoiceable' => 1],
        ['name' => 'Backoffice', 'payable' => 1, 'invoiceable' => 1],
        ['name' => 'Break', 'payable' => 1, 'invoiceable' => 0],
        ['name' => 'Lunch', 'payable' => 0, 'invoiceable' => 0],
    ],
    /**
     * Timy models
     */
    'models' => [
        /**
         * User model. Replace this model by your eloquen User model 'App\Model' and add
         * use the Dainsys\Timy\Models\Timeable trait.
         */
        'user' => App\User::class,
    ]
];
