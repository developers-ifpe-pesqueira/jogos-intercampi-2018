@extends('template')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/web-fonts-with-css/css/fontawesome-all.min.css') }}">
    <style>
       *{
           margin: 0;
           padding: 0;
       }
    
        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        img {
            height: 50vh;
        }
        nav{
            background-color:#57760C;
            height: 3em;
            line-height: 3em;
        }
        nav a{
            color: white;
        }
        a{
            background-color:#57760C !important;
        }
        body {
            background-color: #EFF4E8;
        }

        @media (min-width: 992px){
            .full-height {
                height: 90vh;
            }
        }
        @media (max-width: 991px){
            .btn{
                margin-top: 5px;
            }
        }
    </style>
@stop

@section('navbar')
    &nbsp;
    <div class="pull-right" style="text-align: right; min-width: 15em; margin-right: 1em;">
        @auth
            <a href="{{ route('home') }}" target="_self"><i class="fa fa-fw fa-unlock-alt"></i> Área restrita</a>&nbsp;&nbsp;
        @else
            <a href="{{ route('login') }}" target="_self"><i class="fa fa-fw fa-lock"></i> Área restrita</a>&nbsp;&nbsp;
        @endauth
    </div>
@stop
@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <img src="{{ asset('img/medalha-intercampi-2018.png') }}" alt="">
            <br><br>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-xs-12">
                        <a href="{{ asset('arquivos/Regulamento Geral Intercampi 2018.pdf') }}" class="btn btn-lg btn-block btn-success" target="_blank"><i class="fa fa-fw fa-book"></i> Regulamento Geral</a>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <a href="{{ asset('arquivos/Regulamento Especifico Intercampi 2018.pdf') }}" class="btn btn-lg btn-block btn-success" target="_blank"><i class="fa fa-fw fa-file-alt"></i> Regulamento Específico</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <a href="" class="btn btn-lg btn-block btn-success" disabled><i class="fa fa-fw fa-table-tennis"></i> Modalidades</a>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <a href="" class="btn btn-lg btn-block btn-success" disabled><i class="fa fa-fw fa-calendar"></i> Programação</a>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <a href="" class="btn btn-lg btn-block btn-success" disabled><i class="fa fa-fw fa-table"></i> Tabela</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    {{-- <div class="col-md-12 col-xs-12">
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-info"></i> Encontro pós-inscrição:</h4>
                            <p>CONGRESSO TÉCNICO</p>
                            <p>DATA: <b>22/05/2018</b></p>
                            <p>HORÁRIO: <b>9h</b></p>
                            <p>LOCAL: <b>Campus Caruaru</b></p>
                        </div>
                    </div> --}}
                    <div class="col-md-3 col-xs-12">
                        <div class="alert alert-info alert-dismissible" style="min-height: 12em;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-info"></i> Counter-Strike (CS:GO):</h4>
                            <p>Mínimo e máximo de inscritos por Campus - 5 atletas</p>
                            <p>O campeonato poderá ter no minimo e no máximo 8 equipes</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="alert alert-info alert-dismissible" style="min-height: 12em;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-info"></i> League of Legends (LoL):</h4>
                            <p>Mínimo e máximo de inscritos por Campus - 5 atletas</p>
                            <p>O campeonato poderá ter no mínimo e no máximo 8 equipes</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="alert alert-info alert-dismissible" style="min-height: 12em;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-info"></i> FIFA 18:</h4>
                            <p>Mínimo e máximo de inscritos por Campus - 2 atletas </p>
                            <p>O Campeonato poderá ter inscrição dos 16 Campi do IFPE</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <div class="alert alert-info alert-dismissible" style="min-height: 12em;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-info"></i> Just Dance:</h4>
                            <p>O <b>Just Dance</b> terá como finalidade a prática do lazer, por isso não vimos a necessidade de inscrições. </p>
                        </div>
                    </div>
                    <p>Obs.: O regulamento específico de cada jogo será divulgado posteriormente.</p>
                </div>
            </div>
        </div>
    </div>
@stop