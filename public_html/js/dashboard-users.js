$(document).ready(function() {
    /** Validate account form edit **/
    $(document).find('form#edit').validate({
        rules: {
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            email: {
                required: 'Éste campo es requerido',
                email: 'La dirección ingresada no es válida'
            },
            name: {
                required: 'Éste campo es requerido'
            }
        },
        errorElement: 'p',
        errorPlacement: function(error, element) {
            element.after(error);
        },
        submitHandler: function(form, e) {
            e.preventDefault();
            const formData = new FormData(form),
                loader = $('img#loader'),
                container = $('div#container'),
                userId = $('input[name=id]').attr('value');
            formData.append('photo', $('input[type=file]')[0].files[0]);
            loader.show();
            container.hide();
            $.ajax({
                method: form.method,
                url: form.action,
                data: formData,
                enctype: form.enctype,
                contentType: false,
                processData: false,
                success: function(data) {
                    container.html(data);
                    const userInfo = $('div.user-info'),
                        photo = $('input[name=home]').attr('value'),
                        name = $('input[name=name]').attr('value');
                    if (photo) {
                        userInfo.find('img').attr('src', '/img/users/images/' + photo);
                    }
                    if (name) {
                        userInfo.find('p').text(name);
                    }
                },
                error: function(xhr) {
                    const errors = typeof xhr.responseJSON != 'undefined' ? xhr.responseJSON.errors : '',
                        url = window.location.origin+'/dashboard/users/edit/'+userId+'?home=1';
                    ajaxErrorGet(url, container, errors);
                },
                complete: function() {
                    $('html, body').animate({
                        scrollTop: $('div#users-content').offset().top -250
                    });
                    loader.hide();
                    container.show();
                }
            });
        }
    });

    /** Validate password change form **/
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
            container.hide();
            loader.show();
            $.post(form.action, $(form).serialize()).done(function(data) {
                container.html(data.view);
                if (data.status) {
                    const statusClass = data.status === 'success' ? 'success' : 'error';
                    $('div#status').html('<p class="alert feedback-'+statusClass+'">'+data.message+'</p>');
                }
            }).fail(function(xhr) {
                const errors = typeof xhr.responseJSON != 'undefined' ? xhr.responseJSON.errors : '';
                const url = window.location.origin+'/dashboard/myaccount/password/edit';
                ajaxErrorGet(url, container, errors);
            }).always(function() {
                $('html, body').animate({
                    scrollTop: $('div#users-content').offset().top -250
                });
                loader.hide();
                container.show();
            });
        }
    });

    function ajaxErrorGet(url, container, errors) {
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
    }
});
