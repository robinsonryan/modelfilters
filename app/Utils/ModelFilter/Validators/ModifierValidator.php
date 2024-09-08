<?php

namespace App\Utils\ModelFilter\Validators;

class ModifierValidator
{
    protected array $modifiers = ['and','or','not','exclusive'];

    public function validate($modifier): bool
    {
        if (!in_array($modifier, $this->modifiers)) {
            throw new \InvalidArgumentException("Invalid modifier: $modifier");
        }

        return true;
    }
}
