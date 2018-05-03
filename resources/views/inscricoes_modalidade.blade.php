@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/web-fonts-with-css/css/fontawesome-all.min.css') }}">
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
        @php
            $str_modalidade = "";
            $str_modalidade .= $modalidade->modalidade;
            if ($modalidade->prova != ''){
                $str_modalidade .= ' - ';
                $str_modalidade .= $modalidade->prova;
            }
            $str_modalidade .= ' (';
            $str_modalidade .= $modalidade->sexo;
            $str_modalidade .= ')';
        @endphp
        <h3 class="box-title">{{ $str_modalidade }}:</h3>
    </div>
    <div class="box-body">
        @if(!empty($sucesso))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Sucesso!</h4>
                <p>{{ $sucesso }}</p> 
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
        <form action="{{ route('inscricoes.adicionar') }}" method="POST">
            {{ csrf_field() }}
            {{-- <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <label for="campus">Campus: </label>
                    <input type="text" name="campus" value="Campus {{ $campus->campus }}" class="form-control" disabled>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <label for="modalidade">Modalidade: </label>
                    <input type="text" name="modalidade" value="{{ $str_modalidade }}" class="form-control" disabled>
                </div>
            </div> --}}
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <label for="aluno">
                        Estudante:
                    </label>
                    <input type="hidden" name="campus_id" value="{{ $campus->id }}">
                    <input type="hidden" name="modalidade_id" value="{{ $modalidade->id }}">
                    <select name="aluno_id" id="aluno" class="form-control" aria-describedby="alunosAjuda">
                        <option></option>
                        @foreach($alunos as $aluno)
                            <option value="{{ $aluno->id }}">
                                {{ $aluno->matricula}} - {{ $aluno->nome}}
                            </option>
                        @endforeach
                    </select>
                    <small id="alunosAjuda" class="form-text text-muted">
                        <br><br>
                        Estudantes matriculados no campus: <br>
                        <ul>
                            <li>nascidos a partir de 01/01/1999 (19 anos);</li>
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
                        <th>Matrícula</th>
                        <th>Nome</th>
                        <th>Nascimento</th>
                        <th>Sexo</th>
                        <th>Turma</th>
                        <th>Mãe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inscritos as $inscrito)
                    <tr>
                        <td>{{ $inscrito->aluno->matricula }}</td>
                        <td>{{ $inscrito->aluno->nome }}</td>
                        <td>{{ date('d/m/Y', strtotime($inscrito->aluno->nascimento)) }}</td>
                        <td>{{ $inscrito->aluno->sexo }}</td>
                        <td>{{ $inscrito->aluno->turma }}</td>
                        <td>{{ $inscrito->aluno->nome_mae }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    <div class="overlay" style="display:none;" id="loading">
        <i class="fa fa-refresh fa-spin"></i>
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
    </script>
@stop