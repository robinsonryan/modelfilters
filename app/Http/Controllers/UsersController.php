<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersFilterRequest;
use App\Models\User;
use App\Utils\ModelFilter\ModelFilter;
use Inertia\Inertia;
use Inertia\Response;

class UsersController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('UsersSearch');
    }

    public function search(UsersFilterRequest $request, ModelFilter $filter): Response
    {
        $query = User::filter($request, $filter);

        $totalUsers = $query->count();
        $users = $query->take(5)->get();

        return Inertia::render('UsersSearch', [
            'users' => $users,
            'totalUsers' => $totalUsers,
            'filters' => $this->parseFilters($request->filters()),
        ]);
    }

    protected function parseFilters($filters)
    {
        $parsedFilters = [];

        foreach ($filters as $inputNames => $filterString) {
            // Split input names by comma
            $inputNamesArray = explode(',', $inputNames);

            // Extract the method and modifiers from the filter string
            $filterParts = explode(':', $filterString);
            $methodAndModifiers = $filterParts[0]; // method|modifier part
            $modelProperties = isset($filterParts[1]) ? $filterParts[1] : implode(',', $inputNamesArray); // model properties part

            // For each input name, store the parsed information
            foreach ($inputNamesArray as $inputName) {
                $parsedFilters[$inputName] = [
                    'method_modifiers' => $methodAndModifiers,
                    'model_properties' => $modelProperties
                ];
            }
        }

        return $parsedFilters;
    }
}
