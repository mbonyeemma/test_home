<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsurePackageTablesStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Ensure package table has necessary columns
        Schema::table('package', function (Blueprint $table) {
            // Add created_at if it doesn't exist
            if (!Schema::hasColumn('package', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            
            // Add updated_at if it doesn't exist
            if (!Schema::hasColumn('package', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
            
            // Add created_by if it doesn't exist
            if (!Schema::hasColumn('package', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
            
            // Add status if it doesn't exist
            if (!Schema::hasColumn('package', 'status')) {
                $table->integer('status')->default(0);
            }
            
            // Add latest_event_id if it doesn't exist
            if (!Schema::hasColumn('package', 'latest_event_id')) {
                $table->unsignedBigInteger('latest_event_id')->nullable();
            }
        });

        // Ensure packagemovement_events table has necessary columns
        Schema::table('packagemovement_events', function (Blueprint $table) {
            // Add created_at if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            
            // Add updated_at if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
            
            // Add created_by if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
            
            // Add status if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'status')) {
                $table->integer('status')->default(0);
            }
            
            // Add location if it doesn't exist
            if (!Schema::hasColumn('packagemovement_events', 'location')) {
                $table->unsignedBigInteger('location')->nullable();
            }
        });

        // Ensure facility table has necessary columns
        Schema::table('facility', function (Blueprint $table) {
            // Add name if it doesn't exist
            if (!Schema::hasColumn('facility', 'name')) {
                $table->string('name')->nullable();
            }
        });

        // Ensure testtypes table has necessary columns
        Schema::table('testtypes', function (Blueprint $table) {
            // Add name if it doesn't exist
            if (!Schema::hasColumn('testtypes', 'name')) {
                $table->string('name')->nullable();
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
