@extends('pdf.template')

@section('titulo', 'Relação de Inscritos por Modalidade')

@section('corpo')
    @php $primeira = TRUE; @endphp
    @foreach($modalidades as $modalidade)
        @if($primeira)
            @php $primeira = FALSE; @endphp
        @else
            <div class="page-break"></div>
        @endif
        <header>
            <img src="img/medalha-intercampi-2018.png">
            <p>Instituto Federal de Educação, Ciência e Tecnologia de Pernambuco</p>
            <p>Jogos Intercampi IFPE 2018</p>
        </header>
        <section>
            <h1>Relação de inscritos por Modalidade</h1>
            <h2> {{ $modalidade->nome }}</h2>
            @if(count($inscritos->where('modalidade_id', $modalidade->id)) > 0 )
                @php $primeira_modalidade = TRUE; $campus_old = ""; @endphp
                <table>
                    <thead>
                        <tr>
                            <th style="width: 30%">Campus</th>
                            <th style="width: 15%">Matrícula</th>
                            <th style="width: 40%">Nome</th>
                            <th style="width: 15%">Data de <br>nascimento</th>
                        </tr>
                    </thead>
                    @php
                        $cont = 1;
                        $cont_campi = 0;
                    @endphp
                    <tbody>
                        @foreach($inscritos->where('modalidade_id', $modalidade->id) as $i)
                            <tr>
                                @if($i->campus_id != $campus_old)
                                    @php 
                                        /* $qtd_campus = count($inscritos->where('modalidade_id', $modalidade->id)->where('campus_id', $i->campus_id));  */
                                        $cont_campi++; 
                                    @endphp
                                    {{-- <td rowspan="{{ $qtd_campus }}">{{ $i->campus->campus }} </td> --}}
                                @endif
                                <td>{{ $i->campus->campus }} </td>
                                <td class='centralizar'> {{ $i->aluno->matricula }} </td>
                                <td> {{ $i->aluno->nome }} </td>
                                <td class='centralizar'> {{ date('d/m/Y', strtotime($i->aluno->nascimento)) }} </td>
                            </tr>
                            @php 
                                $campus_old = $i->campus_id; 
                                $cont++;
                            @endphp
                         @endforeach
                    </tbody>
                </table>
                <p>Quantidade de <i>campi</i> inscritos nesta modalidade: <b>{{ $cont_campi }}</b></p>
                <p>Quantidade de inscritos nesta modalidade: <b>{{ --$cont }}</b></p>
            @else
                <h3 class="erro"><b>Obs.:</b> Não há inscritos nesta modalidade.</h3>
            @endif
        </section>
        <footer>
            <div class="esquerda">
                <span>Relação gerada em {{ date('d/m/Y h:m:i') }}</span>
            </div>
            <div class="direita">
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </div>
        </footer>
    @endforeach
@stop