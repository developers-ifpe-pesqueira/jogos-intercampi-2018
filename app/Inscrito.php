<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inscrito extends Model
{
    protected $table = 'inscritos';

    protected $fillable = [
        'campus_id' , 'modalidade_id', 'aluno_id', 'confirmado'
    ];

    /* Relacionamentos N:1 */
    public function aluno()
	{
		return $this->belongsTo('App\Alunos', 'aluno_id');
    }
    public function modalidade()
	{
		return $this->belongsTo('App\Modalidade', 'modalidade_id');
    }
    public function campus()
	{
		return $this->belongsTo('App\Campus', 'campus_id');
    }
}
