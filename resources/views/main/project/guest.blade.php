@extends('layouts.app')

@section('content')
    <div class="container">
        @include('main.project.includes.status')
        <div class="row justify-content-center">
            <div class="col-md-3">
                @include('main.project.includes.info')
                @include('main.project.includes.revisions')
            </div>

            <div class="col-md-9">
                @include('main.project.includes.mainGuest')
            </div>
        </div>

        @include('layouts/includes/scripts/viewProjScript')
@endsection
