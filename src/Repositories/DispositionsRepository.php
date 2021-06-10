<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Models\Disposition;
use Illuminate\Support\Facades\Cache;

class DispositionsRepository
{
    public static function all()
    {
        return Cache::rememberForever('timy.dispositions', function () {
            return Disposition::orderBy('name')->get();
        });;
    }
}
