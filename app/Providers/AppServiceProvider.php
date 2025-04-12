<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        if (!Type::hasType('tinyinteger')) {
            Type::addType('tinyinteger', 'Doctrine\DBAL\Types\IntegerType');
        }
        $platform = \Illuminate\Support\Facades\DB::getDoctrineConnection()->getDatabasePlatform();
        $platform->markDoctrineTypeCommented(Type::getType('tinyinteger'));

        if (!Type::hasType('enum')) {
            Type::addType('enum', 'Doctrine\DBAL\Types\StringType');
        }
        // Mark the 'enum' type to be handled as a comment for MySQL
        $platform = \Illuminate\Support\Facades\DB::getDoctrineConnection()->getDatabasePlatform();
        $platform->markDoctrineTypeCommented(Type::getType('enum'));
        
        // Register the ENUM type mapping
        DB::connection()
            ->getDoctrineSchemaManager()
            ->getDatabasePlatform()
            ->registerDoctrineTypeMapping('enum', 'string');
    }
}
