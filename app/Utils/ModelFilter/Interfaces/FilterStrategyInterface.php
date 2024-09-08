<?php

namespace App\Utils\ModelFilter\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface FilterStrategyInterface
{
    public function apply(Builder $query): Builder;
}