<?php

namespace Dainsys\Timy\Models;

use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    protected $table = 'timy_timers';

    protected $fillable = ['user_id', 'disposition_id', 'name', 'started_at', 'finished_at'];

    protected $dates = ['started_at', 'finished_at'];

    protected $appends = ['path'];

    protected static function booted()
    {
        static::addGlobalScope(new MineScope);

        static::creating(function ($model) {
            $model->mine()->running()->each(function ($timer) {
                $timer->stop();
            });

            $model->name = auth()->user()->name;
        });
    }

    public function getPathAttribute()
    {
        return $this->id;
    }

    public function user()
    {
        return $this->belongsTo(config('timy.models.user'));
    }

    /**
     * Get the related disposition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function disposition()
    {
        return $this->belongsTo(Disposition::class);
    }

    /**
     * Get timer for current user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMine($query)
    {
        return $query->whereUserId(auth()->user()->id);
    }

    /**
     * Get the running timers
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRunning($query)
    {
        return $query->whereNull('finished_at');
    }
    /**
     * Method to mark current timer as complete!
     */
    public function stop()
    {
        $this->update([
            'finished_at' => now()
        ]);

        return $this;
    }
}
