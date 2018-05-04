@extends('layouts.viewer.app')
@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>
    <div id="app">

        <project-navigation>

        </project-navigation>
        <proof :user="'{{Auth::id()}}'" :project="'{{$project->file_path}}'">

        </proof>
        <pages-left>

        </pages-left>

        <div id="sidebar">
            <comments>

            </comments>

            <revisions :active="true">

            </revisions>

        </div>



    </div>
    <script src="{{ asset('js/userProject.js') }}"></script>
@endsection