<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prova extends Model
{
    use SoftDeletes;

    protected $table = 'provas';

    protected $fillable = [
        'modalidade_id', 'prova',
    ];
}
