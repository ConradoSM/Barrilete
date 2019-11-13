$(document).ready(function () {
    $('input[type="submit"]').on('click', function () {
        const progress = $('#progressBar-Container');
        const bar = $('.bar');
        const percent = $('.percent');
        const status = $('#status');
        const CKEditor = $(document).find('#article_body').length;
        const form = $(this).closest('form');
        const options = {
            beforeSerialize: getCkeditor,
            beforeSubmit: loader,
            uploadProgress: progressBar,
            success: success,
            error: error,
            data: form.serialize(),
            datatype: 'json',
            async: true
        };
        /**
         * ENVIAR FORMULARIO
         */
        form.ajaxForm(options);

        /**
         * MOSTRAR EDITOR CKeditor
         */
        function getCkeditor()
        {
            if (CKEditor) {
                const section_notes_data = CKEDITOR.instances.article_body.getData();
                return $('#article_body').val(section_notes_data);
            }
            return null;
        }

        /**
         *  MOSTRAR LOADER
         */
        function loader()
        {
            const percentVal = '0%';
            $('p.invalid-feedback').hide();
            $(document).scrollTop(0);
            status.fadeOut('fast', function(){
                progress.fadeIn('fast', function(){
                    bar.width(percentVal);
                    percent.html(percentVal);
                });
            });
        }

        /**
         *
         * @param event
         * @param position
         * @param total
         * @param percentComplete
         */
        function progressBar(event, position, total, percentComplete)
        {
            const percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        }

        /**
         *
         * @param responseText
         */
        function success(responseText)
        {
            $('div#user-content').html(responseText).fadeIn('fast');
        }

        /**
         *
         * @param xhr
         * @param errorThrown
         */
        function error(xhr, errorThrown)
        {
            const imgError = '<img src="/svg/ajax-error.svg"/>';
            const errors = xhr.responseJSON.errors;
            progress.fadeOut('fast', function() {
                $('div#status').fadeIn('slow');
                if (errors) {
                    $.each(errors, function (key, value) {
                        $('div#errors').append('<p class="invalid-feedback">' + imgError + value + '</p>');
                    });
                } else {
                    $('div#errors').html('<p class="invalid-feedback">' + imgError + xhr.status + ' - ' + xhr.statusText +'</p>');
                }

            });
        }
    });
});
