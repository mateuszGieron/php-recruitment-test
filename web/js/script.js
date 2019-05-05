$(document).ready(function(){
    $('.js-website-id').on('click', function () {
        var isChecked = $(this).is(':checked');
        var varnishId = $(this).data('varnish-id');
        var websiteId = $(this).data('website-id');
        var $url = "/varnish-unlink";

        if (isChecked) {
            $url = "/varnish-link";
        }

        var request = $.ajax({
            url: $url,
            method: "POST",
            data: { varnishId : varnishId, websiteId: websiteId, unlink: !isChecked },
            dataType: "json"
        });

        request.done(function( msg ) {
            $( ".js-container" ).find('.bg-info').remove();
            $( ".js-container" ).prepend( $('<p class="bg-info" />').html(msg.flash) );
        });

        request.fail(function( jqXHR, textStatus ) {
            alert( "Request failed: " + textStatus );
        });
    });
});