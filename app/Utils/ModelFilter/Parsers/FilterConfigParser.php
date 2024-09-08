<?php

namespace App\Utils\ModelFilter\Parsers;

use App\Utils\ModelFilter\Validators\ModifierValidator;

class FilterConfigParser
{
    protected ModifierValidator $modifierValidator;

    public function __construct(ModifierValidator $modifierValidator)
    {
        $this->modifierValidator = $modifierValidator;
    }

    public function parse($input, $filterConfig): array
    {
        // Expected format for config: 'myMethod|modifier1|modifier2:col1,col2'
        // Adding modifiers and columns is optional
        // TODO: accept and array config of the format [method, modifiers:cols] where
        // method can be a string representing a built in filter method 'string' ['string', 'modifier1|modifier2:col1,col2]
        // or a class instance for a custom method [new MyFilter, 'modifier1|modifier2:col1,col2'] that extends FilterHandler

        //separate the method|mods from the columns
        $parts = explode(':', $filterConfig);

        // Split the method and its modifiers
        $methodConfig = explode('|', array_shift($parts));
        $methodName = array_shift($methodConfig);

        //name the handlerMethod - format filterMyMethod
        $method = 'filter' . ucfirst($methodName);

        $modifiers = [];

        // Populate modifiers array with valid modifiers
        foreach ($methodConfig as $modifier) {
            $this->modifierValidator->validate($modifier);
            $modifiers[] = $modifier;
        }

        // If no columns are specified in the config, the column name is equal to the input name
        $fieldsString = array_shift($parts) ?? $input;

        //get an array of column names
        $fields = array_map('trim', explode(',', $fieldsString));

        return [$method, $fields, $modifiers];
    }
}
