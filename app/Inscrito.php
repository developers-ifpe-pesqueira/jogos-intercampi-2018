<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inscrito extends Model
{
    use SoftDeletes;

    protected $table = 'inscritos';

    protected $fillable = [
        'campus_id' , 'modalidade_id', 'aluno_id',
    ];

    /* Relacionamentos N:1 */
    public function aluno()
	{
		return $this->belongsTo('App\Alunos', 'aluno_id');
    }
}
