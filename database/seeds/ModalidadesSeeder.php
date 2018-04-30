<?php

use Illuminate\Database\Seeder;
use App\Modalidade;

class ModalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Modalidade::create([
            'modalidade' => 'Atletismo', 
            'tipo' => 'Individual', 
            'sexo' => 'M', 
            'qtd_max' => 2,
        ]);
        Modalidade::create([
            'modalidade' => 'Atletismo', 
            'tipo' => 'Individual', 
            'sexo' => 'F', 
            'qtd_max' => 2,
        ]);
    }
}
