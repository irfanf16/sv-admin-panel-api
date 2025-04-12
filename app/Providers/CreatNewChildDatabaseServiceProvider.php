<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CreateNewDatabaseInterface;
use App\Services\createNewDatabaseService;

class CreatNewChildDatabaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CreateNewDatabaseInterface::class, createNewDatabaseService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

}
