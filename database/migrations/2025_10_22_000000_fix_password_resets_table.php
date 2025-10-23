<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixPasswordResetsTable extends Migration
{
    public function up()
    {
        DB::statement('DROP TABLE IF EXISTS password_resets');
        
        Schema::create('password_resets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone_number', 20);
            $table->string('otp', 6);
            $table->string('reset_token', 100);
            $table->boolean('is_verified')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            
            $table->index('phone_number');
            $table->index('reset_token');
        });
        
        echo "password_resets table recreated successfully with all required columns!\n";
    }

    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}

