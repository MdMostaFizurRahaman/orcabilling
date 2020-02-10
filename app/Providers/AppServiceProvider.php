<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('greater_than', function($attribute, $value, $parameters, $validator) {
            $compare_field = $parameters[0];
            $data = $validator->getData();
            $compare_value = $data[$compare_field];
            return $value > $compare_value;
        });

        Validator::replacer('greater_than', function($message, $attribute, $rule, $parameters) {
            $compare_field = $parameters[0];
            return str_replace(':field', Str::title(str_replace('_', ' ', $attribute)), ':field must be later from "' . Str::title(str_replace("_", " ", $compare_field)) . '.');
        });
    }
}
