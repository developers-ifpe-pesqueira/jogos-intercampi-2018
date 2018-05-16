@extends('pdf.template')

@section('titulo', 'Relação de Inscritos por Campus/Modalidade')

@section('corpo')
    @php $primeira = TRUE; @endphp
    @foreach($campi as $campus)
        @if($primeira)
            @php $primeira = FALSE; @endphp
        @else
            <div class="page-break"></div>
        @endif
        <header>
            <img src="img/medalha-intercampi-2018.png" style="height: 2cm;">
            <p>Instituto Federal de Educação, Ciência e Tecnologia de Pernambuco</p>
            <p>Jogos Intercampi IFPE 2018</p>
        </header>
        <section>
            <h1>Relação de inscritos por Campus/Modalidade</h1>
            <h2><i>Campus</i>  {{ $campus->campus }}</h2>
            @if(count($inscritos->where('campus_id', $campus->id)) > 0 )
                @php $primeira_modalidade = TRUE; @endphp
                @foreach($inscritos_modalidade->where('campus_id', $campus->id) as $im)
                    @if($primeira_modalidade)
                        @php $primeira_modalidade = FALSE; @endphp
                    @else
                        <hr><br>
                    @endif
                    <h4>{{ $im->modalidade->nome }}</h4>
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Matrícula</th>
                                <th>Nome</th>
                                <th>Data de <br>nascimento</th>
                            </tr>
                        </thead>
                        @php
                            $cont = 1;
                        @endphp
                        <tbody>
                            @foreach($inscritos->where('campus_id', $campus->id)->where('modalidade_id', $im->modalidade_id)->sortBy('aluno.nome_ansi') as $inscrito)
                                <tr>
                                    <td class='centralizar'>{{ $cont++ }}</td>
                                    <td class='centralizar'>{{ $inscrito->aluno->matricula }}</td>
                                    <td>{{ $inscrito->aluno->nome }}</td>
                                    <td class='centralizar'>{{ date('d/m/Y', strtotime($inscrito->aluno->nascimento)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>Quantidade total de inscritos nesta modalidade: <b>{{ --$cont }}</b></p>
                @endforeach
            @else
                <h3 class="erro"><b>Obs.:</b> Não há inscritos neste <i>Campus</i></h3>
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