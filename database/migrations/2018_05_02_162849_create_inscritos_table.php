<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInscritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscritos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('campus_id')->unsigned();
            $table->integer('modalidade_id')->unsigned();
            $table->integer('aluno_id')->unsigned();     
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('campus_id')->references('id')->on('campi')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('modalidade_id')->references('id')->on('modalidades')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('aluno_id')->references('id')->on('alunos')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inscritos', function (Blueprint $table){
            $table->dropForeign(['campus_id']);
            $table->dropForeign(['modalidade_id']);
            $table->dropForeign(['aluno_id']);
        });
        Schema::dropIfExists('inscritos');
    }
}
