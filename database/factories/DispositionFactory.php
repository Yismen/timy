<?php

namespace Dainsys\Timy\Database\Factories;

use Dainsys\Timy\Models\Disposition;
use Illuminate\Database\Eloquent\Factories\Factory;

class DispositionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Disposition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'payable' => rand(0, 1),
            'invoiceable' => rand(0, 1)
        ];
    }
    /**
     * Payable state
     *
     * @return void
     */
    public function payable()
    {
        return $this->state([
            'payable' => 1
        ]);
    }

    public function notPayable()
    {
        return $this->state([
            'payable' => 0
        ]);
    }

    public function invoiceable()
    {
        return $this->state([
            'invoiceable' => 1
        ]);
    }

    public function notInvoiceable()
    {
        return $this->state([
            'payable' => 0
        ]);
    }
}
