<?php

namespace Dainsys\Timy\Models\QueryFilters;

class FromDate extends QueryFiltersBase
{
    protected function apply($builder)
    {
        if ($from_date = request($this->filter_name)) {
            $builder->whereDate('started_at', '>=', $from_date);
        }

        return $builder;
    }
}
