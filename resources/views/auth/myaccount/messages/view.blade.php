@php
$user = $result->getSender()->id != Auth::id() ? $result->getSender() : $result->getRecipient();
$nextPage = strpos($result->replies()->nextPageUrl(), 'save') ? str_replace('save', 'message/'.$result->id.'/', $result->replies()->nextPageUrl()) : $result->replies()->nextPageUrl();
@endphp
<p class="user-title">
    <img src="{{asset($user->photo ? 'img/users/images/'.$user->photo : 'svg/user-blue.svg')}}" alt="{{$user->name}}" title="{{$user->name}}" />
    {{$user->name}}
</p>
<img class="message-bar delete-message" src="{{asset('svg/delete-message.svg')}}" title="Borrar mensaje" alt="Borrar mensaje" data-message-id="{{$result->id}}"/>
<ul class="messages" data-next-page="{{$nextPage}}">
    <li id="parent">
        <p class="{{$result->from == Auth::id() ? 'end' : ''}}">
            <img src="{{asset($result->getSender()->photo ? 'img/users/images/'.$result->getSender()->photo : 'svg/user-blue.svg')}}" alt="{{$result->getSender()->name}}" title="{{$result->getSender()->name}}" />
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
    @forelse($result->replies()->reverse() as $item)
        <li class="replies">
            <p class="{{$item->from == Auth::id() ? 'end' : ''}}">
                <img src="{{asset($item->getSender()->photo ? 'img/users/images/'.$item->getSender()->photo : 'svg/user-blue.svg')}}" alt="{{$item->getSender()->name}}" title="{{$item->getSender()->name}}"/>
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
    @empty
    @endforelse
</ul>
<form action="{{route('saveMessage')}}" method="post" id="send-message">
    <div id="status"></div>
    <label for="body">Responder:</label>
    <input type="text" name="body" id="body" required />
    <input type="submit" value="Enviar" class="button primary" />
    <input type="hidden" name="user_id" id="user_id" value="{{$user->id}}"/>
    <input type="hidden" name="parent_id" id="parent_id" value="{{$result->id}}">
    @csrf
</form>
<script src="{{asset('js/messages.js')}}" type="text/javascript"></script>
