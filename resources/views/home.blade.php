@extends('adminlte::page')

@section('content_header')
    <h1>Informações<small>{{ config('app.name') }}</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('home') }}"><i class="fa fa-fw fa-home"></i> Inicial</a></li>
    </ol>
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
              <h3>{{ $modalidades_confirmadas }}</h3>

              <p>Modalidades confirmadas</p>
            </div>
            <div class="icon">
              <i class="fa fa-fw fa-check-square"></i>
            </div>
            <a class="small-box-footer">&nbsp;</a>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{ $modalidades_totais - $modalidades_confirmadas }}</h3>

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
                                <td style="text-align:center; vertical-align:middle;">
                                    @if(is_null($mi->masc))
                                        -
                                    @elseif($mi->masc >= $mi->masc_min)
                                        <a href="{{ route('inscricoes.modalidade.v',['campus'=> $campus->id, 'modalidade'=> $mi->masc_id]) }}" class="badge bg-green">{{ $mi->masc }}</a>
                                    @else
                                        <a href="{{ route('inscricoes.modalidade.v',['campus'=> $campus->id, 'modalidade'=> $mi->masc_id]) }}" class="badge bg-yellow">{{ $mi->masc }}</a>
                                    @endif
                                </td>
                                <td style="text-align:center; vertical-align:middle;">
                                    @if(is_null($mi->fem))
                                        -
                                    @elseif($mi->fem >= $mi->fem_min)
                                        <a href="{{ route('inscricoes.modalidade.v',['campus'=> $campus->id, 'modalidade'=> $mi->fem_id]) }}" class="badge bg-green">{{ $mi->fem }}</a>
                                    @else
                                        <a href="{{ route('inscricoes.modalidade.v',['campus'=> $campus->id, 'modalidade'=> $mi->fem_id]) }}" class="badge bg-yellow">{{ $mi->fem }}</a>
                                    @endif
                                </td>
                                <td style="text-align:center; vertical-align:middle;">
                                    @if(is_null($mi->unic))
                                        -
                                    @elseif($mi->unic >= $mi->unic_min)
                                        <a href="{{ route('inscricoes.modalidade.v',['campus'=> $campus->id, 'modalidade'=> $mi->unic_id]) }}" class="badge bg-green">{{ $mi->unic }}</a>
                                    @else
                                        <a href="{{ route('inscricoes.modalidade.v',['campus'=> $campus->id, 'modalidade'=> $mi->unic_id]) }}" class="badge bg-yellow">{{ $mi->unic }}</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop