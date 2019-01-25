@if (isset($Exito))
<p class="alert-success">{{ $Exito }}</p>
@endif
<h1>Detalle del usuario</h1>
<div id="action">
    @if (Auth::user()->is_admin)<a href="{{ route('users') }}" class="primary">Volver al listado</a>@endif
    <a href="{{ route('editUser', ['id' => $user->id]) }}" class="success">Editar</a>
    <a href="#" class="danger">Borrar</a>
</div>
<fieldset>
    @if ($user->photo)
    <img class="avatar" src="{{ asset('img/users/'.$user->photo) }}" />
    @else
    <img class="avatar" src="{{ asset('svg/avatar.svg') }}" />
    @endif
    <div class="user-info">
        <h2>{{ $user->name }}</h2>
        <p>{{ $user->email }} - @if ($user->is_admin) Administrador @else User @endif</p>
    </div>
</fieldset>
<fieldset>
    <h2>Más información</h2>
    <hr />
    <p><b>Edad</b>: <span class="info">{{ Carbon\Carbon::parse($user->birthday)->age }}</span></p>
    <p><b>Teléfono</b>: <span class="info">{{ $user->phone }}</span></p>
    <p><b>Dirección</b>: <span class="info">{{ $user->address }}</span></p>
    <p><b>Ciudad</b>: <span class="info">{{ $user->city }}</span></p>
    <p><b>Provincia</b>: <span class="info">{{ $user->state }}</span></p>
    <p><b>País</b>: <span class="info">{{ $user->country }}</span></p>
    <hr />
    <p><b>Acerca de mi</b>: <span class="info">{{ $user->description }}</span></p>
</fieldset>
<fieldset>
    <h2>Estadísticas</h2>
    <hr />
    <p><b>Artículos</b>: <span class="info">{{ $user->articles->count() }}</span></p>
    <p><b>Galerías</b>: <span class="info">{{ $user->gallery->count() }}</span></p>
    <p><b>Encuestas</b>: <span class="info">{{ $user->poll->count() }}</span></p>
</fieldset>
<script type="text/javascript">
    $(document).ready(function(){        
        $('div#action').find('a').each(function () {
            var href = $(this).attr('href');
            $(this).attr({href: '#'});            
            $(this).click(function(){
                $('#loader').fadeIn('fast');
                $('#user-content').hide(0, function () {
                    $('#user-content').load(href, function () {
                        $('#loader').fadeOut('fast', function () {
                            $('#user-content').fadeIn('fast');
                        });
                    });
                });
            });
        });
        setTimeout(function(){
            $('p.alert-success').fadeOut('fast', function(){
                $('p.alert-success').remove();
            });
        }, 2000);
    });
</script>