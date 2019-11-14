$(document).ready(function() {
    /**
     * FIND LINKS
     */
    $(document).find('a').each(function() {
        const dataAjax = $(this).attr('data-ajax');
        if (!dataAjax) {
            const href = $(this).attr('href');
            const dataConfirm = $(this).attr('data-confirm');
            $(this).attr({href: '#'});
            /**
             * CLICK EVENT
             */
            $(this).on('click', function(e) {
                const button = $(this);
                $('html, body').animate({ scrollTop: 0 }, 'fast');
                if (button.attr('class') === 'disabled') {
                    return false;
                }
                /**
                 * ADD/REMOVE SELECTED CLASS
                 */
                $(document).find('a').removeClass('selected');
                button.addClass('selected');
                /**
                 * EXECUTE AJAX REQUEST
                 */
                getAjaxRequest(e, button, href, dataConfirm);
            });
        }
    });

    /**
     * SEARCH DASHBOARD
     */
    $('button#search-button').on('click', function (e) {
        const button = $(this);
        const query = $('#query').val();
        const author = $('#author').val();
        const sec = $('#sec').val();
        const href = $('form#search').attr('action')+'?sec='+sec+'&author='+author+'&query='+query;
        getAjaxRequest(e, button, href, null);
    });

    /**
     * BEFORE AJAX REQUEST
     */
    function beforeSend() {
        $('div#user-content').hide();
        $('#loader').fadeIn('fast');
    }

    /**
     * AJAX GET REQUEST
     */
    function getAjaxRequest(e, button, href, dataConfirm) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if (dataConfirm) {
            if (!confirm(dataConfirm)) {
                return false;
            }
        }
        /**
         * STOP AJAX REQUEST RUNNING
         */
        if (button.data('requestRunning')) {
            return;
        }
        button.data('requestRunning', true);
        /**
         * AJAX REQUEST STARTER
         */
        $.get({
            beforeSend: beforeSend,
            type: 'GET',
            url: href,
            async: true,
            dataType: 'json',
            contentType: 'application/json'
        }).done(function (data) {
            $('#loader').fadeOut('fast', function () {
                $('div#user-content').html(data.view).fadeIn('fast');
            });
        }).fail(function (xhr) {
            const xhrError = xhr.responseJSON.error ? xhr.responseJSON.error : xhr.statusText;
            const errorMessage = '<p class="invalid-feedback"><img src="/svg/ajax-error.svg" alt="error"/>' + xhr.status + ' - ' + xhrError + '</p>';
            $('#loader').fadeOut('fast', function () {
                $('div#user-content').html(errorMessage).fadeIn('fast');
            });
        }).always(function () {
            button.data('requestRunning', false);
        });
    }
});
