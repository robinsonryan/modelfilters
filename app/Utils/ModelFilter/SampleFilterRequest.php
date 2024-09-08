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
        ];
    }

    public function filters(): array
    {
        return [
            'station' => 'string', //Column name is equal to parameter name by default
            'office' => 'string:the_office', //custom columns can be provided
            'some_exact_string' => 'string|not',  // or 'string(not):customColumn
            'some_wild_string1' => 'string|not',
            'user_name' => 'string:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
            'another_string' => 'string|or:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
            'another_string2' => 'string|or|not:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
            'another_string3' => 'string|or|not:user_name,user_name2', //string methods can be called directly and % symbols should be added as necessary if not present
            'user_name2' => 'string|or|not:user_name,user_name2',
            'some_wild_string2'=> 'string|or|not:user_name,user_name2',
            'some_wild_string, some_exact_string' => 'string|or|not:string_col', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
            'string3, string4' => 'string|or:string_col',
        ];
    }
}
