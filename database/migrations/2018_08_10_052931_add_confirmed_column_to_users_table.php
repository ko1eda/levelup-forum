<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConfirmedColumnToUsersTable extends Migration
{
    /**
     * Add user email confirmatin to users table
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('confirmed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * if you roll back the migration
     * the table will be dropped
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('confirmed');
        });
    }
}
