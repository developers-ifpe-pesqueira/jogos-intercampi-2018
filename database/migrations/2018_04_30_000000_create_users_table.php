<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->integer('campus_id')->unsigned();
            $table->boolean('admin')->default(FALSE);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('campus_id')->references('id')->on('campi')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropForeign(['campus_id']);
        });
        Schema::dropIfExists('users');
    }
}
