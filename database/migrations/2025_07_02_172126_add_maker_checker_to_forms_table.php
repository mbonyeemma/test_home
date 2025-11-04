<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
        if (!Schema::hasTable('forms') || !Schema::hasTable('users')) {
            return;
        }

        Schema::table('forms', function (Blueprint $table) {
            if (!Schema::hasColumn('forms', 'submitted_by')) {
                $table->unsignedInteger('submitted_by')->nullable()->after('publish_status');
            }
            if (!Schema::hasColumn('forms', 'approved_by')) {
                $table->unsignedInteger('approved_by')->nullable()->after('submitted_by');
            }
        });

        try {
            $submittedByFkExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'forms' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND CONSTRAINT_NAME = 'forms_submitted_by_foreign'
            ");
            
            if (empty($submittedByFkExists) && Schema::hasColumn('forms', 'submitted_by')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->foreign('submitted_by')->references('id')->on('users')->onDelete('set null');
                });
            }
            
            $approvedByFkExists = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.TABLE_CONSTRAINTS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'forms' 
                AND CONSTRAINT_TYPE = 'FOREIGN KEY' 
                AND CONSTRAINT_NAME = 'forms_approved_by_foreign'
            ");
            
            if (empty($approvedByFkExists) && Schema::hasColumn('forms', 'approved_by')) {
                Schema::table('forms', function (Blueprint $table) {
                    $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
                });
            }
        } catch (\Exception $e) {
            echo "Warning: Could not add foreign keys to forms table - " . $e->getMessage() . "\n";
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
