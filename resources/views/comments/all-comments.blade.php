<h1>Comentarios</h1>
<p>Se encontraron {{ $comments->total() }} comentarios</p>
@if (!empty($message))
    <p class="alert feedback-{{ $message['type'] }}">{{ $message['value'] }}</p>
@endif
<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Fecha</th>
        <th>Usuario</th>
        <th>Contenido</th>
        <th>Acción</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($comments as $comment)
        <tr>
            <td>{{ $comment->id }}</td>
            <td>{{ ucfirst($comment->created_at->diffForHumans()) }}</td>
            <td>{{ $comment->user->name }}</td>
            <td>{{ strlen($comment->content) > 30 ? substr($comment->content, 0, 30) .'...' : $comment->content }}</td>
            <td>
                <a href="{{ route('getCommentById',['id'=>$comment->id]) }}" class="button default small" title="Ver Comentario" data-view="view">Ver</a>
                <a href="{{ route('deleteCommentById',['id'=>$comment->id]) }}" class="button danger small" title="Borrar Comentario" data-confirm="¿Estás seguro que deseas borrar el comentario?">Borrar</a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No hay secciones</td>
        </tr>
    @endforelse
    </tbody>
</table>
{{ $comments->links() }}
<script type="text/javascript" src="{{asset('js/dashboard.js')}}"></script>
