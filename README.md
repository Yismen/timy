# Dainsys Time Tracker Package
Add time tracker functionality to Laravel and Vue apps.

## Installation
- Add the following entry to your composer.json file:
    ```js
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/Yismen/timy.git"
        }
    ]
    ```
- Next run `composer require dainsys/timy` command to install as a dependency
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

## Usage: 
#### If you are using package routes and it's front end views: 
- Make sure config key `with_web_routes` is set to `TRUE`.
- Next run `php artisan vendor:publish --tag=timy-public` to publish the frontend assets.
- Super admin can visit route `/timy/dashbaords/super_admin` to manage Roles. This route is protected.
- Admin users (manage dispositions, user status, timers, reports, etc.) can visit `/timy/dashbaords/admin`.
- Users should be using route `/timy/dashbaords/admin` to track their hours.
### If you are using the vue components shipped with the package:
- Make sure config key `with_web_routes` is set to `TRUE`.
- Publish the components by running the command `php artisan vendor:publish --tag=timy-components`  
- Register your components previous to creating the vue instance:  
`Vue.component('timy-timers-control', require('./components/Timy/TimersControl.vue').default);`     
`Vue.component('timy-user-dashbord', require('./components/Timy/UserDashboard.vue').default);`  
- Next install the following dependencies:
    - `npm install vue@^2.* axios@0.* vuedraggable@2.* chart.js@^2.* vue-chartjs@^3.* js-cookie@^2.* moment@^2.* --save-dev && npm run production`.   
- Drop the `<TimyTimersControl />` in your nav-bar or any other blade partial that only shows under auth and is visible when user refresh the page.  
- Crete a route and return a view that includes the `<TimyUserDashboard />` component so the user can see its own stats.  
- Create a route and return a view where a user with role `timy-admin` can manage all aspects of the packages and include in it the `<TimyAdminDashboard />` component.  
- Create a route and return a view where a user with role `timy-admin` can manage all aspects of the packages and include in it the `<TimySuperAdminDashboard />` component. Define the email of the super admin in tye config file.
## Features
- Authenticated users is required for the package to work. 
- Users and admin shoud have valid roles assigned to them. 
- When session is started, all previously opened timers are closed and a new one is opened.
- Only Super Admin can manage roles.
- Admin controller is protected and only work for users with `timy-admin` role assigned to them.
- On page load:
    -if the user has a timer running for a specifig disposition, upon closing that one, a new timer is started using that same disposition. 
    - Even if there are not timers running, previous disposition is used to start a new timer on page reload.
    - If both previous logic fails, by default the dispo set in the config is used to start a new timer.
- If a user change the Dispositions dropdown (Vue component) a new timer is created, closing all previous.
- When migrations run, some Disposition records are created by default. you can change this option in the config file.

#TODO
[x] Routes should not be API  
[x] It should include DB Seeders with the initial dispositions.  
[x] Add roles module
    [x] Super admin should:
        [x] Assign roles
    [] Admin
        [] See admin dashboard
        [] Update timers dispositions
        [] Logout users out
    [x] User
        [x] See own dashboard
[] Add admin module  
[x] Improve documentation for installation  
[x] Improve documentation for ussage  
[] Isolate CSS classes  
[x] Public it's own css and javascript so the user is not forced to install all npm dependencies  
[x] A user may need to have timy_user role to use the package.  