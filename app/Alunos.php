<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alunos extends Model
{
    use SoftDeletes;

    protected $table = 'alunos';

    protected $fillable = [
        'matricula' , 'cpf', 'nome', 'sexo', 'nascimento', 'turma','nome_pai', 'nome_mae', 'campus_id',
    ];
    
    public function getIdadeAttribute(){
        $tz  = new \DateTimeZone(config('app.timezone', 'America/Recife'));
        $age = \DateTime::createFromFormat('Y-m-d', $this->attributes['nascimento'], $tz)
                    ->diff(new \DateTime('now', $tz))
                    ->y;
        return $age;
    }
}
