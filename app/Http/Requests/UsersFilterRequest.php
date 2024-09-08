<?php

namespace App\Http\Requests;

use App\Utils\ModelFilter\FilterRequest;

class UsersFilterRequest extends FilterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => "sometimes|string|nullable",
            'nickname' => "sometimes|string|nullable",
            'country' => "sometimes|string|nullable",
            'pet' => "sometimes|string|nullable",
            'car_model' => "sometimes|string|nullable",
            'favourite_food' => "sometimes|string|nullable",
            'email' => "sometimes|string|nullable",
        ];
    }

    public function filters(): array
    {
        return [
            'name' => 'string|or:name,nickname', //input name is equal to model property name by default
            'email' => 'string:email', //custom columns can be provided
            'country' => 'string|not', // this might be used of the country field had a label 'countries to exclude'
            'pet' => 'string',
            'car_model' => 'string',
            'favourite_food' => 'string:favorite_food', //different spelling on from input vs model column
            //'least_favorite_animal' => 'string|not:pet',
        ];
    }
}
