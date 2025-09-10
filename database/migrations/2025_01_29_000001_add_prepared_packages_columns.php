<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPreparedPackagesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add missing columns to package table for prepared packages functionality
        Schema::table('package', function (Blueprint $table) {
            // Add created_by column if it doesn't exist
            if (!Schema::hasColumn('package', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('hubid');
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('package', 'status')) {
                $table->integer('status')->default(0)->after('created_by');
            }
            
            // Add latest_event_id column if it doesn't exist
            if (!Schema::hasColumn('package', 'latest_event_id')) {
                $table->unsignedBigInteger('latest_event_id')->nullable()->after('status');
            }
            
            // Add created_at and updated_at if they don't exist
            if (!Schema::hasColumn('package', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('latest_event_id');
            }
            
            if (!Schema::hasColumn('package', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });

        // Add missing columns to packagemovement_events table
        Schema::table('packagemovement_events', function (Blueprint $table) {
            // Add created_by column if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('package_id');
            }
            
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'status')) {
                $table->integer('status')->default(0)->after('created_by');
            }
            
            // Add location column if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'location')) {
                $table->unsignedBigInteger('location')->nullable()->after('status');
            }
            
            // Add created_at and updated_at if they don't exist
            if (!Schema::hasColumn('packagemovement_events', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('location');
            }
            
            if (!Schema::hasColumn('packagemovement_events', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
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
        // We don't want to drop columns in case they were already being used
        // This migration is only to ensure columns exist
    }
}
