<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
// class CreateDatabasePartition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // DB::statement("ALTER TABLE timelogs drop primary key, add primary key(id, start_time)");
        // DB::statement("ALTER TABLE web_app_tracking drop primary key, add primary key(id, start_time)");
        // DB::statement("CREATE INDEX idx_startend_time_wat on web_app_tracking(start_time,end_time, user_id)");

        // DB Partition Script:

/*
        DB::statement("ALTER TABLE timelogs
            PARTITION BY RANGE COLUMNS(start_time)
            (
            PARTITION q0_preQ42023 VALUES LESS THAN ('2023-10-01 00:00:00') ENGINE = InnoDB,
            PARTITION q4_2023 VALUES LESS THAN ('2024-01-01 00:00:00') ENGINE = InnoDB,
            PARTITION q1_2024 VALUES LESS THAN ('2024-04-01 00:00:00') ENGINE = InnoDB,
            PARTITION q2_2024 VALUES LESS THAN ('2024-07-01 00:00:00') ENGINE = InnoDB,
            PARTITION q3_2024 VALUES LESS THAN ('2024-10-01 00:00:00') ENGINE = InnoDB,
            PARTITION q4_2024 VALUES LESS THAN ('2025-01-01 00:00:00') ENGINE = InnoDB,
            PARTITION q1_2025 VALUES LESS THAN ('2025-04-01 00:00:00') ENGINE = InnoDB,
            PARTITION q2_2025 VALUES LESS THAN ('2025-07-01 00:00:00') ENGINE = InnoDB,
            PARTITION q3_2025 VALUES LESS THAN ('2025-10-01 00:00:00') ENGINE = InnoDB,
            PARTITION q4_2025 VALUES LESS THAN ('2026-01-01 00:00:00') ENGINE = InnoDB
            )
        ");


        DB::statement("ALTER TABLE web_app_tracking
            PARTITION BY RANGE COLUMNS(start_time)
            (
            PARTITION q0_preQ42023 VALUES LESS THAN ('2023-10-01 00:00:00') ENGINE = InnoDB,
            PARTITION q4_2023 VALUES LESS THAN ('2024-01-01 00:00:00') ENGINE = InnoDB,
            PARTITION q1_2024 VALUES LESS THAN ('2024-04-01 00:00:00') ENGINE = InnoDB,
            PARTITION q2_2024 VALUES LESS THAN ('2024-07-01 00:00:00') ENGINE = InnoDB,
            PARTITION q3_2024 VALUES LESS THAN ('2024-10-01 00:00:00') ENGINE = InnoDB,
            PARTITION q4_2024 VALUES LESS THAN ('2025-01-01 00:00:00') ENGINE = InnoDB,
            PARTITION q1_2025 VALUES LESS THAN ('2025-04-01 00:00:00') ENGINE = InnoDB,
            PARTITION q2_2025 VALUES LESS THAN ('2025-07-01 00:00:00') ENGINE = InnoDB,
            PARTITION q3_2025 VALUES LESS THAN ('2025-10-01 00:00:00') ENGINE = InnoDB,
            PARTITION q4_2025 VALUES LESS THAN ('2026-01-01 00:00:00') ENGINE = InnoDB
            )
        ");

*/

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('timelogs');
    }
};
