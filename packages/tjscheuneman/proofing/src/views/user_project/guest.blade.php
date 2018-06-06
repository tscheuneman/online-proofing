@extends('proof::layouts.viewer')
@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>
    <div id="app">

        <project-navigation-guest>

        </project-navigation-guest>
        <proof-guest :url="'{{ENV('APP_URL')}}'" :user="'{{Auth::id()}}'" :project="'{{$project->file_path}}'">

        </proof-guest>
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