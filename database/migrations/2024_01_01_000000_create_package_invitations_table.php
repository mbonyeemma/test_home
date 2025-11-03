<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('package_invitations')) {
            Schema::create('package_invitations', function (Blueprint $table) {
                $table->increments('id');
            $table->string('package_id');
            $table->string('barcode');
            $table->string('package_name');
            $table->string('numbe_of_samples');
            $table->string('package_type');
            $table->string('facility_name');
            $table->string('prepared_by');
            $table->datetime('date_prepared');
            $table->string('invited_email');
            $table->enum('status', ['sent', 'accepted', 'declined', 'expired'])->default('sent');
            $table->timestamps();
            
            $table->index(['barcode', 'invited_email']);
            $table->index('status');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_invitations');
    }
}
