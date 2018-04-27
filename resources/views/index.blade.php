@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/web-fonts-with-css/css/fontawesome-all.min.css') }}">
    <style>
       
        .full-height {
            height: 80vh;
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
    </style>
@stop

@section('content')
    <div class="flex-center position-ref full-height">
        <div class="content">
            <img src="{{ asset('img/medalha-intercampi-2018.png') }}" alt="">
            <br><br>
            <div class="container">
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
            </div>
            
        </div>
    </div>
@stop