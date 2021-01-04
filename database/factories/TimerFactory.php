<?php

namespace Dainsys\Timy\Database\Factories;

use App\User;
use Carbon\Carbon;
use Dainsys\Timy\Models\Disposition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Dainsys\Timy\Models\Timer;

class TimerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Timer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'user_id' => User::factory(),
            'disposition_id' => Disposition::factory(),
            'started_at' => now(),
            'finished_at' => null,
            'ip_address' => $this->faker->ipv4,
        ];
    }

    public function running()
    {
        return $this->state([
            'finished_at' => null
        ]);
    }

    public function closed(Carbon $when = null)
    {
        return $this->state([
            'finished_at' => $when ?: now()
        ]);
    }
}
