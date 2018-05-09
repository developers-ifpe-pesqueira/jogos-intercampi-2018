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
        // Abreu e Lima
        // Afogados da Ingazeira
        User::create([
            'name' => 'João Gabriel Eugênio Araújo',
            'email' => 'joao.araujo@afogados.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 2,
            'admin' => FALSE,
        ]);
        // Barreiros
        User::create([
            'name' => 'Jose Nildo Alves Cau',
            'email' => 'caunildo@barreiros.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 3,
            'admin' => FALSE,
        ]);
        User::create([
            'name' => 'Petrucio Venceslau de Moura',
            'email' => 'petruciomoura@barreiros.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 3,
            'admin' => FALSE,
        ]);
        User::create([
            'name' => 'Adoniram Gonçalves de Amorim',
            'email' => 'doniamorim@barreiros.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 3,
            'admin' => FALSE,
        ]);
        // Belo Jardim
        User::create([
            'name' => 'Jairo Bezerra de Sales',
            'email' => 'jairo.sales@belojardim.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 4,
            'admin' => FALSE,
        ]);
        User::create([
            'name' => 'Debora Batista Maciel de Andrade',
            'email' => 'debora.andrade@belojardim.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 4,
            'admin' => FALSE,
        ]);
        // Cabo de Santo Agostinho
        User::create([
            'name' => 'Gabriela Bormann de Souza Lira',
            'email' => 'gabriela.lira@cabo.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 5,
            'admin' => FALSE,
        ]);
        // Caruaru
        User::create([
            'name' => 'Vilma Canazart do Santos',
            'email' => 'vilma.santos@caruaru.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 6,
            'admin' => FALSE,
        ]);
        User::create([
            'name' => 'Leone Severino do Nascimento',
            'email' => 'leone.nascimento@caruaru.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 6,
            'admin' => FALSE,
        ]);
        // EAD
        // Garanhuns
        User::create([
            'name' => 'João Paulo dos Santos Oliveira',
            'email' => 'joao.santos@garanhuns.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 8,
            'admin' => FALSE,
        ]);
        // Igarassu
        // Ipojuca
        // Jaboatão dos Guararapes
        User::create([
            'name' => 'Bonifácio Muniz de Farias Filho',
            'email' => 'boni.muniz@jaboatao.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 11,
            'admin' => FALSE,
        ]);
        // Olinda
        // Palmares
        User::create([
            'name' => 'Paulo Vitor Nascimento de Sousa',
            'email' => 'paulo.souza@palmares.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 13,
            'admin' => FALSE,
        ]);
        // Paulista
        // Pesqueira
        User::create([
            'name' => 'Carlos Eduardo Correia da Silva',
            'email' => 'carlos.correia@pesqueira.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 15,
            'admin' => FALSE,
        ]);
        User::create([
            'name' => 'Roberto Mauro Guimaraes Cavalcanti',
            'email' => 'roberto-mauro@pesqueira.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 15,
            'admin' => FALSE,
        ]);
        // Recife
        User::create([
            'name' => 'Kenio de Salles Menezes',
            'email' => 'keniomenezes@recife.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 16,
            'admin' => FALSE,
        ]);
        // Vitória de Santo Antão
        User::create([
            'name' => 'Iunaly Sumaia da Costa Ataide',
            'email' => 'iunaly.ataide@vitoria.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 17,
            'admin' => FALSE,
        ]);
        User::create([
            'name' => 'Renato Barboza de Souza Junior',
            'email' => 'renato.barbosa@vitoria.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 17,
            'admin' => FALSE,
        ]);
        User::create([
            'name' => 'Flavio Roberto Carneiro de Medeiros',
            'email' => 'flavio.roberto@vitoria.ifpe.edu.br',
            'password' => bcrypt(str_random(10)),
            'campus_id' => 17,
            'admin' => FALSE,
        ]);
    }
}
