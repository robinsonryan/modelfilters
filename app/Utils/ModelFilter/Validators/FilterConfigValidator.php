<?php

namespace App\Utils\ModelFilter\Validators;

use App\Utils\ModelFilter\Exceptions\InvalidFilterException;

class FilterConfigValidator
{
    /**
     * Validate the given filter configuration.
     *
     * @param array $config The filter configuration array.
     *
     * @return bool
     * @throws InvalidFilterException
     */
    public function validate(array $configs): bool
    {
        // Expected format for config: 'param1,param2' => 'myMethod|modifier1|modifier2:col1,col2'
        // Including multiple params is optional. Adding modifiers and columns is optional.
        foreach ($configs as $paramString => $config) {
            $this->validateFilterConfig($config);
            $this->validateParamString($paramString);
        }

        return true;
    }

    protected function validateFilterConfig(string $filterConfig): bool
    {
        // matches myMethod|mod1|mod2|modN:col1,col2,colN
        $pattern = '/^[\w]+(\|[\w]+)*(:[\w,]+)?$/';

        if (!preg_match($pattern, $filterConfig)) {
            throw new InvalidFilterException("Invalid filter configuration: $filterConfig");
        }

        return true;
    }

    protected function validateParamString(string $paramString): bool
    {
        // Matches alphanumeric characters, underscores, and possibly commas - repeatedly.
        // e.g 'some_request_param', or 'param_1, param_2'
        $pattern = '/^[\w]+(,\s*[\w]+)*$/';

        if (!preg_match($pattern, $paramString)) {
            throw new InvalidFilterException("Invalid parameter string: $paramString");
        }

        return true;
    }
}
