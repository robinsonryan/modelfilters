
# Laravel ModelFilter Utility

The **ModelFilter** module is designed to automate and simplify the process of building search queries for Eloquent models in Laravel applications. The module is currently located in the `app/Utils/ModelFilter` directory. While this repository contains a full Laravel application with a demo, **the files in the `app/Utils/ModelFilter` directory are all that you need to integrate it into your own project**. Cloning the entire repo will give you a demonstration from at http://yoursite.local/users/search

## Installation

1. **Copy the Files:**
   - Copy the files from the `app/Utils/ModelFilter` directory into the same directory structure in your own Laravel app (e.g., `app/Utils/ModelFilter`).

2. **Add the `Filterable` Trait to Your Models:**
   - Any model that needs to be searchable must include the `Filterable` trait.
   - Example:
     ```php
     use App\Utils\ModelFilter\Traits\Filterable;

     class YourModel extends Model
     {
         use Filterable;
     }
     ```

3. **Create a `FilterRequest` Class:**
   - Create a new request class that extends `App/Utils/ModelFilter/FilterRequest`. The `FilterRequest` class extends Laravel's `FormRequest` class, but adds additional methods for use in filtering. It is worth noting that the class implements prepareForValidation logic that removes all null/empty fields from the request before validation occurs. 
   - Define your validation rules in this class, which will operate only on the fields in `$request->validated()`.

   Example:
   ```php
   use App\Utils\ModelFilter\FilterRequest;

   class YourModelFilterRequest extends FilterRequest
   {
       public function rules(): array
       {
           return [
               'name' => 'string|nullable',
               'email' => 'string|nullable',
               'country' => 'string|nullable',
           ];
       }
   }
   ```

4. **Define the Filters:**
    - In your `FilterRequest` class, define the filters inside the `filters()` method.
    - The filters map input names to the filtering logic.
    - Example:
      ```php
      public function filters(): array
      {
          return [
              'name' => 'string|or:name,nickname',  // Apply an OR filter looking for the value of the 'name' input on the 'name' or 'nickname' properties of the model
              'email' => 'string:email',           // Custom column 'email'
              'country' => 'string|not',           // Exclude certain countries
          ];
      }
      ```

5. **Apply the Filters in the Controller:**
    - Inject the `FilterRequest` and `ModelFilter` classes into your controller and apply the filters to your model.
    - Example:
      ```php
      use App\Utils\ModelFilter\ModelFilter;
 
      class YourController extends Controller
      {
          public function search(YourModelFilterRequest $request, ModelFilter $filter)
          {
              $models = YourModel::filter($request, $filter)->get();
              return response()->json($models);
          }
      }
      ```

## How It Works

There is a demo search form available at https://domain.com/user/search if you clone the entire repo.  It demonstrates the underlying automated process of building search queries for models in Laravel applications. It allows you to easily define filters within your `FilterRequest` (which extends Laravel's `FormRequest` class) and automatically apply those filters to Eloquent models in the controller.

### Key Steps:

- **Create a `FilterRequest`:** In your app, create a new class that extends the `FilterRequest`. This class will define your validation rules, messages, and, most importantly, the `filters()` method.

- **Define the Filters:** In the `filters()` method, map the input fields (e.g., 'name', 'email', 'nickname') to the filtering logic.

  Example filter:
   ```php
   public function filters(): array
   {
       return [
           'name' => 'string|or:name,nickname',  // Apply an OR filter on 'name' and 'nickname'
           'email' => 'string:email',           // Custom column 'email'
           'country' => 'string|not',           // Exclude certain countries
       ];
   }
   ```

  Each filter string follows the format:
   ```
   'input_name(s)' => 'method|modifier(s):property_name(s)'
   ```
  The method and modifiers define how the query is built.

- **Apply Filters in the Controller:** In the controller, inject the `FilterRequest` and `ModelFilter` into the method and apply the filter logic.
   ```php
   public function search(UsersFilterRequest $request, ModelFilter $filter): Response
   {
       $query = User::filter($request, $filter)->get();
   }
   ```

This system automatically applies the filters defined in the `FilterRequest` class to the model's query, handling the search logic behind the scenes. Only string methods are available now.  They accept input values with or without wildcards (%).

## Future Plans

While the current implementation focuses primarily on string-based filtering with simple modifiers like `and`, `or`, and `not`, there are several planned extensions to increase flexibility and functionality:

- **Support for numeric filters:** Add methods to handle integers and numeric ranges (e.g., greater than, less than, between).
- **Date filters:** Support for date-based queries, such as filtering by dates before, after, or between specified ranges.
- **Custom filter methods:** Allow developers to define custom filtering methods to handle complex queries and edge cases.
- **Related model filtering:** Enable filtering based on properties in related models (e.g., filtering users based on properties in their associated posts).
- **Additional modifiers:** Introduce new modifiers such as `not_null` for checking non-empty fields, and `exists` to filter records based on the existence of related data.
- **Nested Configurations:** Nest filter configurations such that the final query operations are applied as a group.

## Notes

- **Validation:** The `FilterRequest` only operates on validated fields, ensuring the security and accuracy of your search queries.
- **Modularity:** Effort has been made to design in a modular and extendable fashion, allowing developers to customize it according to their application's needs.
- **Null Inputs** Any input with a null or empty value is removed from the `$request` before validation. False and most other Falsey values are left alone. This may become configurable in the future.

---

I hope you find it useful.
```
