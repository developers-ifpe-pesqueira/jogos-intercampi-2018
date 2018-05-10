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
        $campus = Campus::find(\Auth::user()->campus_id);
        $inscritos = DB::Select('SELECT `aluno_id` FROM `inscritos` WHERE `campus_id` = ? GROUP BY `aluno_id`',
                                [$campus->id]);
        $qtd_inscritos = count($inscritos);
        $sql = "SELECT 
                    A.modalidade, masc, fem, unic, masc_id, fem_id, unic_id, masc_min, fem_min, unic_min
                FROM
                    (SELECT
                        CONCAT(modalidade, IF(prova != '', ' - ','') ,prova) AS modalidade
                    FROM
                        modalidades
                    WHERE
                        1
                    GROUP BY
                        modalidade, prova) AS A
                    LEFT JOIN
                    (SELECT
                        CONCAT(m.modalidade, IF(m.prova != '', ' - ','') ,m.prova) AS modalidade, m.id AS masc_id, m.qtd_min AS masc_min, count(i.id) as masc
                    FROM
                        inscritos i, modalidades m
                    WHERE
                        m.id = i.modalidade_id AND
                        m.sexo = 'M' AND
                        i.campus_id = ?
                    GROUP BY
                        m.modalidade, m.prova) AS B
                    ON A.modalidade = B.modalidade
                    LEFT JOIN
                    (SELECT
                        CONCAT(m.modalidade, IF(m.prova != '', ' - ','') ,m.prova) AS modalidade, m.id AS fem_id, m.qtd_min AS fem_min, count(i.id) as fem
                    FROM
                        inscritos i, modalidades m
                    WHERE
                        m.id = i.modalidade_id AND
                        m.sexo = 'F' AND
                        i.campus_id = ?
                    GROUP BY
                        m.modalidade, m.prova) as C
                    ON A.modalidade = C.modalidade
                    LEFT JOIN		
                    (SELECT
                        CONCAT(m.modalidade, IF(m.prova != '', ' - ','') ,m.prova) AS modalidade, m.id AS unic_id, m.qtd_min AS unic_min, count(i.id) as unic
                    FROM
                        inscritos i, modalidades m
                    WHERE
                        m.id = i.modalidade_id AND
                        m.sexo = 'U' AND
                        i.campus_id = ?
                    GROUP BY
                        m.modalidade, m.prova) AS D
                    ON A.modalidade = D.modalidade
                ORDER BY
                    A.modalidade";

        $modalidades_inscritos = DB::Select($sql, [$campus->id,$campus->id,$campus->id]);
        /* $modalidades_confirmadas = Inscrito::where('campus_id',$campus->id)
                                            ->where('confirmado', TRUE)
                                            ->groupBy('modalidade_id')
                                            ->count();
        $modalidades_totais = Modalidade::all()->count(); */


        $modalidades_confirmadas = DB::Select(' SELECT m.modalidade, COUNT(i.id) 
                                                FROM `inscritos` i, modalidades m 
                                                WHERE i.modalidade_id = m.id AND i.confirmado = 1 AND i.campus_id = ? 
                                                GROUP BY m.modalidade',
                                                [$campus->id]);
        $modalidades_confirmadas = count($modalidades_confirmadas);
        $modalidades_totais = Modalidade::groupBy('modalidade')->count();


        return view('home', compact('campus','qtd_inscritos','modalidades_inscritos','modalidades_confirmadas', 'modalidades_totais'));
    }
    public function inscricoes()
    {
        $modalidades = Modalidade::with('categoria')
                                ->orderBy('categoria_id')
                                ->orderBy('modalidade')
                                ->orderBy('prova')
                                ->get();
        if(\Auth::user()->admin){
            $campi = Campus::all();
        }else{
            $campi = Campus::where('id',\Auth::user()->campus_id)->get();
        }
        return view('inscricoes', compact('modalidades','campi'));
    }
    
    public function inscricoes_modalidade_v($campus_id, $modalidade_id)
    {
        
        $campus = Campus::find($campus_id);
        $modalidade = Modalidade::with('categoria')->find($modalidade_id);
        
        if(!\Auth::user()->admin && \Auth::user()->campus_id != $campus->id){
            abort(403);
        }
        if(is_null($campus) || is_null($modalidade)){
            abort(401);
        }

        $inscritos = Inscrito::with('aluno')
                        ->where('campus_id', $campus->id)
                        ->where('modalidade_id', $modalidade->id)
                        ->orderBy('aluno_id')
                        ->get();

        if ($modalidade->sexo_abrev == 'U'){
            $alunos = Alunos::where('campus_id', $campus->id)
            ->where('nascimento', '>=', $modalidade->categoria->dt_nascimento_limite)
            ->get();
        } else {
            $alunos = Alunos::where('campus_id', $campus->id)
            ->where('nascimento', '>=', $modalidade->categoria->dt_nascimento_limite)->where('sexo', $modalidade->sexo_abrev)->get();
        }
        
        return view('inscricoes_modalidade', compact('modalidade', 'campus', 'alunos', 'inscritos'));

    }
    public function inscricoes_modalidade(Request $request)
    {
        $validatedData = $request->validate([
            'campus' => 'required|numeric',
            'modalidade' => 'required|numeric',
        ]);
        return redirect()->route('inscricoes.modalidade.v',['campus'=> $request->campus, 'modalidade'=> $request->modalidade]);        
    }
    
    public function inscricoes_adicionar($campus_id, $modalidade_id, Request $request)
    {
        /* Valida os dados recebidos do formulário */
        $validator = Validator::make($request->all(), [
            'aluno_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        /* Valida se o usuário é admin ou é do Campus */
        if(!\Auth::user()->admin && \Auth::user()->campus_id != $campus_id){
            abort(403);
        }
        /* Verifica se o estudante já está inscrito */
        $inscrito = Inscrito::where('campus_id', $campus_id)
                            ->where('modalidade_id', $modalidade_id)
                            ->where('aluno_id', $request->aluno_id)
                            ->first();
        if (!is_null($inscrito)){
            $msg_erro = 'Estudante já inscrito nesta modalidade.';
            return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
        }

        /* Verifica a quantidade máxima de participantes para esta modalidade*/
        $inscritos = Inscrito::where('campus_id', $campus_id)->where('modalidade_id', $modalidade_id)->get();
        $modalidade = Modalidade::with('categoria')->find($modalidade_id);
        $qtd_inscritos = count($inscritos);
        if ($qtd_inscritos >= $modalidade->qtd_max){
            $msg_erro = 'Inscrição não realizada. Limite de inscritos para esta modalidade foi excedido.';
            return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
        }

        /* Verifica pela data de nascimento se o estudante pode ser inscrito */
        $aluno = Alunos::find($request->aluno_id);
        if ($aluno->idade < 12){
            $msg_erro = 'Inscrição não realizada. Idade inferior a 12 anos, verifique se data de nascimento está correta.';
            return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
        }
        $tz  = new \DateTimeZone(config('app.timezone', 'America/Recife'));
        $data_nasc = \DateTime::createFromFormat('Y-m-d', $aluno->nascimento, $tz);
        $data_perm = \DateTime::createFromFormat('Y-m-d', $modalidade->categoria->dt_nascimento_limite, $tz);
        if ($data_perm->diff($data_nasc)->invert > 0 && $data_perm->diff($data_nasc)->y > 0){
            $msg_erro = 'Inscrição não realizada. Idade superior a permitida para esta modalidade.';
            return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
        }
        
        /* Cria Arrays de Modalidades / Provas em que o atleta está inscrito*/
        $aluno_modalidades = Inscrito::with('modalidade')->where('aluno_id', $request->aluno_id)->get();
        $array_modalidades_individuais = [];
        $array_modalidades_coletivas = [];
        foreach ($aluno_modalidades as $am){
            if($am->modalidade->tipo == 'Individual'){
                if (!array_key_exists($am->modalidade->modalidade, $array_modalidades_individuais)){
                    $array_modalidades_individuais[$am->modalidade->modalidade]['individuais'] = [];
                    $array_modalidades_individuais[$am->modalidade->modalidade]['coletivas'] = [];
                }
                if ($am->modalidade->tipo_prova == 'Individual'){
                    if (!in_array( $am->modalidade->prova, $array_modalidades_individuais[$am->modalidade->modalidade]['individuais'] )){
                        array_push($array_modalidades_individuais[$am->modalidade->modalidade]['individuais'], $am->modalidade->prova);
                    }
                }else if ($am->modalidade->tipo_prova == 'Coletiva'){
                    if (!in_array( $am->modalidade->prova, $array_modalidades_individuais[$am->modalidade->modalidade]['coletivas'] )){
                        array_push($array_modalidades_individuais[$am->modalidade->modalidade]['coletivas'], $am->modalidade->prova);
                    }
                }
            }else if($am->modalidade->tipo == 'Coletiva'){
                if (!array_key_exists($am->modalidade->modalidade, $array_modalidades_coletivas)){
                    $array_modalidades_coletivas[$am->modalidade->modalidade]['individuais'] = [];
                    $array_modalidades_coletivas[$am->modalidade->modalidade]['coletivas'] = [];
                }
                if ($am->modalidade->tipo_prova == 'Individual'){
                    if (!in_array( $am->modalidade->prova, $array_modalidades_coletivas[$am->modalidade->modalidade]['individuais'] )){
                        array_push($array_modalidades_coletivas[$am->modalidade->modalidade]['individuais'], $am->modalidade->prova);
                    }
                }else if ($am->modalidade->tipo_prova == 'Coletiva'){
                    if (!in_array( $am->modalidade->prova, $array_modalidades_coletivas[$am->modalidade->modalidade]['coletivas'] )){
                        array_push($array_modalidades_coletivas[$am->modalidade->modalidade]['coletivas'], $am->modalidade->prova);
                    }
                }
            }
        }
        /* Verifica limites no .Env */
        $limite_modadlidades_individual = env('MODALIDADE_INDIVIDUAL_MAX', 3);
        $limite_modadlidades_coletiva = env('MODALIDADE_COLETIVA_MAX', 3);
        $limite_provas_individual = env('PROVA_INDIVIDUAL_MAX', 2);
        $limite_provas_coletiva = env('PROVA_COLETIVA_MAX', 3);
        if (count($array_modalidades_individuais) > 0 && $modalidade->tipo == 'Individual'){
            if (count($array_modalidades_individuais) >= $limite_modadlidades_individual && !array_key_exists($modalidade->modalidade, $array_modalidades_individuais)){
                /* Verifica quantidade de modalidades individuais em que o estudante já está inscrito */
                $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em ' . $limite_modadlidades_individual . ' modalidades individuais: ';
                foreach ($array_modalidades_individuais as $k => $v){
                    $msg_erro .= ($k . ', ');
                }
                $msg_erro = substr($msg_erro, 0, -2) . '.';
                return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
            } else if($modalidade->tipo_prova == 'Individual'){
                /* Verifica quantidade de provas individuais desta modalidade em que o estudante já está inscrito */
                if (array_key_exists($modalidade->modalidade, $array_modalidades_individuais)){
                    if (count($array_modalidades_individuais[$modalidade->modalidade]['individuais']) >= $limite_provas_individual){
                        $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em ' . $limite_provas_individual . ' provas individuais desta modalidade: ';
                        foreach ($array_modalidades_individuais[$modalidade->modalidade]['individuais'] as $v){
                            $msg_erro .= ($v . ', ');
                        } 
                        $msg_erro = substr($msg_erro, 0, -2) . '.';
                        return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
                    }
                }
            } else if($modalidade->tipo_prova == 'Coletiva'){
                /* Verifica quantidade de provas individuais desta modalidade em que o estudante já está inscrito */
                if (array_key_exists($modalidade->modalidade, $array_modalidades_individuais)){
                    if (count($array_modalidades_individuais[$modalidade->modalidade]['coletivas']) >= $limite_provas_coletiva){
                        $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em ' . $limite_provas_coletiva . ' provas de revezamento desta modalidade: ';
                        foreach ($array_modalidades_individuais[$modalidade->modalidade]['coletivas'] as $v){
                            $msg_erro .= ($v . ', ');
                        }
                        $msg_erro = substr($msg_erro, 0, -2) . '.';
                        return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
                    }
                }
            }
        }
        if (count($array_modalidades_coletivas) > 0 && $modalidade->tipo == 'Coletiva'){
            if (count($array_modalidades_coletivas) >= $limite_modadlidades_coletiva && !array_key_exists($modalidade->modalidade, $array_modalidades_coletivas)){
                /* Verifica quantidade de modalidades coletivas em que o estudante já está inscrito */
                $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em ' . $limite_modadlidades_coletiva . ' modalidades coletivas: ';
                foreach ($array_modalidades_coletivas as $k => $v){
                    $msg_erro .= ($k . ', ');
                }
                $msg_erro = substr($msg_erro, 0, -2) . '.';
                return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
            } else if($modalidade->tipo_prova == 'Individual'){
                /* Verifica quantidade de provas coletivas desta modalidade em que o estudante já está inscrito */
                if (array_key_exists($modalidade->modalidade, $array_modalidades_coletivas)){
                    if (count($array_modalidades_coletivas[$modalidade->modalidade]['individuais']) >= $limite_provas_individual){
                        $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em ' . $limite_provas_individual . ' provas individuais desta modalidade: ';
                        foreach ($array_modalidades_coletivas[$modalidade->modalidade]['individuais'] as $v){
                            $msg_erro .= ($v . ', ');
                        }
                        $msg_erro = substr($msg_erro, 0, -2) . '.';
                        return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
                    }
                }
            } else if($modalidade->tipo_prova == 'Coletiva'){
                /* Verifica quantidade de provas coletivas desta modalidade em que o estudante já está inscrito */
                if (array_key_exists($modalidade->modalidade, $array_modalidades_coletivas)){
                    if (count($array_modalidades_coletivas[$modalidade->modalidade]['coletivas']) >= $limite_provas_coletiva){
                        $msg_erro = 'O estutande não pode ser adicionado. Pois ele já está inscrito em ' . $limite_provas_coletiva . ' provas de revezamento desta modalidade: ';
                        foreach ($array_modalidades_coletivas[$modalidade->modalidade]['coletivas'] as $v){
                            $msg_erro .= ($v . ', ');
                        }
                        $msg_erro = substr($msg_erro, 0, -2) . '.';
                        return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->withErrors([$msg_erro]);
                    }
                }
            }
        }
        $confirmado = FALSE;
        Inscrito::create([
            'campus_id' => $campus_id,
            'modalidade_id' => $modalidade_id,
            'aluno_id' => $request->aluno_id,
            'confirmado' => $confirmado,
        ]);
        /* Verifica se pode confirmar equipe */
        $inscritos = Inscrito::with('modalidade')
                        ->where('campus_id', $campus_id)
                        ->where('modalidade_id', $modalidade_id)
                        ->get();
        $atleta = $inscritos->first();
        if(count($inscritos) >= $atleta->modalidade->qtd_min && count($inscritos) <= $atleta->modalidade->qtd_max){
            Inscrito::where('campus_id', $campus_id)
                    ->where('modalidade_id', $modalidade_id)
                    ->update(['confirmado' => TRUE]);
        }
        $msg_ok = "Estudande inscrito com sucesso!";
        return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->with('sucesso',$msg_ok);
    }

    public function inscricoes_remover($campus_id, $modalidade_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'inscricao' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        
        $inscrito = Inscrito::find($request->inscricao);

        if(is_null($inscrito)){
            abort(401);
        }

        if(!\Auth::user()->admin && \Auth::user()->campus_id != $inscrito->campus_id){
            abort(403);
        }

        $inscrito->delete();

        /* Verifica se pode confirmar equipe */
        $inscritos = Inscrito::with('modalidade')
                        ->where('campus_id', $campus_id)
                        ->where('modalidade_id', $modalidade_id)
                        ->get();
        $atleta = $inscritos->first();
        if(!is_null($atleta)){
            if(count($inscritos) < $atleta->modalidade->qtd_min || count($inscritos) > $atleta->modalidade->qtd_max){
                Inscrito::where('campus_id', $campus_id)
                        ->where('modalidade_id', $modalidade_id)
                        ->update(['confirmado' => FALSE]);
            }
        }
        $msg_ok = "Estudande removido com sucesso!";
        return redirect()->route('inscricoes.modalidade.v',['campus'=> $campus_id, 'modalidade'=> $modalidade_id])->with('sucesso', $msg_ok);    
    }
    
    public function relacao_campus()
    {
        $campi = Campus::all();
        return view('relacao_campus', compact('campi'));
    }
    public function relacao_campus_pdf(Request $request)
    {
        $validatedData = $request->validate([
            'campus' => 'required',
        ]);
        $campi = Campus::whereIn('id', $request->campus)->get();
        $inscritos = Inscrito::with('aluno')
                                ->whereIn('campus_id', $request->campus)
                                ->where('confirmado', TRUE)
                                ->groupBy('aluno_id')
                                ->groupBy('campus_id')
                                ->get();
        $pdf = \PDF::loadView('pdf.campus', compact('campi','inscritos'));
        return $pdf->stream();
    }

    public function relacao_modalidade()
    {
        return view('index');
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
                $data_limite =  strtotime(env('DT_NASC_IMPORT', '1993-01-01'));
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
