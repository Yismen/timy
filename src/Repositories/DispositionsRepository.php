<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Models\Disposition;

class DispositionsRepository
{
    public static function all()
    {
        return Disposition::orderBy('name')->get();
    }
}