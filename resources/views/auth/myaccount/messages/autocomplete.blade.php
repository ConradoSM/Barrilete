<ul id="list">
@forelse ($result as $user)
    <li data-id="{{$user->id}}" data-name="{{$user->name}}"><img src="{{$user->photo ? asset('img/users/'.$user->photo) : asset('svg/user-blue.svg')}}" alt="{{$user->name}}">{{$user->name}}</li>
@empty
    <li>No hay resultados.</li>
@endforelse
</ul>
<script>
    $('ul#list').find('li').on('click', function () {
        const id = $(this).attr('data-id');
        const name = $(this).attr('data-name');
        if (id && name) {
            $('input#user').val(name);
            $('input#user_id').val(id);
            $('div#users').slideUp('fast');
            $('input#submit').removeAttr('disabled').removeClass('disabled').addClass('primary');
        }
    });
</script>
