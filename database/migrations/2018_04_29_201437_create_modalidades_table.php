<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModalidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modalidades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('modalidade');
            $table->enum('tipo', ['Coletiva', 'Individual','']);
            $table->string('prova');
            $table->enum('tipo_prova', ['Coletiva', 'Individual'])->nullable()->defaut(null);
            $table->enum('sexo', ['M', 'F','U']);
            $table->integer('qtd_min')->default(0);
            $table->integer('qtd_max');
            $table->integer('categoria_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('categoria_id')->references('id')->on('categorias')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modalidades', function (Blueprint $table){
            $table->dropForeign(['categoria_id']);
        });
        Schema::dropIfExists('modalidades');
    }
}
