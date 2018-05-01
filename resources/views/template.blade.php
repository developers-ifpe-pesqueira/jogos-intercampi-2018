@extends('adminlte::master')
@section('adminlte_css')
    @yield('css')
@stop


@section('body')
    <!-- Main content -->
    <nav>
        @yield('navbar')
    </nav>
    <section class="content">

        @yield('content')

    </section>
@stop
@section('adminlte_js')
    @yield('js')
@stop