# Dainsys Time Tracker Package
Add time tracker functionality

## Installation
- Step 1: Add the following entry to your composer.json file:
    ```js
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/Yismen/timy.git"
        }
    ]
    ```
- Step 2: Run `composer require dainsys/timy` command to install as a dependency
- Step 3: The Package should be auto-discovered by Laravel. However, you could all register it in your config.app file within the providers array:
    ```php
        'providers' => [
            Dainsys\Timy\TimyServiceProvider::class,
        ]
    ```

## Publish
Run `php artisan vendor:publish` and search for timy to publish the assets.

## Working with Assets
If you are publishing the components to work with Vuejs, install the following dependencies with `npm install vue@^2.* chart.js@^2.* vue-chartjs@^3.* js-cookie@^2.* moment@^2.* --save-dev && npm run production`.

Then register your components previous to creating the vue instance:

`Vue.component('timy-dropdown', require('./components/Timy/DispositionDropdownList.vue').default);`
`Vue.component('timy-user-dashbord', require('./components/Timy/UserDashboard.vue').default);`