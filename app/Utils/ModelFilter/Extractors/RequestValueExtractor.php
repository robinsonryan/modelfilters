<?php

namespace App\Utils\ModelFilter\Extractors;

use App\Utils\ModelFilter\FilterRequest;

class RequestValueExtractor
{
    /**
     * Extract the values from the request inputs found in the parsed config.
     *
     * @param Request $request
     * @return array
     */
    public function extractValues(string $paramSting, FilterRequest $request): array
    {
        $params = explode(',', trim($paramSting));
        $validated = $request->validated();

        return array_values(array_intersect_key($validated, array_flip($params)));
    }
}
