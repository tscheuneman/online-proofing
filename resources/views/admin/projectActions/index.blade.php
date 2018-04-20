@extends('layouts.admin.app')

@section('title', $project->project_name)

@section('content')
    @if(Session::has('flash_deleted'))
        <div class="alert alert-warning"><span class="glyphicon glyphicon-remove-circle"></span><em> {!! session('flash_deleted') !!}</em></div>
    @endif
    @if(Session::has('flash_created'))
        <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! session('flash_created') !!}</em></div>
    @endif

    @include('admin.projectActions.includes.status')
    <br>
    <div class="row justify-content-center">
        <div class="col-md-6">
            @include('admin.projectActions.includes.info')
        </div>
        <div class="col-md-6">
            @include('admin.projectActions.includes.revisions')
        </div>
    </div>
                <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('admin.projectActions.includes.main')
        </div>
        <div class="col-md-4">
            @include('admin.projectActions.includes.comments')
        </div>
    </div>
    <br>
    <br>
<script src="{{ asset('js/project.js') }}"></script>
@include('layouts/includes/scripts/viewProjScript')
   <script>
      $(document).ready(function() {
          $('.pageComment').on('click', function() {
              let thisVal = $(this).data('num');
              goToElementFromPageComment(thisVal);
          });
          $('.getLink').on('click', function() {
            let value = $(this).data('addy');
            let proj = $(this).data('proj');
            getLinkValue(value, proj);
          });
          $('#showProd').on('click', function() {
              $('.revisions').slideToggle(500);
          });
          $('#showInfo').on('click', function() {
              $('.project_info').slideToggle(500);
          });
      });
   </script>
@endsection