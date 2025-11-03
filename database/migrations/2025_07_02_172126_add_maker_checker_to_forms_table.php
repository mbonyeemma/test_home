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
            if (!Schema::hasColumn('forms', 'submitted_by')) {
                $table->unsignedInteger('submitted_by')->nullable()->after('publish_status');
            }
            if (!Schema::hasColumn('forms', 'approved_by')) {
                $table->unsignedInteger('approved_by')->nullable()->after('submitted_by');
            }
        });

        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $indexesFound = $sm->listTableIndexes('forms');
        
        if (!isset($indexesFound['forms_submitted_by_foreign'])) {
            Schema::table('forms', function (Blueprint $table) {
                $table->foreign('submitted_by')->references('id')->on('users')->onDelete('set null');
            });
        }
        
        if (!isset($indexesFound['forms_approved_by_foreign'])) {
            Schema::table('forms', function (Blueprint $table) {
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            });
        }
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
