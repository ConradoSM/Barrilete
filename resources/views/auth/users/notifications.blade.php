@auth
    @forelse($userUnreadNotifications as $notification)
        <div class="{{ $notification->read_at != null ? 'notify' : 'notify unread' }}" onclick="location.href='{{$notification->data['link']}}'">
            @if ($notification->type == 'barrilete\Notifications\UsersCommentReaction')
                <p class="data-notification">A <strong>{{$notification->data['from']}}</strong> {{$notification->data['reaction'] == 1 ? 'le gusta' : 'no le gusta'}} tu comentario</p>
                <img class="reaction-notification-img" src="{{$notification->data['reaction'] == 1 ? asset('svg/like.svg') : asset('svg/dislike.svg')}}" />
                <span class="date-notification">{{ucfirst($notification->created_at->diffForHumans())}}</span>
            @endif
            @if ($notification->type == 'barrilete\Notifications\UsersCommentReply')
                <p class="data-notification"><strong>{{$notification->data['from']}}</strong> ha respondido tu comentario</p>
                <img class="reaction-notification-img" src="{{asset('svg/reply.svg')}}" />
                <span class="date-notification">{{ucfirst($notification->created_at->diffForHumans())}}</span>
            @endif
        </div>
        @php($notification->markAsRead())
    @empty
        <div class="notify">
            <p class="data-notification">Sin notificaciones</p>
        </div>
    @endforelse
@else
    <div class="notify">
        <p class="data-notification">No estas logueado</p>
    </div>
@endauth
