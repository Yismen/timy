<?php

namespace Dainsys\Timy\Models\QueryFilters;

class Running extends QueryFiltersBase
{
    protected function apply($builder)
    {
        if (request($this->filter_name)) {
            $builder->running();
        }

        return $builder;
    }
}
