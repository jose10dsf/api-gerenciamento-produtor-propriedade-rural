<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Property;
use App\Repositories\Property\PropertyRepository;
use App\Services\Property\PropertyService;
use Illuminate\Support\ServiceProvider;

class PropertyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PropertyService::class, function ($app) {
           return new PropertyService(new PropertyRepository(new Property()));
        });
    }
}