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
            App\Providers\TimyServiceProvider::class,
        ]
    ```

## Publish
Run `php artisan vendor:publish` and search for timy to publish the assets.
