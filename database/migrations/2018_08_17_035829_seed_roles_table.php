<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // create some default roles for the roles table
        $roles = [
            ['name' => 'admin'], //id 1
            ['name' => 'moderator'], //id 2
            ['name' => 'user'] //id 3
        ];

        // insert the roles into the table
        DB::table('roles')->insert($roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
