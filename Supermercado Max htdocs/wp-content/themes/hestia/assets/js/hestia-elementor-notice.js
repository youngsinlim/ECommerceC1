/* global hestiaElementorNotice */
jQuery(document).ready( function () {

    var dialog = '';

    dialog = '<div class="hestia-disable-elementor-styling">' +
                '<div class="hestia-elementor-notice-wrapper">' +
                    '<div class="hestia-elementor-notice-header">Hestia supports default styling for Elementor widgets</div>' +
                    '<div class="hestia-elementor-notice-body">Do you want to disable Elementors\' default styles and use the theme defaults?</div>' +
                    '<div class="hestia-elementor-notice-buttons">' +
                        '<a href="#" class="hestia-do-nothing" data-reply="no">No</a>' +
                        '<a href="#" class="hestia-disable-default-styles" data-reply="yes">Yes</a>' +
                    '</div>' +
                '</div>' +
            '</div>';

    jQuery('body').prepend( dialog );
    jQuery('.hestia-elementor-notice-buttons > a').on('click', function() {

        var reply = jQuery(this).data('reply');
        jQuery.ajax({
            url: hestiaElementorNotice.ajaxurl,
            data: {
                reply: reply,
                nonce: hestiaElementorNotice.nonce,
                action: 'hestia_elementor_deactivate_default_styles'
            },
            type: 'post',
            success: function () {
                if( reply === 'yes' ) {
                    parent.location.reload();
                } else {
                    jQuery('.hestia-disable-elementor-styling').fadeOut(500, function() { jQuery(this).remove(); });
                }
            }
        });
    });
});
