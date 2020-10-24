# Dainsys Time Tracker Package
Add user's time tracker functionality to Laravel 7, Livewire and Bootstrap 4.

## Installation
1. Install with composer: `composer require dainsys/timy2`.
    >   Optional: The Package should be auto-discovered by Laravel. However, you could all register it in your config.app file within the providers array:
    > ````php
    >     'providers' => [
    >         Dainsys\Timy\TimyServiceProvider::class,
    >     ]
    > ````
    > You may want to publish the config file: `php artisan vendor:publish --tag=timy-config` to change default configuration. Pay attention to the option of creating default dispositions. 
1. Next you may want to run migrations with command `php artisan migrate`. 
    > You could also publish the migrations with `php artisan vendor:publish --tag=tiy-migrations` and update them before migrating.
1. Add the `use Dainsys\Timy\Timeable` trait to your `User` model. 
    ````javascript
    use Dainsys\Timy\Timeable;
    class User extends Authenticatable
    {
        use Timeable;
    }
    ````
    > This package relies on `laravel/ui` to handle authentication. Follow it's  installation guide from https://laravel.com/docs/7.x/authentication. We recommend to running the following command: `php artisan ui --auth vue`.
1. As required per `laravel/livewire`, make sure you update your layout view:
    ````javascript
        @livewireStyles
    </head>
    <body>
        ...
        @stack('scripts')
        @livewireScripts
    </body>
    </html>
    ````
1. Update your `package.json` file with the follow dependencies:
    ````javascript
            "laravel-echo": "^1.8.0",
            "pusher-js": "^6.0.3",
    ````
    > Then install the front end dependencies and compile: `npm install && npm run dev`.
1. Make sure the `App\Providers\BroadcastServiceProvider::class` is uncommented in the `config.app` file.
1. Next paste the following routes in your `routes\channels.php` file:
    ````javascript
    Broadcast::channel('Timy.User.{id}', function ($user, $id) {
        return (int) $user->id == (int) $id;
    });
    
    Broadcast::channel('Timy.Admin', function ($user) {
        return \Illuminate\Support\Facades\Gate::allows(config('timy.roles.admin'));
    });
    ```` 
1. Include the timy menu in your main nav-bar after you check for logged in users: `@include('timy::_timy-menu')`. 
    > Alternatively you can link to the following endpoints:
    > Users: URL=`/timy/user`, NAME=`user_dashboard`, GATEWAY(blade @can directive)=`timy-user`
    > Admin Users: URL=`/timy/admin`, NAME=`admin_dashboard`, GATEWAY(blade @can directive)=`timy-admin`
    > Super Admin User: URL=`/timy/super_admin`, NAME=`supepr_admin_dashboard`, GATEWAY(blade @can directive)=`timy-super-admin`
1. Next, define the Super User in you .env file by providing its email in the variable `TIMY_SUPER_USER_EMAIL=` . This user will have plenty control of the app.
1. Next get your Pusher's credentials from https://dashboard.pusher.com/apps and use them to define the following variables in your .env file. BE CERTAIN YOU SET YOUR `BROADCAST_DRIVER` VARIABLE TO `pusher`:
    ````javascript
    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=
    PUSHER_APP_KEY=
    PUSHER_APP_SECRET=
    PUSHER_APP_CLUSTER=
    
    MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    ````
1. This package runs an artisan command every 5 minutos that checks if users ip is still alive. 
    > MAKE SURE your server is running a `cron job` as suggested in Laravel documentation `https://laravel.com/docs/7.x/scheduling#introduction`, Starting The schedule session: `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
## Features
- Authenticated users is required for the package to work. We leverage that on `laravel/ui` package. 
- Users and admin shoud have valid roles assigned to them. 
- When session is started, all previously opened timers are closed and a new one is opened.
- Only Super Admin can manage roles.
- Admin controller is protected and only work for users with `timy-admin` role assigned to them.
- On page load:
    - If the user has a timer running for a specifig disposition, upon closing that one, a new timer is started using that same disposition. 
    - Even if there are not timers running, previous disposition is used to start a new timer on page reload.
    - If both previous logic fails, by default the dispo set in the config is used to start a new timer.
- If a user change the Dispositions dropdown (Vue component) a new timer is created, closing all previous.
- When an user change their dispo, the admin dashboar update dinamically.
- When admin update a scpecific user's dispo, user interface update and the user is alerted. 
