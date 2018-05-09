@extends('adminlte::page')

@section('css')
<link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/web-fonts-with-css/css/fontawesome-all.min.css') }}">
<link rel="stylesheet" href="{{ asset('vendor/select2-4.0.5/select2.min.css')}}">
@stop

@section('content_header')
    <h1>Inscrições</h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-fw fa-home"></i> Inicial</a></li>
        <li><a href="{{ route('inscricoes') }}"><i class="fa fa-fw fa-user-plus"></i> Inscrições</a></li>
    </ol>
@stop

@section('content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Escolha uma modalidade:</h3>
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
        <form action="{{ route('inscricoes.modalidade') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <label for="campus">Campus: </label>
                    <select name="campus" id="campus" class="form-control">
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
                    <label for="modalidade">Modalidade: </label>
                    <select name="modalidade" id="modalidade" class="form-control">
                        <option></option>
                        @foreach($modalidades as $modalidade)
                            <option value="{{ $modalidade->id }}">
                                {{ $modalidade->categoria->categoria}} - {{ $modalidade->modalidade}} 
                                @if($modalidade->prova != '') 
                                    ({{ $modalidade->prova}})
                                @endif
                                - {{ $modalidade->sexo}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-fw fa-arrow-alt-circle-right "></i> Próximo</button>
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
        $('#modalidade').select2({
            placeholder: "Selecione uma modalidade:"
        });
    });
    </script>
@stop