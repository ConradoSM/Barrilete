<div class="comment-container">
    <img class="comment-user" src="{{ $comment->user->photo ? asset('img/users/images/'.$comment->user->photo) : asset('svg/comment-user.svg') }}" alt="user">
    <p class="comment" id="{{ $comment->id }}">
        @auth
            @if(Auth::user()->id == $comment->user_id)
                <img alt="Delete" title="Borrar" class="action delete" src="{{ asset('svg/remove-symbol.svg') }}" data-comment="{{ $comment->id}}" data-article="{{$comment->article_id}}" data-section="{{$comment->section_id }}" />
                <img alt="Edit" title="Editar" class="action edit" src="{{asset('svg/pencil.svg')}}" data-comment="{{ $comment->id}}" data-article="{{$comment->article_id}}" data-section="{{$comment->section_id }}" />
            @endif
        @endauth
        <b>{{ $comment->user->name }}</b>: <span class="content">{{ $comment->content }}</span>
        <span class="reactions">
            {{ ucfirst($comment->created_at->diffForHumans()) }}
            <img id="total-likes-{{ $comment->id }}" src="{{ asset('svg/like.svg') }}" alt="likes" /> {{ $comment->getTotalLikes->count() }}
            <img id="total-dislikes-{{ $comment->id }}" src="{{ asset('svg/dislike.svg') }}" alt="dislikes" /> {{ $comment->getTotalDislikes->count() }}
        </span>
    </p>
    @auth
        <p class="actions">
            @if (Auth::user()->authorizeRoles([\barrilete\User::DEFAULT_USER_ROLE,\barrilete\User::EDITOR_USER_ROLE,\barrilete\User::ADMIN_USER_ROLE]))
                @php ($reactionExist = $comment->getUserReaction(Auth::user()->id))
                @php ($reactionValue = $reactionExist ? $reactionExist->reaction : null)
                @if ($reactionValue === 1)
                    <a class="reaction" title="Me gusta" data-user="{{Auth::user()->id}}" data-comment="{{ $comment->id}}" data-reaction="1" id="like-{{ $comment->id }}">Me gusta</a> ·
                    <a class="reaction" title="No me gusta" data-user="{{Auth::user()->id}}" data-comment="{{ $comment->id}}" data-reaction="0" id="dislike-{{ $comment->id }}">No me gusta</a> ·
                @elseif ($reactionValue === 0)
                    <a class="reaction" title="Me gusta" data-user="{{Auth::user()->id}}" data-comment="{{ $comment->id}}" data-reaction="1" id="like-{{ $comment->id }}">Me gusta</a> ·
                    <a class="reaction" title="No me gusta" data-user="{{Auth::user()->id}}" data-comment="{{ $comment->id}}" data-reaction="0" id="dislike-{{ $comment->id }}">No me gusta</a> ·
                @else
                    <a class="reaction" title="Me gusta" data-user="{{Auth::user()->id}}" data-comment="{{ $comment->id}}" data-reaction="1" id="like-{{ $comment->id }}">Me gusta</a> ·
                    <a class="reaction" title="No me gusta" data-user="{{Auth::user()->id}}" data-comment="{{ $comment->id}}" data-reaction="0" id="dislike-{{ $comment->id }}">No me gusta</a> ·
                @endif
            @endif
            <a class="edit" title="Responder" data-user="{{Auth::user()->id}}" data-comment="{{ $comment->id}}" data-article="{{$comment->article_id}}" data-section="{{$comment->section_id }}">Responder</a>
        </p>
    @endauth
    @if ($comment->replies)
        @include('comments.list', ['comments' => $comment->replies])
    @endif
</div>
