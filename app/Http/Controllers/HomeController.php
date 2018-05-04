<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modalidade;
use App\Campus;
use App\Alunos;
use App\Inscrito;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    
    public function inscricoes_modalidade(Request $request)
    {
        $validatedData = $request->validate([
            'campus' => 'required|numeric',
            'modalidade' => 'required|numeric',
        ]);
        if(!\Auth::user()->admin && \Auth::user()->campus_id != $request->campus){
            abort(403);
        }
        $campus = Campus::find($request->campus);
        $modalidade = Modalidade::find($request->modalidade);
        if ($modalidade->sexo_abrev == 'U'){
            $alunos = Alunos::where('campus_id', $campus->id)->get();
        } else {
            $alunos = Alunos::where('campus_id', $campus->id)->where('sexo', $modalidade->sexo_abrev)->get();
        }
        $inscritos = Inscrito::with('aluno')->where('campus_id', $campus->id)->where('modalidade_id', $modalidade->id)->get();
        
        return view('inscricoes_modalidade', compact('modalidade', 'campus', 'alunos', 'inscritos'));
    }
    
    public function inscricoes_adicionar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campus_id' => 'required|numeric',
            'modalidade_id' => 'required|numeric',
            'aluno_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        if(!\Auth::user()->admin && \Auth::user()->campus_id != $request->campus_id){
            abort(403);
        }

        $campus = Campus::find($request->campus_id);
        $modalidade = Modalidade::find($request->modalidade_id);
        if ($modalidade->sexo_abrev == 'U'){
            $alunos = Alunos::where('campus_id', $campus->id)->get();
        } else {
            $alunos = Alunos::where('campus_id', $campus->id)->where('sexo', $modalidade->sexo_abrev)->get();
        }
        $inscritos = Inscrito::with('aluno')->where('campus_id', $campus->id)->where('modalidade_id', $modalidade->id)->get();

        /* Verifica se o estudante já está inscrito */
        $inscrito = Inscrito::where('campus_id', $request->campus_id)
                            ->where('modalidade_id', $request->modalidade_id)
                            ->where('aluno_id', $request->aluno_id)
                            ->first();
        if (!is_null($inscrito)){
            $msg_erro = 'Estudante já inscrito nesta modalidade.';
            return view('inscricoes_modalidade', compact('modalidade', 'campus', 'alunos', 'inscritos'))->with('erro', $msg_erro);
        }

        /* Verifica a quantidade máxima de participantes para esta modalidade*/
        $qtd_inscritos = count($inscritos);
        if ($qtd_inscritos >= $modalidade->qtd_max){
            $msg_erro = 'Inscrição não realizada. Limite de inscritos para esta modalidade foi excedido.';
            return view('inscricoes_modalidade', compact('modalidade', 'campus', 'alunos', 'inscritos'))->with('erro', $msg_erro);
        }

        
        /* Verifica quantidade de modalidades em que o estudante já está inscrito (LIMITE: 3)*/
        $aluno_modalidades = Inscrito::with('modalidade')->where('aluno_id', $request->aluno_id)->get();
        $array_modalidades = [];
        foreach ($aluno_modalidades as $am){
            
            if (!array_key_exists($am->modalidade->modalidade, $array_modalidades)){
                $array_modalidades[$am->modalidade->modalidade] = [];
            }
            
            if ($am->modalidade->prova != ''){
                if (!in_array( $am->modalidade->prova, $array_modalidades[$am->modalidade->modalidade] )){
                    array_push($array_modalidades[$am->modalidade->modalidade], $am->modalidade->prova);
                }
            }
        }
        if (count($array_modalidades) > 0){
            if (count($array_modalidades) >= 3 && !array_key_exists($modalidade->modalidade, $array_modalidades)){
                /* Verifica quantidade de modalidades em que o estudante já está inscrito (LIMITE: 3)*/
                $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em 3 modalidades: ';
                foreach ($array_modalidades as $k => $v){
                    $msg_erro .= ($k . ', ');
                }
                $msg_erro = substr($msg_erro, 0, -2) . '.';
                return view('inscricoes_modalidade', compact('modalidade', 'campus', 'alunos', 'inscritos'))->with('erro', $msg_erro);
            } else {
                /* Verifica quantidade de provas desta modalidade em que o estudante já está inscrito (LIMITE: 3)*/
                if (count($array_modalidades[$modalidade->modalidade]) >= 3){
                    $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em 3 provas desta modalidade: ';
                    foreach ($array_modalidades[$modalidade->modalidade] as $v){
                        $msg_erro .= ($v . ', ');
                    }
                    $msg_erro = substr($msg_erro, 0, -2) . '.';
                    return view('inscricoes_modalidade', compact('modalidade', 'campus', 'alunos', 'inscritos'))->with('erro', $msg_erro);
                }
            }
        }

        Inscrito::create([
            'campus_id' => $request->campus_id,
            'modalidade_id' => $request->modalidade_id,
            'aluno_id' => $request->aluno_id,
        ]);
        
        $inscritos = Inscrito::with('aluno')->where('campus_id', $campus->id)->where('modalidade_id', $modalidade->id)->get();

        $msg_ok = "Estudande inscrito com sucesso!";
        return view('inscricoes_modalidade', compact('modalidade', 'campus', 'alunos', 'inscritos'))->with('sucesso', $msg_ok);
    
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
    /* Limpar tabela alunos */
        DB::delete('delete from alunos');

    /* Importar do arquivo CSV */
        /* Abrir o arquivo CSV */
        $fp = fopen($request->arquivo, "r");
        /* Extrair primeira linha para array cabecalho */
        $linha = fgets($fp);
        if (!mb_detect_encoding($linha, 'UTF-8', true)){
            $linha = utf8_encode($linha);
        }
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
        if (!in_array("Turma", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Turma' : $arquivo_invalido .= ', Turma';
        if (!in_array("Nome do Pai", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nome do Pai' : $arquivo_invalido .= ', Nome do Pai';
        if (!in_array("Nome da Mãe", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nome da Mãe' : $arquivo_invalido .= ', Nome da Mãe';
        if (!in_array("Nível/Regime de Ensino", $cabecalho)) $arquivo_invalido == "" ? $arquivo_invalido .= 'Nível/Regime de Ensino' : $arquivo_invalido .= ', Nível/Regime de Ensino';
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
            if (!mb_detect_encoding($linha, 'UTF-8', true)){
                $linha = utf8_encode($linha);
            }
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
                $turma = $dados[array_search('Turma', $cabecalho)];
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
                $falha = "Quantidade de campos incompatível; ";
            }
            /* Validar os dados */
            if ($validado) {
                if ($matricula == '') {
                    $validado = FALSE;
                    $falha .= "Matrícula inválida; ";
                }
                if (strlen($cpf) != 11) {
                    $validado = FALSE;
                    $falha .= "CPF inválido; ";
                }
                if ($nome == '') {
                    $validado = FALSE;
                    $falha .= "Nome inválido; ";
                }
                if ($campus == 0) {
                    $validado = FALSE;
                    $falha .= "Instituição/Campus inválido; ";
                }
                if ($sexo != 'M' && $sexo != 'F') {
                    $validado = FALSE;
                    $falha .= "Sexo inválido; ";
                }
                if (strlen($nascimento) != 10){
                    $validado = FALSE;
                    $falha .= "Nascimento inválido; ";
                } else {
                    $dt_nascimento = strtotime(substr($nascimento,6,4) . '-' . substr($nascimento,3,2) . '-' . substr($nascimento,0,2));
                    $dt_nasc = date('Y-m-d', $dt_nascimento);
                }
                $data_limite =  strtotime('1999-01-01');
                if ($dt_nascimento < $data_limite){
                    $validado = FALSE;
                    $falha .= "Filtrada pela data de nascimento; ";
                }
                if ($nivel == 'Extensão' || $nivel == 'Formação Inicial e Continuada - FIC') {
                    $validado = FALSE;
                    $falha .= "Filtrada pelo Nível/Regime de Ensino; ";
                }
            }
            /* Inserir os dados no BD ou imprimir linhas não inseridas*/
            if ($validado){
                Alunos::create([
                    'matricula' => $matricula,
                    'cpf' => $cpf,
                    'nome' => $nome,
                    'sexo' => $sexo,
                    'nascimento' => $dt_nasc,
                    'turma' => $turma,
                    'nome_pai' => $nome_pai,
                    'nome_mae' => $nome_mae,
                    'campus_id' => $campus,
                ]); 
                $linhas_ok++;
            } else {
                $linhas_erro++;
                Log::info("Linha $cont: $falha");
            }
            $cont++; 
            $falha = '';
          } 
        fclose($fp);
        $msg_ok = "Importação realizada com sucesso. $linhas_ok registros importados.";
        return view('importar', compact('falha'))->with('sucesso', $msg_ok);
    }

}
