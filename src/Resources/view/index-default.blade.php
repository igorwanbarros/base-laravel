@extends('app')
@section('content')

    @yield('after-actions')

    @section('actions')
    <div class="row">
        <div class="col-sm-12 text-right base-laravel__actions">
            <a href="{!! url($urlBase . '/novo') !!}"
               class="btn btn-default">
                <i class="fa fa-plus-circle"></i>
                <span class="hidden-xs">Novo</span>
            </a>
        </div>
    </div>
    @show

    @yield('after-widget')

    {!! $widget !!}

    @yield('after-widget')

@stop
