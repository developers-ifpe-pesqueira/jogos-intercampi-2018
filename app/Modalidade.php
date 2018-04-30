<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Modalidade extends Model
{
    use SoftDeletes;

    protected $table = 'modalidades';

    protected $fillable = [
        'modalidade', 'tipo', 'sexo', 'qtd_max',
    ];
}
