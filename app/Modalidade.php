<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modalidade extends Model
{
    use SoftDeletes;

    protected $table = 'modalidades';

    protected $fillable = [
        'modalidade','prova', 'tipo', 'tipo_prova', 'sexo', 'qtd_min', 'qtd_max', 'categoria_id'
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
    public function getSexoAbrevAttribute(){
        return $this->attributes['sexo'];
    }
    public function getNomeAttribute(){
        $str_modalidade = "";
        $str_modalidade .= $this->categoria->categoria . ' - ';
        $str_modalidade .= $this->attributes['modalidade'];
        if ($this->attributes['prova'] != ''){
            $str_modalidade .= ' - ';
            $str_modalidade .= $this->attributes['prova'];
        }
        $str_modalidade .= ' (';
        $str_modalidade .= $this->sexo;
        $str_modalidade .= ')';
        return $str_modalidade;
    }

     /* Relacionamentos N:1 */
     public function categoria()
     {
         return $this->belongsTo('App\Categoria', 'categoria_id');
     }

}
