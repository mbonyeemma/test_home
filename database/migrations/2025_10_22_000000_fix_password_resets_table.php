<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPasswordResetsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('password_resets')) {
            if (!Schema::hasColumn('password_resets', 'phone_number')) {
                Schema::table('password_resets', function (Blueprint $table) {
                    $table->string('phone_number', 20)->after('id');
                    $table->string('otp', 6)->after('phone_number');
                    $table->string('reset_token', 100)->after('otp');
                    $table->boolean('is_verified')->default(false)->after('reset_token');
                    $table->timestamp('expires_at')->after('is_verified');
                    
                    $table->index('phone_number');
                    $table->index('reset_token');
                });
            }
        } else {
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
        if (Schema::hasTable('password_resets')) {
            if (Schema::hasColumn('password_resets', 'phone_number')) {
                Schema::table('password_resets', function (Blueprint $table) {
                    $table->dropIndex(['phone_number']);
                    $table->dropIndex(['reset_token']);
                    $table->dropColumn(['phone_number', 'otp', 'reset_token', 'is_verified', 'expires_at']);
                });
            }
        }
    }
}

