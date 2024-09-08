<?php

namespace App\Utils\ModelFilter\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait AppliesFilters
{
    use StringFilters, IntegerFilters, BooleanFilters;

    //general model filters
    public function filterIncludeDeleted($query): Builder
    {
        return $this->query->withTrashed();
    }

    public function filterOnlyDeleted($query): Builder
    {
        return $this->query->onlyTrashed();
    }
}
