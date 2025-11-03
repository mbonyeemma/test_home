<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagePickupRequestsTableFinal extends Migration
{
    public function up()
    {
        
        if (!Schema::hasTable('package_pickup_requests')) {
            Schema::create('package_pickup_requests', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('package_id')->unsigned();
                $table->integer('requested_by')->unsigned();
                $table->integer('hub_id')->unsigned();
                $table->integer('riders_notified')->default(0);
                $table->integer('emails_sent')->default(0);
                $table->integer('sms_sent')->default(0);
                $table->integer('app_notifications_sent')->default(0);
                $table->timestamps();

                $table->index('package_id');
                $table->index('hub_id');
                $table->index('requested_by');
            });
            
            echo "✅ Table 'package_pickup_requests' created successfully!\n";
            echo "✅ Notify Riders feature is now ready to use!\n\n";
        } else {
            echo "ℹ️  Table 'package_pickup_requests' already exists, skipping creation.\n\n";
        }
    }

    public function down()
    {
        Schema::dropIfExists('package_pickup_requests');
    }
}

