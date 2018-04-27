@extends('layouts.viewer.app')
@section('content')
    <div id="app">
        <proof :user="'{{Auth::id()}}'" :project="'{{$project->file_path}}'">

        </proof>
        <actions>

        </actions>
    </div>
    <script src="{{ asset('js/userProject.js') }}"></script>
@endsection