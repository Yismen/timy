<?php

namespace Dainsys\Timy\Repositories;

use Dainsys\Timy\Disposition;

class DispositionsRepository
{
    public static function all()
    {
        return Disposition::orderBy('name')->get();
    }
}