<?php

namespace Dainsys\Timy\Models\QueryFilters;

class Invoiceable extends QueryFiltersBase
{
    protected function apply($builder)
    {
        if (request($this->filter_name)) {
            $builder->whereHas('disposition', function ($query) {
                $query->where('invoiceable', true);
            });
        }

        return $builder;
    }
}
