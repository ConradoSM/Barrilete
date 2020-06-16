$(document).ready(function () {
    /** Show update photo form **/
    $('img.update-image-button').on('click', function() {
        const form = $(this).parent('div').children('form');
        if (form.is(':hidden')) {
            form.slideDown('fast');
        } else {
            form.slideUp('fast');
        }
    });
});
