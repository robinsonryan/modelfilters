<?php

namespace App\Utils\ModelFilter\Concerns;

use App\Utils\ModelFilter\Interfaces\FilterStrategyInterface;
use App\Utils\ModelFilter\Strategies\ManyFieldStrategy;
use App\Utils\ModelFilter\Strategies\ManyValueStrategy;
use App\Utils\ModelFilter\Strategies\OneToOneStrategy;
use Illuminate\Database\Eloquent\Builder;

trait StringFilters
{
    /**
     * Apply string filters based on passed fields, values, and modifiers.
     *
     * @param array $fields
     * @param array $values
     * @param array $modifiers
     * @return Builder
     * @throws \Exception
     */
    public function filterString(array $fields, array $values, array $modifiers): Builder
    {
        if (count($fields) > 1 && count($values) > 1) {
            throw new \Exception('Multiple values with multiple inputs are not supported.');
        }

        $filterStrategy = $this->makeFilterStrategy($fields, $values, $modifiers);

        return $filterStrategy->apply($this->query);
    }

    /**
     * Choose the appropriate filter strategy based on filter properties
     *
     * @param array $fields
     * @param array $values
     * @param array $modifiers
     * @return FilterStrategyInterface
     */
    private function makeFilterStrategy(array $fields, array $values, array $modifiers): FilterStrategyInterface
    {


        $isOr = in_array('or', $modifiers);
        $negate = in_array('not', $modifiers);

        if (count($values) > 1) {
            return new ManyValueStrategy($fields[0], $values, $isOr, $negate);
        } elseif (count($fields) > 1) {
            return new ManyFieldStrategy($fields, $values[0], $isOr, $negate);
        } else {
            return new OneToOneStrategy($fields[0], $values[0], $isOr, $negate);
        }
    }
}
