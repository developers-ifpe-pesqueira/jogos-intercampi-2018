<?php

use Illuminate\Database\Seeder;
use App\Categoria;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categoria::create([
            'categoria' => 'SUB 19', 
            'dt_nascimento_limite' => '2001-01-01',
        ]);
        /* Categoria::create([
            'categoria' => 'SUB 25', 
            'dt_nascimento_limite' => '1993-01-01',
        ]); */
    }
}
