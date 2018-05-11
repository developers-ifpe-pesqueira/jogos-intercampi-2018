@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/select2-4.0.5/select2.min.css')}}">
@stop

@section('content_header')
    <h1>Inscrições (Campus {{ $campus->campus }})</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-fw fa-home"></i> Inicial</a></li>
        <li><a href="{{ route('inscricoes') }}"><i class="fa fa-fw fa-user-plus"></i> Inscrições</a></li>
    </ol>
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ $modalidade->nome }}:</h3>
        <div class="pull-right">
            <a href="{{ route('inscricoes') }}" class="btn btn-primary"><i class="fa fa-fw fa-retweet"></i>&nbsp; Alterar modalidade</a>
        </div>
    </div>
    <div class="box-body">
         @if (session('sucesso'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Sucesso!</h4>
                <p>{{ session('sucesso') }}</p> 
            </div>
        @endif
        @if(!empty($erro))
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Atenção:</h4>
                <p>{{ $erro }}</p> 
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Atenção:</h4>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach 
            </div>
        @endif
        <form action="{{ route('inscricoes.adicionar',['campus'=> $campus->id, 'modalidade' => $modalidade->id ]) }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <label for="aluno">
                        Estudante:
                    </label>
                    {{-- <input type="hidden" name="campus_id" value="{{ $campus->id }}">
                    <input type="hidden" name="modalidade_id" value="{{ $modalidade->id }}"> --}}
                    <select name="aluno_id" id="aluno" class="form-control" aria-describedby="alunosAjuda">
                        <option></option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}">
                                {{ $aluno->matricula}} - {{ $aluno->nome}} ({{ $aluno->idade}} anos)
                            </option>
                        @endforeach
                    </select>
                    <small id="alunosAjuda" class="form-text text-muted">
                        <br>
                        Quantidade mínima: {{ $modalidade->qtd_min }}<br>
                        Quantidade máxima: {{ $modalidade->qtd_max }}<br>
                        Estudantes matriculados no campus: <br>
                        <ul>
                            <li>nascidos a partir de {{ date('d/m/Y', strtotime($modalidade->categoria->dt_nascimento_limite)) }};</li>
                            @if ($modalidade->sexo_abrev != 'U')
                            <li>do sexo {{ $modalidade->sexo }};</li>
                            @endif
                        </ul>
                        <b>Disponível: {{ $alunos->count() }}</b>
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-fw fa-plus"></i> Adicionar</button>
                </div>                    
            </div>
        </form>
        @if(count($inscritos) > 0)
            <h3 class="box-title">Atletas inscritos:</h3>
            <table class="table table-boordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Matrícula</th>
                        <th>Nome</th>
                        <th>Nascimento</th>
                        <th>Sexo</th>
                        <th>Turma</th>
                        <th>Mãe</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @php $cont = 1 @endphp
                    @foreach($inscritos as $inscrito)
                    <tr>
                        <td>{{ $cont++ }}</td>
                        <td>{{ $inscrito->aluno->matricula }}</td>
                        <td>{{ $inscrito->aluno->nome }}</td>
                        <td>{{ date('d/m/Y', strtotime($inscrito->aluno->nascimento)) }}</td>
                        <td>{{ $inscrito->aluno->sexo }}</td>
                        <td>{{ $inscrito->aluno->turma }}</td>
                        <td>{{ $inscrito->aluno->nome_mae }}</td>
                        <td>
                            <form method="post" action="{{ route('inscricoes.remover',['campus'=> $campus->id, 'modalidade' => $modalidade->id ]) }}" style="display:inline;" class="form-delete">
                                {{ csrf_field() }}
                                {{ method_field('DELETE')}}
                                <input type="hidden" name="inscricao" value="{{ $inscrito->id }}">
                                <button type="submit" class="btn btn-danger btn-xs">
                                        <i class="fa fa-fw fa-close"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-12 col-xs-12">
            
            @if(!$inscritos->first()->confirmado)
                <p class="text-yellow">*As inscrições acima não estão confirmadas. Quantidade mímina de atletas não atingido para esta modalidade.</p>
            @endif
                </div>
            </div>
        @endif
    </div>
    <div class="overlay" style="display:none;" id="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="callout callout-info">
            <h4>Observação:</h4>
            <p>A informações dos estudantes foram obtidas pelo sistema Q-Acadêmico, caso exista alguma divergência, favor regularizar junto ao setor responsável pelo registro escolar do seu Campus.</p>
            <p>Para incluir ou modificar estudantes na plataforma de inscrições do Intercampi, enviar documentação comprobatório de matrícula e um documento oficial do estudante para o e-mail <a href="mailto:intercampi@pesqueira.ifpe.edu.br">intercampi@pesqueira.ifpe.edu.br</a></p>
        </div>
    </div>
</div>
@stop

@section('js')
    <script src="{{ asset('vendor/select2-4.0.5/select2.min.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('#aluno').select2({
            placeholder: "Selecione um(a) aluno(a):"
        });
    });
    window.onload = function() {
        $("#form-importar").submit(function(event) {
            $('#loading').css('display','block');
        });
    }
    @if (session('sucesso'))
        $('.alert-dismissible').delay(10000).slideUp(500);
    @endif
    @if(!empty($erro))
        $('.alert-dismissible').delay(10000).slideUp(500);
    @endif
    @if ($errors->any())
        $('.alert-dismissible').delay(10000).slideUp(500);
    @endif
    </script>
@stop