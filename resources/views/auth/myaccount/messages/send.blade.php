<p class="user-title"><img src="{{asset('svg/mail.svg')}}" alt="Reactar Mensaje">Redactar Mensaje</p>
<fieldset>
    <form action="{{route('saveMessage')}}" method="post" id="send-message">
        <div id="status"></div>
        <label for="user">Para:</label>
        <input type="search" name="user" id="user" required />
        <div id="users"></div>
        <label for="body">Mensaje:</label>
        <textarea name="body" id="body" required></textarea>
        <input type="submit" value="Enviar" id="submit" class="button disabled" disabled />
        <input type="reset" value="Restablecer" class="button default" />
        <input type="hidden" name="user_id" id="user_id" />
        @csrf
    </form>
</fieldset>
<script>
    $('input#user').keyup(function() {
        if ($(this).val().length === 0) {
            $('div#users').slideUp('fast');
        }
        $('input#user_id').val('');
        if (!$('input#submit').attr('disabled')) {
            $('input#submit').attr('disabled', true).removeClass('primary').addClass('disabled');
        }
        $(this).autocomplete({
            source: function (request) {
                $.ajax({
                    type: 'GET',
                    url: '{{route('getUsers')}}',
                    dataType: 'json',
                    data: {
                        query: request.term,
                    },
                    success: function (data) {
                        $('div#users').html(data).slideDown('fast');
                    },
                    error: function (xhr) {
                        $('div#users').html('<p>Error: ' + xhr.status + ' - ' + xhr.statusText+'</p>').slideDown('fast');
                    }
                });
            },
            minLength: 1
        });
    }).keyup();

    $('form#send-message').validate({
        messages: {
            user: {
                required: 'Éste campo es requerido.'
            },
            body: {
                required: 'Éste campo es requerido.'
            }
        },
        errorElement: 'p',
        errorPlacement: function (error, element) {
            element.after(error);
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            $.ajax({
                method: form.method,
                url: form.action,
                data: $(form).serialize(),
                beforeSend: function () {
                    $(document).scrollTop(0);
                    $('div#users-content').html('<img id="loader" src="/img/loader.gif" />');
                },
                success: function (data) {
                    $('div#users-content').html(data.view);
                    if (data.status) {
                        const statusClass = data.status === 'success' ? 'success' : 'error';
                        $('div#status').html('<p class="alert feedback-'+statusClass+'">'+data.message+'</p>');
                    }
                },
                error: function (xhr) {
                    const errors = typeof xhr.responseJSON != 'undefined' ? xhr.responseJSON.errors : '';
                    const url = '{{route('writeMessage')}}';
                    $.get(url, function(data) {
                        $('div#users-content').html(data.view);
                        const errorsContainer = $('div#status');
                        if (errors) {
                            $.each(errors, function (key, value) {
                                errorsContainer.append('<p class="alert feedback-error">' + value + '</p>');
                            });
                        } else {
                            errorsContainer.html('<p class="alert feedback-error">'+ xhr.status + ' - ' + xhr.statusText +'</p>');
                        }
                    });
                }

            });
        }
    });
</script>
