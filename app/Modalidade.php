<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modalidade extends Model
{
    use SoftDeletes;

    protected $table = 'modalidades';

    protected $fillable = [
        'modalidade','prova', 'tipo', 'sexo', 'qtd_max',
    ];
    public function getSexoAttribute($value){
        switch($value){
            case 'M':
                return 'Masculino';
                break;
            case 'F':
                return 'Feminino';
                break;
            case 'U':
                return 'Único';
                break;
        }
        return 'Único';
    }
}
