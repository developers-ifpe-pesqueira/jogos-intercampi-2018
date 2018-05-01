<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CampiSeeder::class);
        $this->call(UsuariosSeeder::class);
        $this->call(ModalidadesSeeder::class);
    }
}
