@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/select2-4.0.5/select2.min.css')}}">
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
        border-color: #367fa9;
        padding: 1px 10px;
        color: #fff;
    }
</style>
@stop

@section('content_header')
    <h1>Relação de inscritos</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-fw fa-home"></i> Inicial</a></li>
        <li><a><i class="fa fa-fw fa-list-alt"></i> Relação de inscritos</a></li>
        <li><a href="{{ route('relacao.campus') }}"><i class="fa fa-fw fa-graduation-cap"></i> por Campus</a></li>
    </ol>
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Relação de inscritos por Campus</h3>
    </div>
    <div class="box-body">
        @if ($errors->any())
            <div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-warning"></i> Atenção</h4>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach 
            </div>
        @endif
        <form action="{{ route('relacao.campus') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <label for="campus">
                        Campus: 
                        (<a id="selecionar">Selecionar todos</a> / <a id="deselecionar">Limpar seleção</a>)
                    </label>
                    <select class="form-control" name="campus[]" id="campus" multiple="multiple">
                        @foreach($campi as $campus)
                            <option value="{{ $campus->id }}">
                                {{ $campus->campus}} 
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-danger btn-block"><i class="fa fa-fw fa-file-pdf"></i> Gerar Relatório</button>
                </div>                    
            </div>
        </form>
    </div>
</div>
@stop
@section('js')
    <script src="{{ asset('vendor/select2-4.0.5/select2.min.js')}}"></script>
    <script>
    $(document).ready(function() {
        $('#campus').select2();
       $('#selecionar').click(function(){
           $('#campus option').attr('selected','selected');
           $('#campus').select2();
       }); 
       $('#deselecionar').click(function(){
           $('#campus option').removeAttr('selected');
           $('#campus').select2();
       }); 
    });
    </script>
@stop