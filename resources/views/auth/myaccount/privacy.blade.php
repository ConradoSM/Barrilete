
<p class="user-title"><img src="{{asset('svg/mail.svg')}}" alt="Newsletter">Boletín informativo</p>
<fieldset>
    <p>El boletín informativo le permite estar al tanto de los artículos más recientes por correo electrónico. Para suscribirse, selecciona el campo que aparece a continuación. Puede anular su suscripción a la lista de distribución de correo a través del enlace especial incluido en los correos electrónicos.</p>
    <form method="post" action="{{route('newslettersSubscribe')}}" id="newsletter-update">
        <label class="check-container" for="status">Suscripción al boletín informativo
            <input type="checkbox" name="status" id="status" value="1" {{auth()->user()->isNewsletterSubscribe() ? 'checked' : ''}}>
            <span class="check-mark"></span>
        </label>
        @csrf
        <input type="submit" class="button primary" value="Actualizar" id="newsletter-submit">
    </form>
</fieldset>
<br>
<p class="user-title"><img src="{{asset('svg/shield.svg')}}" alt="Privacidad">Eliminar Cuenta</p>
<fieldset>
    <p>Tu cuenta se borrará en forma permanente de nuestros registros, ésto incluye:</p>
    <ul>
        <li>Mensajes</li>
        <li>Comentarios</li>
        <li>Notificaciones</li>
        <li>Suscripción al boletín informativo</li>
        <li>Y todas las opciones personalizadas</li>
    </ul>
    <p class="alert feedback-warning">Si cambias de parecer, no podrás recuperarla.</p>
    <form action="{{route('deleteOwnAccount')}}" method="post" id="delete-account-form">
        @csrf
        <input type="hidden" name="email" value="{{auth()->user()->email}}">
        <input type="submit" class="button danger" value="Eliminar" id="delete-account-submit">
    </form>
</fieldset>
<script>
    $(document).ready(function() {
        const screenWidth = $(window).width() < 767.98 ? '90%' : '55%';
        /** Update Newsletter **/
        $('form#newsletter-update').submit(function(e) {
            e.preventDefault();
            $.post($(this).attr('action'), $(this).serialize()).done(function(data) {
                alert(data.message, data.type);
            }).fail(function() {
                alert('Ocurrió un error al procesar tu suscripción, por favor intentalo mas tarde.', 'error');
            });
        });

        function alert(data, classType) {
            return $.alert({
                title: 'Boletín informativo',
                closeIcon: true,
                content: '<p class="alert feedback-'+classType+'">' + data + '</p>',
                boxWidth: screenWidth,
                useBootstrap: false,
                buttons: {
                    Cerrar: {
                        btnClass: 'button small primary',
                    }
                }
            });
        }

        /** Delete Account **/
        $('form#delete-account-form').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            $.confirm({
                title: 'Borrar Cuenta',
                closeIcon: true,
                content: '<p class="alert feedback-warning">¿Realmente quieres borrar tu cuenta?</p>',
                boxWidth: screenWidth,
                useBootstrap: false,
                buttons: {
                    borrar: {
                        btnClass: 'button small danger',
                        action: function() {
                            $.post(form.attr('action'), form.serialize()).done(function(data) {
                                return window.location.href = data;
                            }).fail(function() {
                                alert('Ocurrió un error al procesar tu suscripción, por favor intentalo mas tarde.', 'error');
                            });
                        }
                    },
                    cancelar: {
                        btnClass: 'button small default',
                    }
                }
            });
        });
    });
</script>
