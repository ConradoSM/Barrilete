<p class="user-title"><img src="{{asset('svg/email.svg')}}" alt="Mis Mensajes">Mensajes {{ $box == 'inbox' ? 'Recibidos' : 'Enviados' }}</p>
<div id="status"></div>
@if($result->count() > 0)
    <ul class="inbox-messages">
    @foreach($result as $item)
        <li class="{{$item->status == 0 ? 'unread' : ''}}" data-link="{{route('getConversation', ['id' => $item->parent_id ? $item->parent_id : $item->id])}}">
            @php($user = $box == 'inbox' ? $item->getSender() : $item->getRecipient())
            <img src="{{$user->photo ? asset('img/users/images/'.$user->photo) : asset('svg/user-blue.svg')}}" alt="{{$user->name}}">
            <p>
                <span>{{ $user->name }}</span>
                <span>{{ strlen($item->body) > 100 ? substr($item->body, 0, 100).'...' : $item->body }}</span>
                <span>{{ $item->created_at->diffForHumans() }}</span>
            </p>
        </li>
    @endforeach
    </ul>
@else
    <p class="alert feedback-warning">No hay mensajes.</p>
@endif
    {{$result->links()}}
<script>
    /** Messages **/
    $('ul.inbox-messages').find('li').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const link = $(this).attr('data-link');
        if (link) {
            ajaxCall(link);
        }
    });

    /** Links **/
    $('ul.pagination').find('a').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const link = $(this).attr('href');
        if (link) {
            ajaxCall(link);
        }
    });

    /**
     * Ajax Call
     * @param link
     */
    function ajaxCall(link) {
        const loader = $('img#loader'), container = $('div#container');
        $.get(link, {
            beforeSend: function () {
                $(document).scrollTop(0);
                container.hide();
                loader.show();
            }
        }).done(function(data) {
            container.html(data.view);
        }).fail(function(xhr) {
            container.html('<p class="alert feedback-error">Error: ' + xhr.status + ' - ' + xhr.statusText + '</p>');
        }).always(function() {
            container.show();
            loader.hide();
        });
    }

</script>
