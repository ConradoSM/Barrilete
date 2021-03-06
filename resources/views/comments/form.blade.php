@if (Auth::check())
    @if (Auth::user()->authorizeRoles([
        \barrilete\User::DEFAULT_USER_ROLE,
        \barrilete\User::EDITOR_USER_ROLE,
        \barrilete\User::ADMIN_USER_ROLE
        ])
    )
        <form action="{{ route('commentsSave') }}" method="post" id="send-comment">
            @csrf
            <textarea name="comment" id="comment" class="comment" placeholder="Tu comentario:" required></textarea>
            <input type="submit" class="button primary" value="Enviar">
            <!--Hidden Inputs-->
            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            <input type="hidden" name="article_id" value="{{ $article->id }}">
            <input type="hidden" name="section_id" value="{{ $article->section_id }}">
            <input type="hidden" name="current_page" value="1">
        </form>
    @else
        <p class="alert feedback-warning">No tienes privilegios para comentar, contáctate con el administrador del sitio</p>
    @endif
@else
    <p class="alert feedback-warning"><span>Debes estar logueado para comentar <a href="{{ route('login') }}">ingresa</a> o <a href="{{ route('register') }}">regístrate</a></span></p>
@endif
