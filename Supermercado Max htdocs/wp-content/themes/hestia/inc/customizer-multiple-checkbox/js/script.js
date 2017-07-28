/**
 * Script for multiple checkbox control.
 *
 * @package hestia
 * @since 1.1.38
 */

/* global jQuery */

jQuery( document ).ready( function() {
    'use strict';
    /* === Checkbox Multiple Control === */
    jQuery( '.customize-control-checkbox-multiple input[type="checkbox"]' ).on(
        'change',
        function() {

            var checkbox_values = jQuery( this ).parents( '.customize-control' ).find( 'input[type="checkbox"]:checked' ).map(
                function() {
                    return this.value;
                }
            ).get().join( ',' );

            jQuery( this ).parents( '.customize-control' ).find( 'input[type="hidden"]' ).val( checkbox_values ).trigger( 'change' );
        }
    );
} ); // jQuery( document ).ready