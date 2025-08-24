<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDataCollectorRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the role already exists to avoid duplicates
        $existingRole = DB::table('roles')->where('name', 'data_collector')->first();
        
        if (!$existingRole) {
            DB::table('roles')->insert([
                'name' => 'data_collector',
                'display_name' => 'Data Collector',
                'description' => 'Data Collector role for mobile app users',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->where('name', 'data_collector')->delete();
    }
}
