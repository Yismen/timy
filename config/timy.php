<?php

return [
    /**
         * When 'with_shift' is set to true, users will not be able to start timers outside that range. 
         * Define the start and end of the shifts. Make sure to provide valid time representing 
     * the start and ending of your shifts.
     * To disable the shift functionality just set the 'with_shift' to false.
     */
    'shift' => [
        'with_shift' => true,
        'starts_at' => '07:45',
        'ends_at' => '17:00',
        'working_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']
    ],
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
        'api' => 'web|auth', // 'api'
        'web' => 'web|auth',
    ],
    /**
     * List of expected roles
     */
    'roles' => [
        'super_admin' => 'timy-super-admin',
        'admin' => 'timy-admin',
        'user' => 'timy-user',
    ],
    /**
     * The email of the user who can access the super admin 
     * pannel, in which roles are not in places.
     */
    'super_admin_email' => env('TIMY_SUPER_USER_EMAIL', null),
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
     * Default seeder. When the table is migrated, these dispositions will be inserted.
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
