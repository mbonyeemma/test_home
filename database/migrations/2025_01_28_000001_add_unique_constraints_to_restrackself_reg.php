<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
        $usernameIndexExists = DB::select("
            SHOW INDEX FROM restrackself_reg WHERE Key_name = 'restrackself_reg_username_unique'
        ");
        
        if (empty($usernameIndexExists)) {
            $duplicates = DB::select("
                SELECT username, COUNT(*) as count 
                FROM restrackself_reg 
                GROUP BY username 
                HAVING count > 1
            ");
            
            if (empty($duplicates)) {
                Schema::table('restrackself_reg', function (Blueprint $table) {
                    $table->unique('username', 'restrackself_reg_username_unique');
                });
            } else {
                echo "Warning: Cannot add username unique constraint - duplicates exist\n";
            }
        }
        
        $emailIndexExists = DB::select("
            SHOW INDEX FROM restrackself_reg WHERE Key_name = 'restrackself_reg_email_unique'
        ");
        
        if (empty($emailIndexExists)) {
            $duplicates = DB::select("
                SELECT email, COUNT(*) as count 
                FROM restrackself_reg 
                GROUP BY email 
                HAVING count > 1
            ");
            
            if (empty($duplicates)) {
                Schema::table('restrackself_reg', function (Blueprint $table) {
                    $table->unique('email', 'restrackself_reg_email_unique');
                });
            } else {
                echo "Warning: Cannot add email unique constraint - duplicates exist\n";
            }
        }
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

