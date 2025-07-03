<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMakerCheckerToFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('forms', function (Blueprint $table) {
        $table->unsignedInteger('submitted_by')->nullable()->after('publish_status');
        $table->unsignedInteger('approved_by')->nullable()->after('submitted_by');

        $table->foreign('submitted_by')->references('id')->on('users')->nullOnDelete();
        $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forms', function (Blueprint $table) {
            //
        });
    }
}
