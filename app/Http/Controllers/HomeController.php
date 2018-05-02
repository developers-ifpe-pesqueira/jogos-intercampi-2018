<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modalidade;
use App\Campus;
use App\Alunos;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    public function inscricoes()
    {
        $modalidades = Modalidade::all();
        if(\Auth::user()->admin){
            $campi = Campus::all();
        }else{
            $campi = Campus::where('id',\Auth::user()->campus_id)->get();
        }
        return view('inscricoes', compact('modalidades','campi'));
    }
    public function inscricoes_modaliade(Request $request)
    {
        $validatedData = $request->validate([
            'campus' => 'required|numeric',
            'modalidade' => 'required|numeric',
        ]);
        if(!\Auth::user()->admin && \Auth::user()->campus_id != $request->campus){
            abort(403);
        }
        $modalidade = Modalidade::find($request->modalidade);
        return view('inscricoes_modalidade', compact('modalidade'));
    }
    public function relacao()
    {
        return view('home');
    }

    public function importar()
    {
        $this->authorize('admin');
        return view('importar');
    }

    public function processar_importacao(Request $request)
    {
        $this->authorize('admin');
        $validatedData = $request->validate([
            'arquivo' => 'required|file',
        ]);
        //dd($request->arquivo);
    /* Limpar tabela alunos */
        DB::delete('delete from alunos');

    /* Importar do arquivo CSV */
        /* Abrir o arquivo CSV */
        $fp = fopen($request->arquivo, "r");
        /* Extrair primeira linha para array cabecalho */
        $linha = fgets($fp);
        $linha = str_replace("\"", "", $linha);
        $cabecalho = explode(",", $linha);
        $colunas = count($cabecalho);
        /* Verifica se o arquivo possui todos os campos necessários */
        $arquivo_invalido = "";
        if (!in_array("Matrícula", $cabecalho)) $arquivo_invalido .= 'Matrícula';
        if (!in_array("Nome", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nome' : $arquivo_invalido .= ', Nome';
        if (!in_array("Nascimento", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nascimento' : $arquivo_invalido .= ', Nascimento';
        if (!in_array("Sexo", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Sexo' : $arquivo_invalido .= ', Sexo';
        if (!in_array("CPF", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'CPF' : $arquivo_invalido .= ', CPF';
        if (!in_array("Instituição", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Instituição' : $arquivo_invalido .= ', Instituição';
        if (!in_array("Nome do Pai", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nome do Pai' : $arquivo_invalido .= ', Nome do Pai';
        if (!in_array("Nome da Mãe", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nome da Mãe' : $arquivo_invalido .= ', Nome da Mãe';
        //if (!in_array("Nível/Regime de Ensino", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nível/Regime de Ensino' : $arquivo_invalido .= ', Nível/Regime de Ensino';
        $msg_erro = "Arquivo inválido. O aquivo não possui os campos: $arquivo_invalido";
        if($arquivo_invalido != ""){
            return redirect()->back()->withErrors([$msg_erro]);
        }
        /* Extrair linha para array dados */
        $cont = 2;
        $linhas_ok = 0;
        $linhas_erro = 0;
        $falha = "";
        while(!feof($fp)) {
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
                //$nivel = $dados[array_search('Nível/Regime de Ensino', $cabecalho)];
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
                $falha .= "Linha $cont: Quantidade de campos incompatível.<br>";
            }
            /* Validar os dados */
            if ($matricula == '') {
                $validado = FALSE;
                $falha .= "Linha $cont: Matrícula inválida.<br>";
            }
            if (strlen($cpf) != 11) {
                $validado = FALSE;
                $falha .= "Linha $cont: CPF inválido.<br>";
            }
            if ($nome == '') {
                $validado = FALSE;
                $falha .= "Linha $cont: Nome inválido.<br>";
            }
            if ($sexo != 'M' && $sexo != 'F') {
                $validado = FALSE;
                $falha .= "Linha $cont: Sexo inválido.<br>";
            }
            if (strlen($nascimento) != 10){
                $validado = FALSE;
                $falha .= "Linha $cont: Nascimento inválido.<br>";
            } else {
                $dt_nascimento = strtotime(substr($nascimento,6,4) . '-' . substr($nascimento,3,2) . '-' . substr($nascimento,0,2));
                $dt_nasc = date('Y-m-d', $dt_nascimento);
            }
            /* if ($nivel == 'Extensão') $validado = FALSE; */

            /* Inserir os dados no BD ou imprimir linhas não inseridas*/
            if ($validado){
                Alunos::create([
                    'matricula' => $matricula,
                    'cpf' => $cpf,
                    'nome' => $nome,
                    'sexo' => $sexo,
                    'nascimento' => $dt_nasc,
                    'nome_pai' => $nome_pai,
                    'nome_mae' => $nome_mae,
                    'campus_id' => $campus,
                ]); 
                $linhas_ok++;
            } else {
                $linhas_erro++;
            }
            $cont++; 
          } 
         fclose($fp);
         $msg_ok = "Importação realizada com sucesso. $linhas_ok registros importados.";
         return view('importar', compact('falha'))->with('sucesso', $msg_ok);
         //return redirect()->route('importar', compact('falha'))->with('sucesso', $msg_ok);
    }

}
