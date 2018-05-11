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
    public function getNomeAttribute($value){
        $encoding = 'UTF-8'; // ou ISO-8859-1...
        return mb_convert_case($value, MB_CASE_UPPER, $encoding);;
    }
    public function getNomeMaeAttribute($value){
        $encoding = 'UTF-8'; // ou ISO-8859-1...
        return mb_convert_case($value, MB_CASE_UPPER, $encoding);;
    }
    public function getNomeAnsiAttribute(){
        $value =  iconv( 'UTF-8', 'ASCII//TRANSLIT', $this->attributes['nome'] );
        $value = preg_replace( '/[`^~\'"]/', null, $value);
        return $value;
    }
}
