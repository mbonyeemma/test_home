<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('password_resets')) {
            Schema::create('password_resets', function (Blueprint $table) {
                $table->increments('id');
                $table->string('phone_number', 20);
                $table->string('otp', 6);
                $table->string('reset_token', 100);
                $table->boolean('is_verified')->default(false);
                $table->timestamp('expires_at');
                $table->timestamps();
                
                $table->index('phone_number');
                $table->index('reset_token');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('password_resets');
    }
}

