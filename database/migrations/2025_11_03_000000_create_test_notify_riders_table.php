<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestNotifyRidersTable extends Migration
{
    public function up()
    {
        echo "\n✅ TEST MIGRATION RUNNING - MIGRATIONS ARE WORKING! ✅\n\n";
        
        if (!Schema::hasTable('test_notify_riders_migration')) {
            Schema::create('test_notify_riders_migration', function (Blueprint $table) {
                $table->increments('id');
                $table->string('test_column')->default('Migration system works!');
                $table->timestamps();
            });
            
            echo "✅ Test table 'test_notify_riders_migration' created successfully!\n\n";
        }
    }

    public function down()
    {
        Schema::dropIfExists('test_notify_riders_migration');
    }
}

