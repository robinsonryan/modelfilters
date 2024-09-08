<?php

namespace App\Utils\ModelFilter\Strategies;

use Illuminate\Database\Eloquent\Builder;

class OneToOneStrategy extends BaseFilterStrategy
{
    private string $field;
    private string $value;

    public function __construct(string $field, string $value, bool $isOr, bool $negate)
    {
        parent::__construct($isOr, $negate);
        $this->field = $field;
        $this->value = $value;
    }

    public function apply(Builder $query): Builder
    {
        $operator = $this->getOperator($this->value);

        if ($this->isOr) {
            return $query->orWhere($this->field, $operator, $this->value);
        }
        return $query->where($this->field, $operator, $this->value);
    }
}
