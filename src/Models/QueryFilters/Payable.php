<?php

namespace Dainsys\Timy\Models\QueryFilters;

class Payable extends QueryFiltersBase
{
    protected function apply($builder)
    {
        if (request($this->filter_name)) {
            $builder->whereHas('disposition', function ($query) {
                $query->where('payable', true);
            });
        }

        return $builder;
    }
}
