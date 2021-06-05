<?php

namespace Dainsys\Timy\Models;

use App\User;
use Dainsys\Timy\Database\Factories\RoleFactory;
use Dainsys\Timy\Models\Traits\ModelBootTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use ModelBootTrait;

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
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return RoleFactory::new();
    }
}
