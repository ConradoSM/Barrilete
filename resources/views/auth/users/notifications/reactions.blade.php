@auth
    @forelse(Auth::user()->getCommentNotifications() as $key => $notifications)
        <ul>
            @foreach($notifications as $notification)
            @if ($notification->type == 'barrilete\Notifications\UsersCommentReaction')
            <li class="{{ $notification->read_at ? '' : 'unread' }}" onclick="location.href='{{$notification->data['link']}}'">
                <p>A <strong>{{$key}}</strong> {{$notification->data['reaction'] == 1 ? 'le gusta' : 'no le gusta'}} tu comentario</p>
                <span><img src="{{$notification->data['reaction'] == 1 ? asset('svg/like.svg') : asset('svg/dislike.svg')}}" />{{ucfirst($notification->created_at->diffForHumans())}}</span>
            </li>
            @elseif($notification->type == 'barrilete\Notifications\UsersCommentReply')
            <li class="{{ $notification->read_at ? '' : 'unread' }}" onclick="location.href='{{$notification->data['link']}}'">
                <p><strong>{{$notification->data['from']}}</strong> ha respondido tu comentario</p>
                <span><img src="{{asset('svg/reply.svg')}}" />{{ucfirst($notification->created_at->diffForHumans())}}</span>
            </li>
            @endif
            @if (!$notification->read_at)
            @php($notification->markAsRead())
            @endif
            @endforeach
        </ul>
    @empty
        <ul>
            <li>Sin notificaciones</li>
        </ul>
    @endforelse
@else
    <ul>
        <li>No estás logueado</li>
    </ul>
@endauth
