@php
$user = $result->getSender()->id != Auth::id() ? $result->getSender() : $result->getRecipient();
@endphp
<p class="user-title">
    <img src="{{asset($user->photo ? 'img/users/'.$user->photo : 'svg/user-blue.svg')}}" alt="{{$user->name}}" title="{{$user->name}}" />
    {{$user->name}}
</p>
<ul class="messages">
    <li>
        <p class="{{$result->from == Auth::id() ? 'end' : ''}}">
            <img src="{{asset($result->getSender()->photo ? 'img/users/'.$result->getSender()->photo : 'svg/user-blue.svg')}}" alt="{{$result->getSender()->name}}" title="{{$result->getSender()->name}}" />
            <span>
                {{$result->body}}
            @if($result->from == Auth::id() AND $result->status)
                <img src="{{asset('svg/read.svg')}}" alt="Leído" title="Leído" />
            @endif
            </span>
        </p>
        <p class="{{$result->from == Auth::id() ? 'end' : ''}}">
            @if($result->from == Auth::id() AND $result->status AND isset($result->updated_at))
                Leído {{$result->updated_at->diffForHumans()}}
            @else
                {{$result->created_at->diffForHumans()}}
            @endif
        </p>
    </li>
    @if($result->from != Auth::id())
        @php($result->markAsRead())
    @endif
@if($result->replies()->get())
    @foreach($result->replies()->get() as $item)
        <li>
            <p class="{{$item->from == Auth::id() ? 'end' : ''}}">
                <img src="{{asset($item->getSender()->photo ? 'img/users/'.$item->getSender()->photo : 'svg/user-blue.svg')}}" alt="{{$item->getSender()->name}}" title="{{$item->getSender()->name}}"/>
                <span>
                    {{$item->body}}
                    @if($item->from == Auth::id() AND $item->status)
                        <img src="{{asset('svg/read.svg')}}" alt="Leído" title="Leído" />
                    @endif
                </span>
            </p>
            <p class="{{$item->from == Auth::id() ? 'end' : ''}}">
            @if($item->from == Auth::id() AND $item->status AND isset($item->updated_at))
                Leído {{$item->updated_at->diffForHumans()}}
            @else
                {{$item->created_at->diffForHumans()}}
            @endif
            </p>
            @if($item->from != Auth::id())
                @php($item->markAsRead())
            @endif
        </li>
    @endforeach
@endif
</ul>
<form action="{{route('saveMessage')}}" method="post" id="send-message">
    <div id="status"></div>
    <label for="body">Responder:</label>
    <textarea name="body" id="body" required></textarea>
    <input type="submit" value="Enviar" class="button primary" />
    <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}"/>
    <input type="hidden" name="parent_id" id="parent_id" value="{{$result->id}}">
    @csrf
</form>
<script>
    $('form#send-message').validate({
        messages: {
            body: {
                required: 'Éste campo es requerido.'
            }
        },
        errorElement: 'p',
        errorPlacement: function (error, element) {
            element.after(error);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            $.ajax({
                method: form.method,
                url: form.action,
                data: $(form).serialize(),
                beforeSend: function () {
                    $(document).scrollTop(0);
                    $('div#users-content').html('<img id="loader" src="/img/loader.gif" />');
                },
                success: function (data) {
                    $('div#users-content').html(data.view);
                    if (data.status) {
                        const statusClass = data.status === 'success' ? 'success' : 'error';
                        $('div#status').html('<p class="alert feedback-'+statusClass+'">'+data.message+'</p>');
                    }
                },
                error: function (xhr) {
                    const errors = typeof xhr.responseJSON != 'undefined' ? xhr.responseJSON.errors : '';
                    const url = '{{route('writeMessage')}}';
                    $.get(url, function(data) {
                        $('div#users-content').html(data.view);
                        const errorsContainer = $('div#status');
                        if (errors) {
                            $.each(errors, function (key, value) {
                                errorsContainer.append('<p class="alert feedback-error">' + value + '</p>');
                            });
                        } else {
                            errorsContainer.html('<p class="alert feedback-error">'+ xhr.status + ' - ' + xhr.statusText +'</p>');
                        }
                    });
                },
                complete: function() {
                    window.scrollTo(0,document.body.scrollHeight);
                }
            });
        }
    });
</script>
