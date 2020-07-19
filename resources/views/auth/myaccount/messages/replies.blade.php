@forelse($replies->reverse() as $item)
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
