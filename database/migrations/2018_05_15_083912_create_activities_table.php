<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     * note that an index referes
     * to a clustered index aka, how sql
     * groups related data,
     * essentially it will keep all
     * user_ids clustered together in memory
     * so that it is easier to search 
     * since we will be searching by user id
     * NOTE there can only be one clustered index
     * PRIMARY KEY is a non-clustered index
     * https://stackoverflow.com/questions/2955459/what-is-an-index-in-sql
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->index();
            $table->string('type', 50);
            $table->morphs('subject');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
