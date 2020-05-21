<?php

namespace Dainsys\Timy;

use Dainsys\Timy\Models\Timer;

trait Timeable
{
    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    public function startTimer($disposition_id)
    {
        $this->timers()->create([
            'name' => $this->name,
            'disposition_id' => $disposition_id,
            'started_at' => now(),
        ]);
    }
}
