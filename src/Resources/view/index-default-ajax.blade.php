@extends('app-ajax')
@section('content')

    @yield('before-widget')

    {!! $widget !!}

    @yield('after-widget')

@stop
