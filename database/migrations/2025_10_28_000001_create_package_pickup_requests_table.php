<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagePickupRequestsTable extends Migration
{
    public function up()
    {
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
    }

    public function down()
    {
        Schema::dropIfExists('package_pickup_requests');
    }
}

