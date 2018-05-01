@extends('layouts.viewer.app')
@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>
    <div id="app">

        <project-navigation-guest>

        </project-navigation-guest>
        <proof-guest :user="'{{Auth::id()}}'" :project="'{{$project->file_path}}'">

        </proof-guest>
        <pages-left>

        </pages-left>
        <actions>

        </actions>

    </div>
    <script src="{{ asset('js/userProject.js') }}"></script>
@endsection