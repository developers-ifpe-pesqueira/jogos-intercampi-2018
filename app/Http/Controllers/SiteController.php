<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function importar()
    {
    /* Limpar tabela alunos */
        DB::delete('delete from alunos');

    /* Importar do arquivo CSV */
        /* Abrir o arquivo CSV */
        $fp = fopen("C:\alunos.csv", "r");
        /* Extrair primeira linha para array cabecalho */
        $linha = fgets($fp);
        $linha = str_replace("\"", "", $linha);
        $cabecalho = explode(",", $linha);
        $colunas = count($cabecalho);
        /* Verifica se o arquivo possui todos os campos necessários */
        if (!in_array("Matrícula", $cabecalho)) die("O arquivo não possui o campo Matrícula.");
        if (!in_array("Nome", $cabecalho)) die("O arquivo não possui o campo Nome.");
        if (!in_array("Nascimento", $cabecalho)) die("O arquivo não possui o campo Nascimento.");
        if (!in_array("Sexo", $cabecalho)) die("O arquivo não possui o campo Sexo.");
        if (!in_array("CPF", $cabecalho)) die("O arquivo não possui o campo CPF.");
        if (!in_array("Instituição", $cabecalho)) die("O arquivo não possui o campo Instituição.");
        if (!in_array("Nome do Pai", $cabecalho)) die("O arquivo não possui o campo Nome do Pai.");
        if (!in_array("Nome da Mãe", $cabecalho)) die("O arquivo não possui o campo Nome da Mãe.");
        if (!in_array("Nível/Regime de Ensino", $cabecalho)) die("O arquivo não possui o campo Nível/Regime de Ensino.");
        
        /* $key = array_search('CPF', $cabecalho); */
        $cont = 2;
        $sum = 0;
        while(!feof($fp)) {
            /* Extrair linha para array dados */
            $linha = fgets($fp);
            $linha = str_replace("\"", "", $linha);
            $dados = explode(",", $linha);
            /* Recupera os dados do array */
            if (count($dados) == $colunas) {
                $matricula = $dados[array_search('Matrícula', $cabecalho)];
                $cpf = $dados[array_search('CPF', $cabecalho)];
                $nome = $dados[array_search('Nome', $cabecalho)];
                $sexo = $dados[array_search('Sexo', $cabecalho)];
                $nascimento = $dados[array_search('Nascimento', $cabecalho)];
                $nome_pai = $dados[array_search('Nome do Pai', $cabecalho)];
                $nome_mae = $dados[array_search('Nome da Mãe', $cabecalho)];
                $instituicao = $dados[array_search('Instituição', $cabecalho)];
                $agora = strtotime("now");
                $created_at = date('Y-m-d H:i:s', $agora);
                $updated_at = date('Y-m-d H:i:s', $agora);
                $nivel = $dados[array_search('Nível/Regime de Ensino', $cabecalho)];
                /* Sanear os dados recebidos */
                $cpf = str_replace(".", "", $cpf);
                $cpf = str_replace("-", "", $cpf);
                switch ($instituicao) {
                    case 'IFPE /  IGARASSU':
                        $campus = 9;
                        break;
                    case 'IFPE /  OLINDA':
                        $campus = 12;
                        break;
                    case 'IFPE /  PALMARES':
                        $campus = 13;
                        break;
                    case 'IFPE /  PAULISTA':
                        $campus = 14;
                        break;
                    case 'IFPE / ABREU E LIMA':
                        $campus = 1;
                        break;
                    case 'IFPE / AFOGADOS DA INGAZEIRA':
                        $campus = 2;
                        break;
                    case 'IFPE / BARREIROS':
                        $campus = 3;
                        break;
                    case 'IFPE / BELO JARDIM':
                        $campus = 4;
                        break;
                    case 'IFPE / CABO DE SANTO AGOSTINHO':
                        $campus = 5;
                        break;
                    case 'IFPE / CARUARU':
                        $campus = 6;
                        break;
                    case 'IFPE / EAD':
                        $campus = 7;
                        break;
                    case 'IFPE / GARANHUNS':
                        $campus = 8;
                        break;
                    case 'IFPE / IPOJUCA':
                        $campus = 10;
                        break;
                    case 'IFPE / PESQUEIRA':
                        $campus = 15;
                        break;
                    case 'IFPE / RECIFE':
                        $campus = 16;
                        break;
                    case 'IFPE / VITÓRIA DE SANTO ANTÃO':
                        $campus = 17;
                        break;
                    case 'IFPE/ JABOATÃO DOS GUARARAPES':
                        $campus = 11;
                        break;
                    default:
                        $campus = 0;
                        break;
                }
                $validado = TRUE;
            } else {
                $validado = FALSE;
                echo 'Cabeçalho: ' . count($cabecalho) . '<br>';
                echo 'LinDadosha: ' . count($dados) . '<br>';
            }
            /* Validar os dados */
            if ($matricula == '') $validado = FALSE;
            if (!$validado) exit("$matricula");
            if (strlen($cpf) != 11) $validado = FALSE;
            if (!$validado) exit("$cpf");
            if ($nome == '') $validado = FALSE;
            if (!$validado) exit("$nome");
            if ($sexo != 'M' && $sexo != 'F') $validado = FALSE;
            if (!$validado) exit("$sexo");
            if (strlen($nascimento) != 10){
                $validado = FALSE;
                if (!$validado) exit("$nascimento");
            } else {
                $dt_nascimento = strtotime(substr($nascimento,6,4) . '-' . substr($nascimento,3,2) . '-' . substr($nascimento,0,2));
                $dt_nasc = date('Y-m-d', $dt_nascimento);
            }
            if ($nivel == 'Extensão') $validado = FALSE;
            if (!$validado) exit("$nivel");

            /* Inserir os dados no BD ou imprimir linhas não inseridas*/
            if ($validado){
                DB::insert("insert into alunos 
                    (matricula , cpf, nome, sexo, nascimento, nome_pai, nome_mae, campus_id, created_at, updated_at, deleted_at)
                    values ('?', '?', '?', '?', '?', '?', '?', ?, '?', '?', null)", 
                    [
                        $matricula,
                        $cpf,
                        $nome,
                        $sexo,
                        $dt_nasc,
                        $nome_pai,
                        $nome_mae,
                        $campus,
                        $created_at,
                        $updated_at,
                    ]
                );
            } else {
                echo 'Linha: ' . $cont . '<br>';
                echo $linha . '<br>';
                $sum++;
            }
            $cont++; 
          } 
          echo 'Total de linhas não inseridas: ' . $sum . '<br>';
         fclose($fp);
    }

}
