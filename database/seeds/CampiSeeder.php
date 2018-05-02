<?php

use Illuminate\Database\Seeder;
use App\Campus;

class CampiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Campus::create([
            'campus' => 'Abreu e Lima',
        ]);
        Campus::create([
            'campus' => 'Afogados da Ingazeira',
        ]);
        Campus::create([
            'campus' => 'Barreiros',
        ]);
        Campus::create([
            'campus' => 'Belo Jardim',
        ]);
        Campus::create([
            'campus' => 'Cabo de Santo Agostinho',
        ]);
        Campus::create([
            'campus' => 'Caruaru',
        ]);
        Campus::create([
            'campus' => 'EAD',
        ]);
        Campus::create([
            'campus' => 'Garanhuns',
        ]);
        Campus::create([
            'campus' => 'Igarassu',
        ]);
        Campus::create([
            'campus' => 'Ipojuca',
        ]);
        Campus::create([
            'campus' => 'Jaboatão dos Guararapes',
        ]);
        Campus::create([
            'campus' => 'Olinda',
        ]);
        Campus::create([
            'campus' => 'Palmares',
        ]);
        Campus::create([
            'campus' => 'Paulista',
        ]);
        Campus::create([
            'campus' => 'Pesqueira',
        ]);
        Campus::create([
            'campus' => 'Recife',
        ]);
        Campus::create([
            'campus' => 'Vitória de Santo Antão',
        ]); 

    }
}
