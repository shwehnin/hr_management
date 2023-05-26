<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::unprepared('
        CREATE TRIGGER id_store BEFORE INSERT ON users FOR EACH ROW
        BEGIN
        INSERT INTO sequences VALUES(NULL);
        SET NEW.rec_id = CONCAT("HH_", LPAD(LAST_INSERT_ID(), 5, "0"));
        END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::unprepared('DROP TRIGGER "id_store"');
    }
};
