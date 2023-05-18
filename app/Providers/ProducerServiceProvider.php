<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Producer;
use App\Repositories\Producer\ProducerRepository;
use App\Services\Producer\ProducerService;
use Illuminate\Support\ServiceProvider;

class ProducerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ProducerService::class, function ($app) {
           return new ProducerService(new ProducerRepository(new Producer()));
        });
    }
}