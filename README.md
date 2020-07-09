# Dainsys Time Tracker Package
Add user's time tracker functionality to Laravel 7, VueJs 2 and Bootstrap 4.

## Installation
- Install with composer: `composer require dainsys/timy`.
- Optional: The Package should be auto-discovered by Laravel. However, you could all register it in your config.app file within the providers array:
    ```php
        'providers' => [
            Dainsys\Timy\TimyServiceProvider::class,
        ]
    ```
- Next you may want to publish the config file: `php artisan vendor:publish --tag=timy-config` to change default configuration. Pay attention to the option of creating default dispositions. 
- Next provide the super user email in the `config/timy.php` file for key `super_admin => email`. It is a better practice to set `TIMY_SUPER_USER_EMAIL` variable in you .env with the super user email for security. 
- Next you may want to run migrations with command `php artisan migrate`. 
- Add the `use Dainsys\Timy\Timeable` trait to your `User` model. 
- Next, make sure to follow the `laravel/ui` installation guide from https://laravel.com/docs/7.x/authentication
The package has it's own views and Vue components and it should work out of the box.
* Add links to the following end points: 
- Users: URL=`/timy/user`, NAME=`user_dashboard`, GATEWAY(blade @can directive)=`timy-user`
- Admin Users: URL=`/timy/admin`, NAME=`admin_dashboard`, GATEWAY(blade @can directive)=`timy-admin`
- Super Admin User: URL=`/timy/super_admin`, NAME=`supepr_admin_dashboard`, GATEWAY(blade @can directive)=`timy-super-admin`
- Drop the `<TimyTimersControl />` in your nav-bar or any other blade partial that only shows under auth and is visible when user refresh the page.  
- Crete a route and return a view that includes the `<TimyUserDashboard />` component so the user can see its own stats.  
- Create a route and return a view where a user with role `timy-admin` can manage all aspects of the packages and include in it the `<TimyAdminDashboard />` component.  
- Create a route and return a view where a user with role `timy-admin` can manage all aspects of the packages and include in it the `<TimySuperAdminDashboard />` component. Define the email of the super admin in tye config file.
- Next define the following variables in your .env file:
````javascript
TIMY_SUPER_USER_EMAIL=

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}
````
#### If you are using package routes and it's front-end views: 
- Next run `php artisan vendor:publish --tag=timy-public` to publish the frontend assets.
- Super admin can visit route `/timy/super_admin` to manage Roles. This route is protected.
- Admin users (manage dispositions, user status, timers, reports, etc.) can visit `/timy/admin`.
- Users should be using route `/timy/admin` to track their hours.
### If you are using the vue components shipped with the package:
- Publish the components by running the command `php artisan vendor:publish --tag=timy-components`  
- Register your components previous to creating the vue instance:  
`Vue.component('timy-timers-control', require('./components/Timy/ControlTimers.vue').default);`  
`Vue.component('timy-user-dashbord', require('./components/Timy/DashboardUser.vue').default);`  
`Vue.component('timy-admin-dashbord', require('./components/Timy/DashboardAdmin.vue').default);`  
`Vue.component('timy-super-admin-dashbord', require('./components/Timy/DashboardSuperAdmin.vue').default);`  
- Next install the following dependencies:
    - `npm install vue@^2.* cross-env@7.0 axios@0.* vuedraggable@2.* chart.js@^2.* vue-chartjs@^3.* js-cookie@^2.* moment@^2.* laravel-echo@1.* pusher-js@6.* --save-dev && npm run production`.   
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
#TODO 
[] Add roles module
    [] Admin
        [] Run reports