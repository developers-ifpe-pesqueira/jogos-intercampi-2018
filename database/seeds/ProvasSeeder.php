<?php

use Illuminate\Database\Seeder;
use App\Prova;

class ProvasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Prova::create([
            'modalidade_id' => 1, 
            'prova' => 'Arremesso de Peso',
        ]);
        Prova::create([
            'modalidade_id' => 1, 
            'prova' => 'Lançamento de Dardo',
        ]);
        Prova::create([
            'modalidade_id' => 1, 
            'prova' => 'Lançamento de Disco',
        ]);
    }
}
