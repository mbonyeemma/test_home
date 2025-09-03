<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueConstraintsToRestrackselfReg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restrackself_reg', function (Blueprint $table) {
            $table->unique('username', 'restrackself_reg_username_unique');
            $table->unique('email', 'restrackself_reg_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restrackself_reg', function (Blueprint $table) {
            $table->dropUnique('restrackself_reg_username_unique');
            $table->dropUnique('restrackself_reg_email_unique');
        });
    }
}

