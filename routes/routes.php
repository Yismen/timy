<?php

require(__DIR__ . '/api.php');

if (config('timy.with_web_routes')) {
    require(__DIR__ . '/web.php');
}
