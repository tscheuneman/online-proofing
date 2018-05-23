@extends('proof::layouts.viewer')
@section('content')
    <div id="loader">
        <div class="loader">

        </div>
    </div>
    <div id="app">


        <project-navigation-admin>

        </project-navigation-admin>
        <proof-guest :user="'{{Auth::id()}}'" :project="'{{$project->file_path}}'">

        </proof-guest>
        <pages-left>

        </pages-left>

        <div id="sidebar">
            <comments-admin>

            </comments-admin>

            <revisions-admin :active="true">

            </revisions-admin>

        </div>
        <messaging>

        </messaging>
        <logs>

        </logs>

    </div>
    <script src="{{ asset('js/adminProject.js') }}"></script>
@endsection