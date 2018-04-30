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
            $table->string('matricula');
            $table->string('cpf', 11)->unique();
            $table->string('nome');
            $table->enum('sexo', ['M', 'F']);
            $table->date('nascimento');
            $table->string('nome_pai');
            $table->string('nome_mae');
            $table->integer('campus_id')->unsigned();       
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alunos');
    }
}
