<div class="comment-container">
    <img class="comment-user" src="{{ $comment->user->photo ? asset('img/users/images/'.$comment->user->photo) : asset('svg/comment-user.svg') }}" alt="user">
    <p class="comment">
        @auth
            @if(Auth::user()->id == $comment->user_id)
                <img onclick="deleteConfirm('{{ $comment->id}}', '{{$comment->article_id}}', '{{$comment->section_id }}')" alt="Delete" title="Borrar" class="action delete" src="{{ asset('svg/remove-symbol.svg') }}">
                <img onclick="editComment('{{$comment->id}}', '{{$comment->article_id}}', '{{$comment->section_id }}', '{{$comment->content}}')" src="{{asset('svg/pencil.svg')}}" class="action edit" alt="Edit" title="Editar" />
            @endif
        @endauth
        <b>{{ $comment->user->name }}</b>: {{ $comment->content }}
        <span class="reactions">
            {{ ucfirst($comment->created_at->diffForHumans()) }}
            <img id="total-likes-{{ $comment->id }}" src="{{ asset('svg/like.svg') }}" alt="like" /> {{ $comment->getTotalLikes->count() }}
            <img id="total-dislikes-{{ $comment->id }}" src="{{ asset('svg/dislike.svg') }}" alt="dislike" /> {{ $comment->getTotalDislikes->count() }}
        </span>
    </p>
    @auth
        <p class="actions">
            @if (Auth::user()->authorizeRoles([\barrilete\User::DEFAULT_USER_ROLE,\barrilete\User::EDITOR_USER_ROLE,\barrilete\User::ADMIN_USER_ROLE]))
                @php ($reactionExist = $comment->getUserReaction(Auth::user()->id))
                @php ($reactionValue = $reactionExist ? $reactionExist->reaction : null)
                @if ($reactionValue === 1)
                    <a onclick="commentReactionSave('{{ Auth::user()->id }}','{{ $comment->id }}', '1')" id="like-{{ $comment->id }}" class="reaction-active">Me gusta</a> ·
                    <a onclick="commentReactionSave('{{ Auth::user()->id }}','{{ $comment->id }}', '0')" id="dislike-{{ $comment->id }}">No me gusta</a> ·
                @elseif ($reactionValue === 0)
                    <a onclick="commentReactionSave('{{ Auth::user()->id }}','{{ $comment->id }}', '1')" id="like-{{ $comment->id }}">Me gusta</a> ·
                    <a onclick="commentReactionSave('{{ Auth::user()->id }}','{{ $comment->id }}', '0')" id="dislike-{{ $comment->id }}" class="reaction-active">No me gusta</a> ·
                @else
                    <a onclick="commentReactionSave('{{ Auth::user()->id }}','{{ $comment->id }}', '1')" id="like-{{ $comment->id }}">Me gusta</a> ·
                    <a onclick="commentReactionSave('{{ Auth::user()->id }}','{{ $comment->id }}', '0')" id="dislike-{{ $comment->id }}">No me gusta</a> ·
                @endif
            @endif
            <a onclick="replyComment('{{ $comment->id}}', '{{$comment->article_id}}', '{{$comment->section_id }}', '{{Auth::user()->id}}')" title="Responder">Responder</a>
        </p>
    @endauth
    @if ($comment->replies)
        @include('comments.list', ['comments' => $comment->replies])
    @endif
</div>
