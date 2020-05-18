<p class="user-title"><img src="{{asset('svg/email.svg')}}" alt="Mis Mensajes">Mis Mensajes</p>
@if($result->count() > 0)
    <ul class="inbox-messages">
    @foreach($result as $item)
        <li class="{{$item->status == 0 ? 'unread' : ''}}" data-link="{{route('getConversation', ['id' => $item->parent_id ? $item->parent_id : $item->id])}}">
            <img src="{{$item->getSender()->photo ? asset('img/users/'.$item->getSender()->photo) : asset('svg/user-blue.svg')}}" alt="{{$item->getSender()->name}}">
            <p>
                <span>{{$item->getSender()->name}}</span>
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
    $('#select_all').click(function() {
        const checkboxes = $('td').find(':checkbox');
        checkboxes.prop('checked', $(this).is(':checked'));
    });

    $('ul.inbox-messages').find('li').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        const link = $(this).attr('data-link');
        if (link) {
            $.ajax({
                type: 'GET',
                url: link,
                beforeSend: function () {
                    $(document).scrollTop(0);
                    $('div#users-content').html('<img id="loader" src="/img/loader.gif" />');
                },
                success: function (data) {
                    $('div#users-content').html(data.view);
                },
                error: function (xhr) {
                    $('div#users-content').html('<p class="alert feedback-error">Error: ' + xhr.status + ' - ' + xhr.statusText + '</p>');
                }
            });
        }
    });
</script>
