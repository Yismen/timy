<?php

namespace Dainsys\Timy\Models;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $table = 'timy_dispositions';

    protected $fillable = ['name', 'payable', 'invoiceable'];

    public function timers()
    {
        return $this->hasMany(Timer::class);
    }
}
