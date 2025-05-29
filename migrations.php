<?php

use Illuminate\Database\Migrations\Migration;

class ImportDump extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("YOUR SQL DUMP HERE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
	//https://stackoverflow.com/questions/21307464/can-i-import-a-mysql-dump-to-a-laravel-migration

}