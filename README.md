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
- Next you may want to run migrations with command `php artisan migrate`. 
- Add the `use Dainsys\Timy\Timeable` trait to your `User` model. 

## Usage: PHP

## Ussage: Vue
If you are using the vue components shipped with the package: 
1. First publish them using the `php artisan vendor:publish --tag=timy-components`   
2. Install the following dependencies by running `npm install vue@^2.* chart.js@^2.* vue-chartjs@^3.* js-cookie@^2.* moment@^2.* --save-dev && npm run production`.  
3. Register your components previous to creating the vue instance:  
`Vue.component('timy-dropdown', require('./components/Timy/DispositionDropdownList.vue').default);`     
`Vue.component('timy-user-dashbord', require('./components/Timy/UserDashboard.vue').default);`  
4. Drop the `<TimyDropdown />` in your nav-bar or any other blade partial that only shows under auth and is visible when user refresh the page.  
5. Crete a route and return a view that includes the `<TimyUserDashboard />` component so the user can see its own stats.  
6. Create a route and return a view where a user with role `timy-admin` can manage all aspects of the packages and include in it the `<TimyAdminDashboard />` component.  

## Features
- Authenticated users is required for the package to work. 
- Users and admin shoud have valid roles assigned to them. 
- When session is started, all previously opened timers are closed and a new one is opened.
- On page load:
    -if the user has a timer running for a specifig disposition, upon closing that one, a new timer is started using that same disposition. 
    - Even if there are not timers running, previous disposition is used to start a new timer on page reload.
    - If both previous logic fails, by default the dispo set in the config is used to start a new timer.
- If a user change the Dispositions dropdown (Vue component) a new timer is created, closing all previous.
- When migrations run, some Disposition records are created by default. you can change this option in the config file.

#TODO
[x] Routes should not be API  
[x] It should include DB Seeders with the initial dispositions.  
[] Add roles table for admin  
[] Add admin module  
[] Improve documentation for installation  
[x] Improve documentation for ussage  
[] Isolate CSS classes  
[] Public it's own css and javascript so the user is not forced to install all npm dependencies  
[] A user may need to have timy_user role to use the package.  