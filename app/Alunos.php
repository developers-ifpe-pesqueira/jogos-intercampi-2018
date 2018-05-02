<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alunos extends Model
{
    use SoftDeletes;

    protected $table = 'alunos';

    protected $fillable = [
        'matricula' , 'cpf', 'nome', 'sexo', 'nascimento', 'nome_pai', 'nome_mae', 'campus_id',
    ];
}
