@extends('adminlte::master')
@section('adminlte_css')
    @yield('css')
@stop


@section('body')
    <!-- Main content -->
    <nav>
        @yield('navbar')
    </nav>
    <section>

        @yield('content')

    </section>
@stop
@section('adminlte_js')
    @yield('js')
@stop