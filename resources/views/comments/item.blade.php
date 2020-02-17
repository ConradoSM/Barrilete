<div class="comment">
    <img class="user" src="{{ $comment->user->photo ? asset('img/users/'.$comment->user->photo) : asset('svg/comment-user.svg') }}" alt="user">
    <p>
        @auth
            @if(Auth::user()->id == $comment->user_id)
                <img onclick="deleteConfirm('{{ $comment->id}}', '{{$comment->article_id}}', '{{$comment->section_id }}')" alt="Delete" title="Borrar" class="action delete" src="{{ asset('svg/remove-symbol.svg') }}">
                <img onclick="editComment('{{$comment->id}}', '{{$comment->article_id}}', '{{$comment->section_id }}', '{{$comment->content}}')" src="{{asset('svg/pencil.svg')}}" class="action edit" alt="Edit" title="Editar" />
            @endif
        @endauth
        <b>{{ $comment->user->name }}</b>: {{ $comment->content }}
        <span>
            {{ ucfirst($comment->created_at->diffForHumans()) }}
            @auth
                @if (Auth::user()->authorizeRoles([\barrilete\User::DEFAULT_USER_ROLE,\barrilete\User::EDITOR_USER_ROLE,\barrilete\User::ADMIN_USER_ROLE]))
                    <img class="reply" src="{{ asset('svg/reply.svg') }}"  alt="reply"/>
                    <a onclick="replyComment({{ $comment->id}},{{$comment->article_id}},{{$comment->section_id }},{{Auth::user()->id}})" class="reply" title="Responder">Responder</a>
                @endif
            @endauth
        </span>
    </p>
    @if ($comment->replies)
        @include('comments.list', ['comments' => $comment->replies])
    @endif
</div>
<script>
    /**
     * Comments Box Functionality
     */
    $('img.delete').hide();
    $('img.edit').hide();
    $('div.comment').find('p').mouseover(function () {
        const buttonDelete = $(this).find('img.delete');
        const buttonEdit = $(this).find('img.edit');
        buttonEdit.show();
        buttonDelete.show();
        $(this).mouseleave(function () {
            buttonDelete.hide();
            buttonEdit.hide();
        });
    });
</script>
