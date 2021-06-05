<?php

namespace Dainsys\Timy\Models;

use App\User;
use Dainsys\Timy\Database\Factories\TeamFactory;
use Dainsys\Timy\Models\Traits\ModelBootTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    use ModelBootTrait;

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
     * A Disposition can have many Timers
     *
     * @return Relationship
     */
    public function users()
    {
        return $this->hasMany(User::class, 'timy_team_id');
    }
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TeamFactory::new();
    }
}
