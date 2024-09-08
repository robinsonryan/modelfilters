<?php

namespace App\Utils\ModelFilter\Providers;

use App\Utils\ModelFilter\Extractors\RequestValueExtractor;
use App\Utils\ModelFilter\Parsers\FilterConfigParser;
use App\Utils\ModelFilter\Validators\FilterConfigValidator;
use App\Utils\ModelFilter\Validators\ModifierValidator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\FormRequest;
use App\Utils\ModelFilter\ModelFilter;
use App\Utils\ModelFilter\FilterRequest;

class ModelFilterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(RequestValueExtractor::class, function ($app) {
            return new RequestValueExtractor();
        });

        $this->app->bind(FilterConfigValidator::class, function ($app) {
            return new FilterConfigValidator();
        });

        $this->app->bind(ModifierValidator::class, function ($app) {
            return new ModifierValidator();
        });

        $this->app->bind(FilterConfigParser::class, function ($app) {
            return new FilterConfigParser($app->make(ModifierValidator::class));
        });

        $this->app->bind(ModelFilter::class, function ($app) {
            return new ModelFilter(
                $app->make(RequestValueExtractor::class),
                $app->make(FilterConfigValidator::class),
                $app->make(FilterConfigParser::class)
            );
        });


    }

    public function boot()
    {
        //
    }
}
