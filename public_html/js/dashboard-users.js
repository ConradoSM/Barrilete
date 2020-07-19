$(document).ready(function() {
    $('form#edit').validate({
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
            $.ajax({
                method: form.method,
                url: form.action,
                data: formData,
                enctype: form.enctype,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $(document).scrollTop(0);
                    container.hide();
                    loader.show();
                },
                success: function(data) {
                    container.html(data);
                    const userInfo = $('div.user-info');
                    const photo = $('input[name=home]').attr('value');
                    const name = $('input[name=name]').attr('value');
                    if (photo) {
                        userInfo.find('img').attr('src', '/img/users/images/' + photo);
                    }
                    if (name) {
                        userInfo.find('p').text(name);
                    }
                },
                error: function(xhr) {
                    const errors = typeof xhr.responseJSON != 'undefined' ? xhr.responseJSON.errors : '';
                    const url = window.location.origin+'/dashboard/users/edit/'+userId+'?home=1';
                    $.get(url, function(data) {
                        container.html(data.view);
                        const status = $('div#status');
                        if (errors) {
                            $.each(errors, function (key, value) {
                                status.append('<p class="alert feedback-error">' + value + '</p>');
                            });
                        } else {
                            status.html('<p class="alert feedback-error">'+ xhr.status + ' - ' + xhr.statusText +'</p>');
                        }
                    });
                },
                complete: function() {
                    loader.hide();
                    container.show();
                }
            });
        }
    });
});
