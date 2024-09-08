<?php

namespace App\Utils\ModelFilter;

use App\Utils\ModelFilter\Exceptions\InvalidFilterException;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Local Scope for filtering models using a FilterRequest.
     * @throws InvalidFilterException
     */
    public function scopeFilter(Builder $query, FilterRequest $request, ModelFilter $filter)
    {
        return $filter->apply($query, $request);
    }
}
