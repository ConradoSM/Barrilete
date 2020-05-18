<ul>
    @auth
        @forelse(Auth::user()->getMessageNotifications()->get() as $notification)
            @if ($notification->type == 'barrilete\Notifications\UsersMessages')
                <li class="{{ $notification->read_at != null ? '' : 'unread' }}" onclick="location.href='{{ route('users.dashboard') }}?conversation_id={{$notification->data['conversation_id']}}'">
                    <p><strong>{{$notification->data['from']}}</strong>: {{$notification->data['message']}}</p>
                    <span><img src="{{asset('svg/mail-grey.svg')}}" />{{ucfirst($notification->created_at->diffForHumans())}}</span>
                </li>
            @endif
            @php($notification->markAsRead())
        @empty
            <li>No tienes mensajes</li>
        @endforelse
    @else
        <li>No estás logueado</li>
    @endauth
</ul>
