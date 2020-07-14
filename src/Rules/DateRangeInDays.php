<?php

namespace Dainsys\Timy\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class DateRangeInDays implements Rule
{
    private $days;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $days)
    {
        $this->days = $days;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Carbon::parse(request('date_to'))->diffInDays(Carbon::parse(request('date_from'))) <= $this->days;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Only {$this->days} days range allowed!";
    }
}
