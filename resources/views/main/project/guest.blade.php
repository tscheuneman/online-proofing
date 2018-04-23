@extends('layouts.app')

@section('content')
    <div class="container">
        @include('main.project.includes.status')
        <div class="row justify-content-center">
            <div class="col-md-6">
                @include('main.project.includes.info')
            </div>
            <div class="col-md-6">
                @include('main.project.includes.revisions')
            </div>
        </div>
        <br><br>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @include('main.project.includes.main')
            </div>
            <div class="col-md-4">
                @include('admin.projectActions.includes.comments')
            </div>
        </div>

        @include('layouts/includes/scripts/viewProjScript')
        <script>
            $(document).ready(function() {
                $('#showProd').on('click', function() {
                    $('.revisions').slideToggle(500);
                });
                $('#showInfo').on('click', function() {
                    $('.project_info').slideToggle(500);
                });

            });
        </script>
@endsection
