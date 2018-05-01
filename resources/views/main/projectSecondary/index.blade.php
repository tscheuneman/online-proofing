@extends('layouts.viewer.app')
@section('content')
    <div id="app">

        <project-navigation>

        </project-navigation>
        <proof :user="'{{Auth::id()}}'" :project="'{{$project->file_path}}'">

        </proof>
        <pages-left>

        </pages-left>
        <actions>

        </actions>

    </div>
    <script src="{{ asset('js/userProject.js') }}"></script>
@endsection