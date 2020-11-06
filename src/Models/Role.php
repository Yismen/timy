<?php

namespace Dainsys\Timy\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'timy_roles';

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
        return $this->hasMany(User::class, 'timy_role_id');
    }
}
