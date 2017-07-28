/**
 * File customizer-controls.js
 *
 * The file for generic customizer controls.
 */

jQuery(document).ready( function() {
    'use strict';

    updateArrays();

    jQuery('#accordion-section-hestia_blog_authors .accordion-section-title').click( function() {
        updateArrays();
    });

    function updateArrays() {

        var titles = [];
        var ids = [];
        var counter = 0;

        jQuery('#customize-control-hestia_team_content .customizer-repeater-general-control-repeater-container').each( function () {

            var title = jQuery(this).find('.customizer-repeater-title-control').val();

            if( typeof (title) !== 'undefined' ) {
                titles.push(title);
            }

            var id = jQuery(this).find('.social-repeater-box-id').val();

            if ( typeof (id) !== 'undefined' ) {
                ids.push(id);
            }

            counter ++;

        });

        jQuery('.repeater-multiselect-team').empty();

        jQuery('<option value="0">Empty</option>').appendTo('.repeater-multiselect-team');

        for (var i = 0; i < counter; i++) {

            if (titles.length > 0 ) {
                jQuery('<option value="'+ ids[i] +'">' + titles[i] + '</option>').appendTo('.repeater-multiselect-team');
            }

        }
    }

    /* Move controls to Widgets sections. Used for sidebar placeholders */
    if ( typeof wp.customize.control( 'hestia_placeholder_sidebar_1' ) !== 'undefined' ) {
        wp.customize.control('hestia_placeholder_sidebar_1').section('sidebar-widgets-sidebar-1');
    }
    if ( typeof wp.customize.control( 'hestia_placeholder_sidebar_woocommerce' ) !== 'undefined' ) {
        wp.customize.control('hestia_placeholder_sidebar_woocommerce').section('sidebar-widgets-sidebar-woocommerce');
    }
});
