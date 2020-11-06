<?php

namespace Dainsys\Timy\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $table = 'timy_teams';

    protected $fillable = ['name'];
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
    public function users()
    {
        return $this->hasMany(resolve('TimyUser'), 'timy_team_id');
    }
}