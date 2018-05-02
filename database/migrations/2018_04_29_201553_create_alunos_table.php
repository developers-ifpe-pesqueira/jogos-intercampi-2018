<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlunosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricula', 30)->unique();
            $table->string('cpf', 11);
            $table->string('nome');
            $table->enum('sexo', ['M', 'F']);
            $table->date('nascimento');
            $table->string('nome_pai');
            $table->string('nome_mae');
            $table->integer('campus_id')->unsigned();       
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
        Schema::table('alunos', function (Blueprint $table){
            $table->dropForeign(['campus_id']);
        });
        Schema::dropIfExists('alunos');
    }
}
