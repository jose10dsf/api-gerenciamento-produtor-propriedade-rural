<?php

namespace App\Providers;

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
        $this->app->register(\Tymon\JWTAuth\Providers\LumenServiceProvider::class);
    }

    /**
     * Boot the app services for the application.
     *
     * @return void
     */
    public function boot()
    {
    	Validator::extend('cpf', '\App\Helpers\CpfValidationHelper@validate');
    	//Validator::extend('rural_cadastre', '\App\Helpers\RuralCadastreValidationHelper@validate');
    }
}
