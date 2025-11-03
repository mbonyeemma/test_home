<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureRoleUserTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users') || !Schema::hasTable('roles')) {
            return;
        }

        if (!Schema::hasTable('role_user')) {
            try {
                Schema::create('role_user', function (Blueprint $table) {
                    $table->unsignedInteger('user_id');
                    $table->unsignedInteger('role_id');
                    $table->timestamps();
                    
                    $table->primary(['user_id', 'role_id']);
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                    $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
                });
            } catch (\Exception $e) {
                echo "Warning: Could not create role_user table - " . $e->getMessage() . "\n";
            }
        } else {
            try {
                Schema::table('role_user', function (Blueprint $table) {
                    if (!Schema::hasColumn('role_user', 'created_at')) {
                        $table->timestamps();
                    }
                });
            } catch (\Exception $e) {
                echo "Warning: Could not update role_user table - " . $e->getMessage() . "\n";
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration doesn't need to be reversed
        // as it only ensures table structure
    }
}
