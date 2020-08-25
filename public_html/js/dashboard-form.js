$(document).ready(function () {
    const progress = $('#progressBar-Container');
    const bar = $('.bar');
    const percent = $('.percent');
    const status = $('#status');
    const CKEditor = $(document).find('#article_body').length;

    /** Click event **/
    $('input[type="submit"]').on('click', function() {
        const form = $(this).closest('form');
        const options = {
            beforeSerialize: getCkeditor,
            beforeSubmit: beforeSubmit,
            uploadProgress: progressBar,
            success: success,
            error: error,
            data: form.serialize(),
            datatype: 'json',
            async: true
        };
        /** Validate form **/
        const required = 'Este campo es requerido.',
              minLength = 'Ingrese al menos {0} caracteres.',
              email = 'Formato de email incorrecto.';
        form.validate({
            rules: {
                title: {
                    minlength: 20
                },
                article_desc: {
                    minlength: 50
                },
                email: {
                    email: true
                }
            },
            messages: {
                title: {
                    required: required,
                    minlength: minLength
                },
                article_desc: {
                    required: required,
                    minlength: minLength
                },
                section_id: {
                    required: required
                },
                photo: {
                    required: required
                },
                name: {
                    required: required
                },
                prio: {
                    required: required
                },
                email: {
                    required: required,
                    email: email
                },
                option: {
                    required: required
                },
                valid_from: {
                    required: required
                },
                valid_to: {
                    required: required
                }
            },
            errorElement: 'p',
            errorPlacement: function(error, element) {
                element.after(error);
            }
        });
        /** Send form **/
        form.ajaxForm(options);
    });

    /** Show CKeditor **/
    function getCkeditor() {
        if (CKEditor) {
            const section_notes_data = CKEDITOR.instances.article_body.getData();
            return $('#article_body').val(section_notes_data);
        }
        return null;
    }

    /** Show loader **/
    function beforeSubmit() {
        const percentVal = '0%';
        $('p.alert.feedback-error').remove();
        $(document).scrollTop(0);
        status.fadeOut('fast', function() {
            progress.show('fast', function() {
                bar.width(percentVal);
                percent.html(percentVal);
            });
        });
    }

    /** Progress bar **/
    function progressBar(event, position, total, percentComplete) {
        const percentVal = percentComplete + '%';
        bar.width(percentVal);
        percent.html(percentVal);
    }

    /** Success response **/
    function success(responseText) {
        $('div#user-content').html(responseText).fadeIn('fast');
    }

    /** Error response **/
    function error(xhr) {
        progress.fadeOut('fast', function () {
            const errors = xhr.responseJSON.errors;
            if (errors) {
                $.each(errors, function (key, value) {
                    $('div#errors').append('<p class="alert feedback-error">' + value + '</p>');
                });
            } else {
                $('div#errors').html('<p class="alert feedback-error">' + xhr.status + ' - ' + xhr.statusText + '</p>');
            }
            $('div#status').fadeIn('slow');
        });
    }
});
