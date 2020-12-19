<?php

namespace Dainsys\Timy\Models\QueryFilters;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

abstract class QueryFiltersBase
{
    protected $filter_name;

    public function handle($request, Closure $next)
    {
        $this->filter_name = Str::snake(class_basename($this));

        if (!request($this->filter_name)) {
            return $next($request);
        }

        $builder = $next($request);

        return $this->apply($builder);
    }

    abstract protected function apply(Builder $builder);
}
