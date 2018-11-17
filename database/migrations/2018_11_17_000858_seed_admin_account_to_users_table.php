<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class SeedAdminAccountToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $u = new User;
        $u->name = config('forum.admin.name');
        $u->username = config('forum.admin.username');
        $u->email = config('forum.admin.email');
        $u->password = Hash::make(env('DEFAULT_ADMIN_PASSWORD'));
        $u->role_id = config('forum.admin.role_id');
        $u->confirmed = config('forum.admin.confirmed');

        $u->save();

        // create some default roles for the roles table
        // insert the roles into the table
        // DB::table('users')->insert([$u]);
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
