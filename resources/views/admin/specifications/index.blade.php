@extends('layouts.admin.app')

@section('title', 'Specifications')

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif
    <h2>
        Specifications
    </h2>
    <a class="btn btn-main" href="/admin/specifications/schema/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Specification Schema</a>
    <a class="btn btn-primary" href="/admin/specifications/create"><i class="fa fa-plus" aria-hidden="true"></i> Create Specification</a>
    <br class="clear" />
    <br />
    <div class="row">
        <div class="col-md-6">
            <h3>Specifications</h3>
            <table class="table">
                <thead>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        Type
                    </td>
                    <td>
                        Default
                    </td>
                </tr>
                </thead>
                <tbody>
                    @foreach($specs as $spec)
                        <tr>
                            <td>
                                {{$spec->name}}
                            </td>
                            <td>
                                {{$spec->type}}
                            </td>
                            <td>
                                {{$spec->default}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3>Schemas</h3>
            <table class="table">
                <thead>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        Number of fields
                    </td>
                </tr>
                </thead>
                <tbody>
                @foreach($schemas as $schema)
                    <tr>
                        <td>
                            {{$schema->name}}
                        </td>
                        <td>
                            {{count($schema->specs)}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection