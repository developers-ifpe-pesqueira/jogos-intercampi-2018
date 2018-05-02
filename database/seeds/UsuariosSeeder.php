<?php

use Illuminate\Database\Seeder;
use App\User;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'cgti@pesqueira.ifpe.edu.br',
            'password' => bcrypt('741852963'),
            'campus_id' => 15,
            'admin' => TRUE,
        ]);
        User::create([
            'name' => 'Carlos Eduardo Correia da Silva',
            'email' => 'carlos.correia@pesqueira.ifpe.edu.br',
            'password' => bcrypt('2162303'),
            'campus_id' => 15,
            'admin' => FALSE,
        ]); 
    }
}
