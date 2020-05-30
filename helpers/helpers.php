<?php

if (!function_exists('timy_assets')) {
    function timy_assets($path, $secure = null)
    {
        return asset('vendor/dainsys/timy/' . $path, $secure);
    }
}
