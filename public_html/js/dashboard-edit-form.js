$(document).ready(function () {
    /**
     * MOSTRAR FORMULARIOS
     */
    $('a.danger').css('display','none');
    $('fieldset form p').on('click', function() {
        const p = $(this);
        const input = p.next();
        const submit = p.parent('form.data').children('input[type=submit]');
        const deleteBtn = p.parent('form.data').children('a.danger');
        p.fadeOut('fast', function() {
            submit.css('display','inline-block');
            if (deleteBtn) {
                deleteBtn.css('display', 'inline-block');
            }
            input.fadeIn('fast', function() {
                input.focus();
                input.on('keyup', function() {
                    const valor = $(this).val();
                    p.text(valor);
                });
            });
        });
        input.on('focusout', function() {
            submit.fadeOut('fast');
            if (deleteBtn) {
                deleteBtn.fadeOut('fast');
            }
            input.fadeOut('fast', function() {
                p.fadeIn('fast');
            });
        });
    });

    /**
     * MOSTRAR FORM ACTUALIZAR FOTO
     */
    $('img.update-image-button').on('click', function() {
        const form = $(this).parent('div').children('form');
        form.toggle('linear');
    });
});
