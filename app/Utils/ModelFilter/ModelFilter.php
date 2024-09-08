<?php

namespace App\Utils\ModelFilter;

use App\Utils\ModelFilter\Concerns\AppliesFilters;
use App\Utils\ModelFilter\Exceptions\InvalidFilterException;
use App\Utils\ModelFilter\Extractors\RequestValueExtractor;
use App\Utils\ModelFilter\Parsers\FilterConfigParser;
use App\Utils\ModelFilter\Validators\FilterConfigValidator;
use Illuminate\Database\Eloquent\Builder;

class ModelFilter
{
    use AppliesFilters;

    protected Builder $query;
    //protected array $filters = [];
    protected RequestValueExtractor $extractor;
    protected FilterConfigValidator $validator;
    protected FilterConfigParser $parser;

    public function __construct(
        RequestValueExtractor $extractor,
        FilterConfigValidator $validator,
        FilterConfigParser $parser
    ) {
        $this->extractor = $extractor;
        $this->validator = $validator;
        $this->parser = $parser;
    }

    /**
     * @throws InvalidFilterException
     */
    public function build(FilterRequest $request): array
    {
        $filtersConfig = $request->filters();
        $this->validator->validate($filtersConfig);

        $filters = [];

        foreach($filtersConfig as $input => $config) {
            $values = $this->extractor->extractValues($input, $request);

            [$method, $fields, $modifiers] = $this->parser->parse($input, $config);

            if(!empty($values)) {
                $filters[$method][] = [$fields, $values, $modifiers];
            }
        }

        return $filters;
    }

    /**
     * @throws InvalidFilterException
     */
    public function apply(Builder $query, FilterRequest $request): Builder
    {
        $filters = $this->build($request);

        $this->query = $query;
        foreach ($filters as $handler => $configs) {
            if (!method_exists($this, $handler)) {
                //TODO: create InvalidFilterMethodException
                throw new \Exception("Method $handler does not exist in " . __CLASS__);
            }

            //special handlers
            /*if($handler === 'filterIncludeDeleted' && $values[0] === true) {
                //$this->filterIncludeDeleted($query);
            }

            if($handler === 'filterOnlyDeleted' && $values[0] === true) {
                //$this->filterOnlyDeleted($query);
            }*/

            foreach($configs as $config) {
                $this->$handler(...$config);
            }

        }

        return $this->query;
    }
}
