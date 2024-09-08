<?php

namespace App\Utils\ModelFilter;

class SampleFilterRequest extends FilterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            //regular validation rules.  Filters are only applied to parameters found in the $request->validated() array
            //So, give the filters() below, if only station and office are found in $request->validated(), the are the only
            //two inputes that will be used in the search.
        ];
    }

    public function filters(): array
    {
        return [
            //'input_name,optional_additional_names,comma_separated' => 'method|modifier|modifier2|another_modifier:property_name,additional_property_names'
            'station' => 'string', //the model name will be assumed to be the same as the input of the model name is omitted.
            'office' => 'string:the_office', //map any input name to any model column
            'some_exact_string' => 'string|not',  // negate using the not modifier.  Results searching ->where('model_property', 'NOT LIKE', inputVale).
            'some_wild_string1%' => 'string|not', // wildcards are supported on the input name.
            'user_name' => 'string:user_name,user_name2', //compares string on multiple columns when more than one column is passed. Uses ->orWhere clauses.
            'another_string' => 'string|or:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.
            'another_string2' => 'string|or|not:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.
            'another_string3' => 'string|or|not:user_name,user_name2',
            'user_name2' => 'string|or|not:user_name,user_name2',
            '%some_wild_string2'=> 'string|or|not:user_name,user_name2',
            'some_wild_string, some_exact_string' => 'string|or|not:string_col', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
            'string3, string4' => 'string|or:string_col', //search for string3's value and/or string4's value in the models string_col property
        ];
    }
}
