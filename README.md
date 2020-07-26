# Dainsys Time Tracker Package
Add user's time tracker functionality to Laravel 7, VueJs 2 and Bootstrap 4.

## Installation
1. Install with composer: `composer require dainsys/timy`.
- Optional: The Package should be auto-discovered by Laravel. However, you could all register it in your config.app file within the providers array:
````php
    'providers' => [
        Dainsys\Timy\TimyServiceProvider::class,
    ]
````
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
> This package relies on `laravel/ui` to handle authentication. Follow it's  installation guide from https://laravel.com/docs/7.x/authentication
1. Make sure the `App\Providers\BroadcastServiceProvider::class` is uncommented in the `app.config` file.
1. Next paste the following routes in your `routes\channels.php` file:
````javascript
Broadcast::channel('Timy.User.{id}', function ($user, $id) {
    return (int) $user->id == (int) $id;
});
Broadcast::channel('Timy.Admin', function ($user) {
    return Gate::allows(config('timy.roles.admin'));
});
````
1. Include the timy menu in your main nav-bar after you check for logged in users: `@include('timy::_timy-menu')`. Alternatively you can link to the following endpoints:
> Users: URL=`/timy/user`, NAME=`user_dashboard`, GATEWAY(blade @can directive)=`timy-user`
> Admin Users: URL=`/timy/admin`, NAME=`admin_dashboard`, GATEWAY(blade @can directive)=`timy-admin`
> Super Admin User: URL=`/timy/super_admin`, NAME=`supepr_admin_dashboard`, GATEWAY(blade @can directive)=`timy-super-admin`
1. Next, define the Super User in you .env file by providing its email in the variable `TIMY_SUPER_USER_EMAIL=` . This user will have plenty control of the app.
1. Next get your Pusher's credentials from https://dashboard.pusher.com/apps and use them to define the following variables in your .env file:
````javascript
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
````
1. Publish the vue components by running the command `php artisan vendor:publish --tag=timy-components --force`
1. Next, Register your components previous to creating the vue instance:  
````javascript
Vue.component('timy-timers-control', require('./components/Timy/ControlTimers.vue').default);  
Vue.component('timy-user-dashboard', require('./components/Timy/DashboardUser.vue').default);  
Vue.component('timy-admin-dashboard', require('./components/Timy/DashboardAdmin.vue').default);  
Vue.component('timy-super-admin-dashboard', require('./components/Timy/DashboardSuperAdmin.vue').default);  
````
1. Make sure the `laravel-echo` block in the `resources\js\bootstrap.js` is present and uncommented:
````javascript
import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});
````
1. Next install the following dependencies and compile for production:
    > - `npm install vue@^2.* cross-env@7 axios@0.* vuedraggable@2.* chart.js@^2.* vue-chartjs@^3.* js-cookie@^2.* moment@^2.* laravel-echo@1.* pusher-js@6.* --save-dev && npm run production`
> Optionally, you may want to add the following scripts in your main `composer.json` file:
> ````javascript
> "scripts": {
>     "post-autoload-dump": [
>         "@php artisan vendor:publish --tag=timy-components --force",
>         "npm run production"
>    ],
> }
> ````
## Features
- Authenticated users is required for the package to work. We leverage that on `laravel/ui` package. 
- Users and admin shoud have valid roles assigned to them. 
- When session is started, all previously opened timers are closed and a new one is opened.
- Only Super Admin can manage roles.
- Admin controller is protected and only work for users with `timy-admin` role assigned to them.
- On page load:
    -if the user has a timer running for a specifig disposition, upon closing that one, a new timer is started using that same disposition. 
    - Even if there are not timers running, previous disposition is used to start a new timer on page reload.
    - If both previous logic fails, by default the dispo set in the config is used to start a new timer.
- If a user change the Dispositions dropdown (Vue component) a new timer is created, closing all previous.
- When an user change their dispo, the admin dashboar update dinamically.
- When admin update a scpecific user's dispo, user interface update and the user is alerted. 
