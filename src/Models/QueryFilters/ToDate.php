<?php

namespace Dainsys\Timy\Models\QueryFilters;

class ToDate extends QueryFiltersBase
{
    protected function apply($builder)
    {
        if ($to_date = request($this->filter_name)) {
            $builder->whereDate('started_at', '<=', $to_date);
        }

        return $builder;
    }
}
