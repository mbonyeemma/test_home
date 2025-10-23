<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FixPasswordResetsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('password_resets')) {
            $hasPhoneNumber = Schema::hasColumn('password_resets', 'phone_number');
            
            if (!$hasPhoneNumber) {
                DB::statement('RENAME TABLE password_resets TO password_resets_old');
                
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
                
                DB::statement('DROP TABLE IF EXISTS password_resets_old');
            }
        } else {
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
        }
    }

    public function down()
    {
        if (Schema::hasTable('password_resets')) {
            Schema::dropIfExists('password_resets');
        }
    }
}

