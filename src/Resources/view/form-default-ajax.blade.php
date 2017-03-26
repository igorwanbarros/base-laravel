@extends('app-ajax')
@section('content')

    @yield('after-widget')

    {!! $widget !!}

    @yield('after-widget')

@stop
