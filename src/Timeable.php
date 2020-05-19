<?php

namespace Dainsys\Timy;

use Dainsys\Timy\Models\Timer;

trait Timeable
{
    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    public function startTimer($task_id)
    {
        $this->timers()->create([
            'name' => $this->name,
            'task_id' => $task_id,
            'started_at' => now(),
        ]);
    }
}
