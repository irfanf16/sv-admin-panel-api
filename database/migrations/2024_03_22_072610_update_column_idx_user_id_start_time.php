<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::table('timelogs', static function (Blueprint $table) {
            $schemaManager = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound  = $schemaManager->listTableIndexes('timelogs');
         
            if (! array_key_exists('idx_user_id_start_time', $indexesFound)) {
                \DB::select("CREATE INDEX idx_user_id_start_time ON timelogs ( `user_id` , `start_time` )");
            }
        });
        
        // Schema::table('timelogs', function (Blueprint $table) {
        //     $index_exists = collect(DB::select("SHOW INDEXES FROM timelogs"))->pluck('Key_name')->contains('persons_body_unique');
        //     if ($index_exists) {
        //         $table->dropUnique("persons_body_unique");
        //     }
        // })
        // \DB::select("CREATE INDEX idx_user_id_start_time ON timelogs ( `user_id` , `start_time` )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
 
        
    }
};
