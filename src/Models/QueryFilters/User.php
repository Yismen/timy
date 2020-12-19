<?php

namespace Dainsys\Timy\Models\QueryFilters;

class User extends QueryFiltersBase
{
    protected function apply($builder)
    {
        if ($user = request($this->filter_name)) {
            $builder->whereHas('user', function ($query) use ($user) {
                $query->where('name', 'like', "%{$user}%");
            });
        }

        return $builder;
    }
}
