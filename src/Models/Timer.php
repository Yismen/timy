<?php

namespace Dainsys\Timy\Models;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Database\Factories\TimerFactory;
use Dainsys\Timy\Models\Traits\Filters\TimerFiltersTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timer extends Model
{
    use HasFactory;
    use TimerFiltersTrait;

    protected $table = 'timy_timers';

    protected $fillable = ['user_id', 'disposition_id', 'name', 'started_at', 'finished_at', 'ip_address'];

    protected $dates = ['started_at', 'finished_at'];

    protected $appends = ['path', 'total_hours', 'payable_hours', 'is_payable'];

    public function getPathAttribute()
    {
        return $this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function scopePayable($query)
    {
        return $query->whereHas('disposition', function ($query) {
            return $query->where('payable', 1);
        });
    }

    /**
     * Method to mark current timer as complete!
     */
    public function stop()
    {
        $this->update([
            'finished_at' => now()
        ]);
    }

    /**
     * Method to mark current timer as complete!
     */
    public function fakeStop()
    {
        $this->finished_at = now();

        return $this;
    }

    public function getTotalHoursAttribute()
    {
        return !$this->finished_at ? 0 : $this->started_at->floatDiffInHours($this->finished_at);
    }

    public function getPayableHoursAttribute()
    {
        return optional($this->disposition)->payable == true && $this->finished_at
            ? $this->started_at->floatDiffInHours($this->finished_at) : 0;
    }

    public function getNameAttribute()
    {
        return optional($this->user)->name;
    }

    public function getIsPayableAttribute()
    {
        return optional($this->disposition)->payable;
    }
    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TimerFactory::new();
    }
}
