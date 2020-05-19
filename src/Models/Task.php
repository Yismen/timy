<?php

namespace Dainsys\Timy\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'timy_tasks';

    protected $fillable = ['name', 'payable', 'invoiceable'];

    public function timers()
    {
        return $this->hasMany(Timer::class);
    }
}
