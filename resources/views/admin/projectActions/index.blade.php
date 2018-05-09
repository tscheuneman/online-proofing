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

    <div id="messages">
        <div class="messageOverallContainer">
            <div class="message_controls">
                <div class="closeMessage"><i class="fa fa-times" aria-hidden="true"></i></div>
                <h1>Message Center</h1>
                <hr>
                <button id="messageBackButton" data-proj="{{$project->file_path}}" class="btn btn-secondary"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back</button>
                <button id="createThreadLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-submission"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create Thread</button>
                <button id="createThreadMessage" onClick="showCreateMessage(this)" data-thread="" class="btn btn-submission"><i class="fa fa-plus-circle" aria-hidden="true"></i> New Message</button>
                <input type="hidden" id="this_user" value="{{Auth::id()}}">
                <div class="messageHolder">

                </div>
                <div class="dropdown-menu" aria-labelledby="createThreadLabel">
                    <div class="px-3 py-3">
                        <div class="form-group">
                            <label for="threadName">Thread Name</label>
                            <input type="text" class="form-control" id="threadName" placeholder="Things">
                        </div>
                        <div class="dropdown-divider"></div>
                        <button data-proj="{{$project->file_path}}" id="addThread"  type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
                <hr>
            </div>
            @include('admin.projectActions.includes.messages')
        </div>

    </div>
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
    <div class="row justify-content-center">
        <div class="col-md-12">
            @include('admin.projectActions.includes.logs')
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
          $('#showLogs').on('click', function() {
              $('.project_logs').slideToggle(500);
          });
          $('.closeMessage').on('click', function() {
            messageToggle();
          });
          $('#showMessages').on('click', function() {
            messageToggle();
          });

          $('#addThread').on('click', function() {
              let project_data = $(this).data('proj');
              let thread = $('#threadName').val();
              if(thread !== '') {
                  $('#threadName').val('');
                  createMessageThread(project_data, thread);
              }
              else {
                  alert('Please enter a thread name');
              }

          });

          $('#messageBackButton').on('click', function() {
              let data = $(this).data('proj');
              loadInThreads(data);
          });

          $('.messageHolder').on('click', '#addMessage', function() {
              let messageVal = $('#mainMsg').val();
              let proj_id = $('#threadID').val();

              createMessage(messageVal, proj_id);
          });

          $('#message_container').on('click', '.messageThread', function() {
              let thread_data = $(this).data('id');
              let thread_name= $(this).data('name');
              goToMessageThread(thread_data, thread_name);
          });
      });
   </script>
@endsection