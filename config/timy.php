<?php

return [
    /**
     * Indicate if the web routes will be activated. Set it to false 
     * if you are planning to use your own routes and compile the 
     * components withing your front end.
     */
    'with_web_routes' => true,
    /**
         * Here you can specify a list of middleware to apply to 
         * all routes. use "," or "|" to separate the list.
         */
    'midlewares' => [
        'api' => 'web|auth',
        'web' => 'web|auth',
    ],
    /**
     * The email of the user who can access the super admin 
     * pannel, in which roles are not in places.
     */
    'super_admin' => [
        'email' => env('TIMY_SUPER_USER_EMAIL', null),
        'role' => 'timy_super_admin',
    ],
    /**
     * Timy models
     */
    'models' => [
        /**
         * User model. Replace this model by your eloquen User model 'App\Model' and add
         * use the Dainsys\Timy\Timeable trait.
         */
        'user' => App\User::class,
    ],
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
    ]
];
