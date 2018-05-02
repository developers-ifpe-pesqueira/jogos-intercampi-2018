@extends('adminlte::page')

@section('content_header')
    <h1>Importar Alunos</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-fw fa-home"></i> Inicial</a></li>
        <li><a href="{{ route('importar') }}"><i class="fa fa-fw fa-download"></i> Importar</a></li>
    </ol>
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Importar alunos a partir do Q-Acadêmico</h3>
    </div>
    <div class="box-body">
        @if(!empty($sucesso))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> Sucesso!</h4>
                <p>{{ $sucesso }}</p> 
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Atenção</h4>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach 
            </div>
        @endif
        <form action="{{ route('importar') }}" method="POST" enctype="multipart/form-data" id="form-importar">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <input type="file" name="arquivo" id="arquivo" accept=".csv" aria-describedby="arquivoAjuda">
                    <small id="arquivoAjuda" class="form-text text-muted">
                        <br>
                        O arquivo deve ser extraído do Q-Acadêmico (Acesso remoto) apenas
                        com os alunos matriculados e no formato CSV utilizando o 
                        caractere vírgula "," como separador.<br>
                        Deve conter no mínimo os dados abaixo:<br>
                        <ul>
                            <li>Matrícula</li>
                            <li>Nome</li>
                            <li>Nascimento</li>
                            <li>Sexo</li>
                            <li>CPF</li>
                            <li>Instituição</li>
                            <li>Nome do Pai</li>
                            <li>Nome da Mãe</li>
                            <li>Nível/Regime de Ensino</li>
                        </ul>
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block"> <i class="fa fa-fw fa-download"></i> Importar</button>
                </div>                    
            </div>
        </form>
    </div>
    <div class="overlay" style="display:none;" id="loading">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
</div>
@stop

@section('js')
<script>
    window.onload = function() {
        $("#form-importar").submit(function(event) {
            $('#loading').css('display','block');
        });
    }
</script>
@stop