<?php

$filters = ['station' => 'string',]; //string parameters are searched for * (and replaced) and sent to the string (=), stringContains (%str% or *str*), stringStartsWith(str% or str*), stringEndsWith(%str or *str). Column name is equal to parameter name by default
'office' => 'string:the_office', //custom columns can be provided
    'some_string' => 'string|not',  // or 'string(not):customColumn uses ->whereNot()
    'user_name' => 'string:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string' => 'string|or:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string2' => 'string|not:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string3' => 'string|or|not:user_name,user_name2', //string methods can be called directly and % symbols should be added as necessary if not present
    'string1, string2' => 'string:string_col', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
    'string3, string4' => 'string|or:string_col',];

$params = ['station' => 'astation', //string parameters are searched for * (and replaced) and sent to the string (=), stringContains (%str% or *str*), stringStartsWith(str% or str*), stringEndsWith(%str or *str). Column name is equal to parameter name by default
    'office' => 'theoffice%', //custom columns can be provided
    'some_string' => '%boogers%',  // or 'string(not):customColumn uses ->whereNot()
    'user_name' => 'ryan', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string' => 'bob', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string2' => 'thomas', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string3' => 'car', //string methods can be called directly and % symbols should be added as necessary if not present
    'string1' => 'test2', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
    'string2' => 'test',
    'string3' => 'a',
    'string4' => 'string|and:string_col'
];

$r->merge(['station' => 'a station', //string parameters are searched for * (and replaced) and sent to the string (=), stringContains (%str% or *str*), stringStartsWith(str% or str*), stringEndsWith(%str or *str). Column name is equal to parameter name by default
    'office' => 'the office%', //custom columns can be provided
    'some_string' => '%boogers%',  // or 'string(not):customColumn uses ->whereNot()
    'user_name' => 'ryan', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string' => 'bob', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string2' => 'thomas', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string3' => 'car', //string methods can be called directly and % symbols should be added as necessary if not present
    'string1' => 'test2', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
    'string2' => 'test',
    'string3' => 'testing two values with and',
    'string4' => 'this be the and'
]);

if (count($fields) > 1 && count($values) > 1) {
    throw new \Exception('multiple values with multiple inputs are not supported.');
}

$negate = in_array('not', $modifiers);
$exact = strpos('%', implode(',',$values)) === false;
$isOr = in_array('or', $modifiers);

$operator = $exact ? ($negate ? '!=' : '=') : ($negate ? 'NOT LIKE' : 'LIKE');

if($isOr) {
    //OR operations
    if(count($values) > 1) {
        if($exact) {
            if($negate) {
                $query->whereNotIn($field, $values);
            } else {
                $query->whereIn($field, $values);
            }
        } else {
            $query->where(function($query) use ($values, $field, $operator) {
                foreach($values as $value) {
                    $query->orWhere($field, $operator, $value);
                }
            });
        }
    } elseif(count($fields) > 1) {
        $query->where(function($query) use ($value, $fields) {
            foreach($fields as $field) {
                $query->orWhere($field, $operator, $value);
            }
        });
    } else {
        $query->orWhere($field, $operator, $value);
    }
} else {
    //AND operations
    if(count($values) > 1) { // two values one field
        $query->where(function($query) use ($values, $field, $operator) {
            foreach($values as $value) {
                $query->where($field, $operator, $value);
            }
        });
    } elseif(count($fields) > 1) { // two fields one value
        foreach($fields as $field) {
            $query->where(function($query) use ($value, $field, $operator) {
                $query->where($field, $operator, $value);
            });
        }
    } else { // one of each
        $query->where($field, $operator, $value);
    }
}
}
$query->where('station','=', 'a station');

$query->where('the_office', 'LIKE', 'the office%');

$query->where('some_string', 'NOT LIKE', '%boogers%');

$query->orWhere('user_name', 'ryan')->orWhere('user_name2', 'ryan');

$query->where(function($query) {
    $query->where('user_name', 'ryan')->Where('user_name2', 'ryan');
});

