<?php

namespace Modules\Files\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
// use TypiCMS\Modules\Contacts\Composers\SidebarViewComposer;
use Modules\Files\Models\File;
// use TypiCMS\Modules\Core\Facades\TypiCMS;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/file.php', 'typicms.modules.file');

        // $this->loadViewsFrom(__DIR__.'/../../resources/views/', 'contacts');

        // $this->publishes([__DIR__.'/../../database/migrations/create_contacts_table.php.stub' => getMigrationFileName('create_contacts_table')], 'typicms-migrations');
        // $this->publishes([__DIR__.'/../../resources/views' => resource_path('views/vendor/contacts')], 'typicms-views');

        // View::composer('core::admin._sidebar', SidebarViewComposer::class);

        /*
         * Add the page in the view.
         */
        // View::composer('contacts::public.*', function ($view) {
        //     $view->page = TypiCMS::getPageLinkedToModule('contacts');
        // });
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind('File', File::class);
    }
}
