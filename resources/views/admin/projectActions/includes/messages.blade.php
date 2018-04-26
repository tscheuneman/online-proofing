<div id="message_loader">

</div>
<div id="message_container">
@if(isset($messages[0]))
    @foreach($messages as $message)
        <div class="messageThread" data-name="{{$message->subject}}" data-id="{{$message->id}}">
            {{$message->subject}} ({{$message->msg_cnt_count}})
        </div>
    @endforeach
@else
    <p class="none">
        No Messages
    </p>
@endif
</div>