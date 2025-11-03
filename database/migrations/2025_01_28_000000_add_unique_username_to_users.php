<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueUsernameToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $indexExists = DB::select("
            SHOW INDEX FROM users WHERE Key_name = 'users_username_unique'
        ");
        
        if (empty($indexExists)) {
            $duplicates = DB::select("
                SELECT username, COUNT(*) as count 
                FROM users 
                GROUP BY username 
                HAVING count > 1
            ");
            
            if (empty($duplicates)) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unique('username', 'users_username_unique');
                });
            } else {
                echo "Warning: Cannot add unique constraint - duplicate usernames exist:\n";
                foreach ($duplicates as $dup) {
                    echo "  - {$dup->username} ({$dup->count} occurrences)\n";
                }
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_username_unique');
        });
    }
}