$q->where('name', 'boogers');

$q->where(function($query){
    $pets = ['cat', 'dog', 'monkey'];
    $pet = array_shift($pets);
    $query->where($pet, 'LIKE', 'monkey');
    foreach($pets as $field) {
        $operator = "LIKE";
        $query->orWhere($field, $operator, 'monkey');
    }
})




//full request params
$r->merge(['station' => 'a station', //string parameters are searched for * (and replaced) and sent to the string (=), stringContains (%str% or *str*), stringStartsWith(str% or str*), stringEndsWith(%str or *str). Column name is equal to parameter name by default
    'office' => 'the office%', //custom columns can be provided
    'some_string' => '%boogers%',  // or 'string(not):customColumn uses ->whereNot()
    'user_name' => 'ryan', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string' => 'bob', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string2' => 'thomas', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string3' => 'car', //string methods can be called directly and % symbols should
    be added as necessary if not present
    'string1' => 'test2', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
    'string2' => 'test',
    'string3' => 'testing two values with and',
    'string4' => 'this be the and'
]);

//full filter params
$f = ['station' => 'string', //string parameters are searched for * (and replaced) and sent to the string (=), stringContains (%str% or *str*), stringStartsWith(str% or str*), stringEndsWith(%str or *str). Column name is equal to parameter name by default
    'office' => 'string:the_office', //custom columns can be provided
    'some_string' => 'string|not',  // or 'string(not):customColumn uses ->whereNot()
    'user_name' => 'string:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string' => 'string|or:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string2' => 'string|or|not:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string3' => 'string|or|not:user_name,user_name2', //string methods can be called directly and % symbols should be added as necessary if not present
    'string1, string2' => 'string:string_col', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
    'string3, string4' => 'string|or:string_col',];

$q->toSql();

$mf = app(App\Utils\ModelFilter\ModelFilter::class);

$r = new App\Utils\ModelFilter\FilterRequest();

$r->merge(['station' => 'a station', //string parameters are searched for * (and replaced) and sent to the string (=), stringContains (%str% or *str*), stringStartsWith(str% or str*), stringEndsWith(%str or *str). Column name is equal to parameter name by default
    'office' => 'the office%', //custom columns can be provided
    'some_wild_string' => '%boogers%',  // or 'string(not):customColumn uses ->whereNot()
    'some_exact_string' => 'boogers',
    'user_name' => 'ryan', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string' => 'bob', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string2' => 'thomas', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string3' => 'car', //string methods can be called directly and % symbols should be added as necessary if not present
    'string1' => 'value1', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
    'string2' => 'value2%',
    'string3' => 'value3',
    'string4' => 'value4'
]);

$f = ['station' => 'string', //string parameters are searched for * (and replaced) and sent to the string (=), stringContains (%str% or *str*), stringStartsWith(str% or str*), stringEndsWith(%str or *str). Column name is equal to parameter name by default
    /*'office' => 'string:the_office', //custom columns can be provided
    'some_exact_string' => 'string|not',  // or 'string(not):customColumn uses ->whereNot()
    'some_wild_string' => 'string|not',
    'user_name' => 'string:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->orWhere clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string' => 'string|or:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string2' => 'string|or|not:user_name,user_name2', //compares string on multiple columns when more than one column is passed using ->where clauses.  Wildcards should be calculated, replaced, or added as necessary.
    'another_string3' => 'string|or|not:user_name,user_name2', //string methods can be called directly and % symbols should be added as necessary if not present
    */
    'user_name' => 'string|or|not:user_name,user_name2',
    'some_wild_string'=> 'string|or|not:user_name,user_name2',
    'some_wild_string, some_exact_string' => 'string|or|not:string_col', //multiple string parameters are allowed and compared on the custom column using ->orWhere.  defaults column to first field name if no column is provided.
    /*'string3, string4' => 'string|or:string_col',*/];

$q = DeviceHistory::query();

$mf->build($f, $r);

$mf->apply($q);

$q->toSql();
$q->getBindings();
