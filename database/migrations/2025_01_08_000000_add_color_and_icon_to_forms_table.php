<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconAndMissingColumnsToFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {
            // Add icon column if it doesn't exist
            if (!Schema::hasColumn('forms', 'icon')) {
                $table->string('icon', 50)->default('file')->after('color');
            }
            
            // Add facility_id column if it doesn't exist
            if (!Schema::hasColumn('forms', 'facility_id')) {
                $table->unsignedBigInteger('facility_id')->nullable()->after('icon');
                $table->foreign('facility_id')->references('id')->on('facility')->onDelete('set null');
            }
            
            // Add form_submission_url column if it doesn't exist
            if (!Schema::hasColumn('forms', 'form_submission_url')) {
                $table->string('form_submission_url')->nullable()->after('form_id');
            }
            
            // Add publish_status column if it doesn't exist
            if (!Schema::hasColumn('forms', 'publish_status')) {
                $table->enum('publish_status', ['draft', 'pending_approval', 'approved'])->default('draft')->after('form_submission_url');
            }
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
            if (Schema::hasColumn('forms', 'facility_id')) {
                $table->dropForeign(['facility_id']);
                $table->dropColumn('facility_id');
            }
            
            if (Schema::hasColumn('forms', 'icon')) {
                $table->dropColumn('icon');
            }
            
            if (Schema::hasColumn('forms', 'publish_status')) {
                $table->dropColumn('publish_status');
            }
            
            if (Schema::hasColumn('forms', 'form_submission_url')) {
                $table->dropColumn('form_submission_url');
            }
        });
    }
}
