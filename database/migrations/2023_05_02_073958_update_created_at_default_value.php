<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCreatedAtDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasColumn('user_activities', 'created_at')){
           \DB::select('ALTER TABLE user_activities MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;');
        }
        if(Schema::hasColumn('user_activities', 'updated_at')){
            \DB::select('ALTER TABLE user_activities MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('user_activities', 'created_at')){
            \DB::select('ALTER TABLE user_activities MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;');
        }
        if(Schema::hasColumn('user_activities', 'updated_at')){
            \DB::select('ALTER TABLE user_activities MODIFY COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;');
        }
    }
}
