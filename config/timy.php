<?php

return [
    /**
         * ====================================================
         * Timy Package Config Options
         * ====================================================
         */

    /**
     * The id of the disposition to which the controller will default when no other is set. Usually it is an 
     * initla state, not payable or invoiceable disposition.
     */
    'default_disposition_id' => 1,
    /**
     * When 'with_shift' is set to true, users will not be able to start timers outside that range. 
     * Define the start and end of the shifts. Make sure to provide valid time representing 
     * the start and ending of your shifts.
     * To disable the shift functionality just set the 'with_shift' to false.
     */
    'shift' => [
        'with_shift' => env('TIMY_WITH_SHIFT', true),
        'starts_at' => '08:00',
        'ends_at' => '17:00',
        'working_days' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri']
    ],
    /**
     * @type Boolean. If set to true, will register a command that would check the timers ip to
     * see if they are still alive.
     */
    'with_scheduled_commands' => env('TIMY_WITH_SCHEDULED_COMMANDS', false),
    /**
     * The amount of seconds to wait for an IP Address to respond when checking if alive.
     */
    'ip_timeout_in_seconds' => 5,
    /**
     * Default layout to extend in production.
     */
    'layout' => 'layouts.app',
    /**
     * Scripts Stack.
     */
    'scripts_stack' => 'scripts',
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
