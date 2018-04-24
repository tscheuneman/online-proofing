@extends('layouts.app')

@section('content')

<div class="container">
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    @if(isset($orders[0]))
            <div class="row justify-content-center">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-hourglass-end" aria-hidden="true"></i>
                            Attention Required
                        </div>
                        <div class="card-body">
                            @include('main.index.includes.attentionRequired')
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            Active Projects
                        </div>
                        <div class="card-body">
                            @include('main.index.includes.activeProjects')
                        </div>
                    </div>
                </div>
            </div>
    @else
        <h2 class="titleHeading">No Active Projects</h2>
    @endif

</div>
@endsection
