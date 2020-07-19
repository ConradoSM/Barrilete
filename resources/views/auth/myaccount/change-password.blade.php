<p class="user-title"><img src="{{asset('svg/padlock.svg')}}" alt="Cambiar Contraseña">Cambiar Contraseña</p>
<fieldset>
    <div id="status"></div>
    <form action="{{route('updatePassword')}}" method="post" id="update-password">
        <label for="current_password">Contraseña actual:</label>
        <input type="password" name="current_password" id="current_password" required />
        <label for="password">Contraseña nueva:</label>
        <input type="password" name="password" id="password" required />
        <label for="password_confirm">Confirmar contraseña:</label>
        <input type="password" name="password_confirm" id="password_confirm" required />
        <input type="submit" class="button primary" value="Actualizar contraseña" />
        @csrf
    </form>
</fieldset>
<script>
    $('form#update-password').validate({
        rules: {
            field: {
                required: true,
                password: true,
            },
            password : {
                minlength : 7
            },
            password_confirm : {
                minlength : 7,
                equalTo : "#password"
            }
        },
        messages: {
            current_password: {
                required: 'Éste campo es requerido.'
            },
            password: {
                required: 'Éste campo es requerido.',
                minlength: 'Debe tener una longitud de al menos 7 caracteres.'
            },
            password_confirm: {
                required: 'Éste campo es requerido.',
                minlength: 'Debe tener una longitud de al menos 7 caracteres.',
                equalTo: 'Por favor ingrese el mismo valor otra vez.'
            }
        },
        errorElement: 'p',
        errorPlacement: function(error, element) {
            element.after(error);
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            const loader = $('img#loader'),
                container = $('div#container');
            $(document).scrollTop(0);
            container.hide();
            loader.show();
            $.post(form.action, $(form).serialize()
            ).done(function(data) {
                container.html(data.view);
                if (data.status) {
                    const statusClass = data.status === 'success' ? 'success' : 'error';
                    $('div#status').html('<p class="alert feedback-'+statusClass+'">'+data.message+'</p>');
                }
            }).fail(function(xhr) {
                const errors = typeof xhr.responseJSON != 'undefined' ? xhr.responseJSON.errors : '';
                const url = '{{route('editMyPassword')}}';
                $.get(url, function(data) {
                    container.html(data.view);
                    const errorsContainer = $('div#status');
                    if (errors) {
                        $.each(errors, function (key, value) {
                            errorsContainer.append('<p class="alert feedback-error">' + value + '</p>');
                        });
                    } else {
                        errorsContainer.html('<p class="alert feedback-error">'+ xhr.status + ' - ' + xhr.statusText +'</p>');
                    }
                });
            }).always(function() {
                loader.hide();
                container.show();
            });
        }
    });
</script>
