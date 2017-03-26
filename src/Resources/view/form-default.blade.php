@extends('app')
@section('content')

    @yield('after-actions-top')

    @section('actions-top')
    <div class="row">
        <div class="col-sm-12 text-right base-laravel__actions">
            <a href="{!! url($urlBase . '/novo') !!}"
               class="btn btn-default">
                <i class="fa fa-plus-circle"></i>
                <span class="hidden-xs">Novo</span>
            </a>
            @if ($model->id)
            <a href="{!! url($urlBase . '/excluir/' . $model->id) !!}" class="btn btn-danger">
                <i class="fa fa-trash-o"></i>
                <span class="hidden-xs">Excluir</span>
            </a>
            @else
            <a href="#" class="btn btn-danger disabled">
                <i class="fa fa-trash-o"></i>
                <span class="hidden-xs">Excluir</span>
            </a>
            @endif
        </div>
    </div>
    @show

    @yield('before-widget')

    {!! $widget !!}

    @yield('after-widget')

    @yield('after-actions-bottom')

    @section('actions-bottom')
    <div class="row">
        <div class="col-sm-12 text-right">
            <a href="{!! url($urlBase) !!}" class="btn btn-default">
                <i class="fa fa-chevron-circle-left"></i>
                <span class="hidden-xs">Voltar</span>
            </a>
        </div>
    </div>
    @show
@stop
