@auth
    @forelse(Auth::user()->getMessageNotifications() as $key => $notifications)
        <ul>
        @php($countUnread = 0)
        @php($i = 0)
        @foreach($notifications as $notification)
            @if($i == 0)
            <li {{ $notification->read_at ? '' : 'unread' }}" onclick="location.href='{{ route('users.dashboard') }}?conversation_id={{$notification->data['conversation_id']}}'">
                <p><strong>{{$key}}</strong>: {{$notification->data['message']}}</p>
                <span><img src="{{asset('svg/mail-grey.svg')}}" />{{ucfirst($notification->created_at->diffForHumans())}}</span>
            </li>
            @endif
            @php($i++)
            @if(!$notification->read_at)
                @php(++$countUnread)
                @php($notification->markAsRead())
            @endif
        @endforeach
        @if($countUnread)
            <span class="count-unread">{{$countUnread}}</span>
        @endif
        </ul>
    @empty
        <ul>
            <li>No tienes mensajes</li>
        </ul>
    @endforelse
@else
    <ul>
        <li>No estás logueado</li>
    </ul>
@endauth
