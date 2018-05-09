@extends('adminlte::page')

@section('content_header')
    <h1>Informações<small>{{ config('app.name') }}</small></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <div class="small-box" style="background-color:#EEF5E2 !important; color:black;">
            <div class="inner">
              <h3>IFPE</h3>

                <p><i>Campus</i> {{ $campus->campus }}</p>
            </div>
            <div class="icon">
                <img src="{{ asset(config('app.logo')) }}" style="height:1em">
            </div>
            <a class="small-box-footer" style="background-color:transparent !important;">&nbsp;</a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>{{ $qtd_inscritos }}</h3>

              <p>Quantidade de inscritos</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-users"></i>
            </div>
            <a class="small-box-footer">&nbsp;</a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-green">
            <div class="inner">
              <h3>&nbsp;</h3>

              <p>Modalidades confirmadas</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-check-square-o"></i>
            </div>
            <a class="small-box-footer">&nbsp;</a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>&nbsp;</h3>

              <p>Modalidades pendentes</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-warning"></i>
            </div>
            <a class="small-box-footer">&nbsp;</a>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Modalidades</h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th rowspan="2" style="text-align:center; vertical-align:middle;">MODALIDADE</th>
                                <th colspan="3" style="text-align:center; vertical-align:middle;">QUANTIDADE DE INSCRITOS</th>
                            </tr>
                            <tr>
                                <th style="text-align:center; vertical-align:middle;">Masculino</th>
                                <th style="text-align:center; vertical-align:middle;">Feminino</th>
                                <th style="text-align:center; vertical-align:middle;">Único</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($modalidades_inscritos as $mi)
                            <tr>
                                <td style="text-align:left; vertical-align:middle;">{{ $mi->modalidade }}</td>
                                <td style="text-align:center; vertical-align:middle;">{{ $mi->masc or '-' }}</td>
                                <td style="text-align:center; vertical-align:middle;">{{ $mi->fem or '-' }}</td>
                                <td style="text-align:center; vertical-align:middle;">{{ $mi->unic or '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop