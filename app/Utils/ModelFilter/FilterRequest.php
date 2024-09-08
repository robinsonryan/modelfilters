<?php

namespace App\Utils\ModelFilter;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    /**
     * Search Filters to be applied to $request parameters
     */
    public function filters(): array
    {
        // add your filter definitions
        return [];
    }

    protected function prepareForValidation(): void
    {
        $this->removeNullInputs();
    }

    //TODO: this should be a trait - save it as a resource outside of this package. I use it all the time.
    private function removeNullInputs(): void
    {
        $inputs = $this->all();
        array_walk($inputs, function ($value, $key) {
            if($value !== false && !$value)
            {
                $this->attributes->remove($key);
                //TODO: use request->remove()
                $this->offsetUnset($key);
            }
        });
    }
}
