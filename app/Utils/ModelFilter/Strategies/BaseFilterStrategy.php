<?php

namespace App\Utils\ModelFilter\Strategies;

use App\Utils\ModelFilter\Interfaces\FilterStrategyInterface;

abstract class BaseFilterStrategy implements FilterStrategyInterface
{
    protected bool $isOr;
    protected bool $negate;

    public function __construct(bool $isOr, bool $negate)
    {
        $this->isOr = $isOr;
        $this->negate = $negate;
    }

    protected function getOperator(string $value): string
    {
        $containsWildcard = strpos($value, '%') !== false;
        if ($this->negate) {
            return $containsWildcard ? 'NOT LIKE' : '!=';
        }
        return $containsWildcard ? 'LIKE' : '=';
    }
}