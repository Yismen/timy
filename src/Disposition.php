<?php

namespace Dainsys\Timy;

use Illuminate\Database\Eloquent\Model;

class Disposition extends Model
{
    protected $table = 'timy_dispositions';

    protected $fillable = ['name', 'payable', 'invoiceable'];
    /**
     * Name Accesor
     *
     * @param String $name
     * @return void
     */
    public function getNameAttribute($name)
    {
        return ucwords(trim($name));
    }
    /**
     * Name Mutator
     *
     * @param String $name
     * @return void
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = strtolower(trim($name));
    }
    /**
     * A Disposition can have many Timers
     *
     * @return Relationship
     */
    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    public function getOpenTimersAttribute()
    {
        return $this->timers()->running();
    }
}
