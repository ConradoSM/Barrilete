<ul>
@auth
    @forelse(Auth::user()->getCommentNotifications()->get() as $notification)
            @if ($notification->type == 'barrilete\Notifications\UsersCommentReaction')
            <li class="{{ $notification->read_at != null ? '' : 'unread' }}" onclick="location.href='{{$notification->data['link']}}'">
                <p>A <strong>{{$notification->data['from']}}</strong> {{$notification->data['reaction'] == 1 ? 'le gusta' : 'no le gusta'}} tu comentario</p>
                <span><img src="{{$notification->data['reaction'] == 1 ? asset('svg/like.svg') : asset('svg/dislike.svg')}}" />{{ucfirst($notification->created_at->diffForHumans())}}</span>
            </li>
            @elseif($notification->type == 'barrilete\Notifications\UsersCommentReply')
            <li class="{{ $notification->read_at != null ? '' : 'unread' }}" onclick="location.href='{{$notification->data['link']}}'">
                <p><strong>{{$notification->data['from']}}</strong> ha respondido tu comentario</p>
                <span><img src="{{asset('svg/reply.svg')}}" />{{ucfirst($notification->created_at->diffForHumans())}}</span>
            </li>
            @endif
        @php($notification->markAsRead())
    @empty
        <li>Sin notificaciones</li>
    @endforelse
@else
    <li>No estás logueado</li>
@endauth
</ul>
