<?php

namespace Dainsys\Timy\Models\QueryFilters;

class Disposition extends QueryFiltersBase
{
    protected function apply($builder)
    {
        if ($disposition = request($this->filter_name)) {
            $builder->whereHas('disposition', function ($query) use ($disposition) {
                $query->where('name', 'like', "%{$disposition}%");
            });
        }

        return $builder;
    }
}
