/* Apply matchHeight to match team grid */
/* global jQuery */
/* global wp */
function hestiaMatchHeight() {
    'use strict';


    var in_customizer = false;

    // check for wp.customize return boolean
    if ( typeof wp !== 'undefined' ) {
        in_customizer =  typeof wp.customize !== 'undefined';
    }

    // if you're in the customizer do this
    if ( in_customizer ) {
        return;
    }

    var byRow = jQuery('body').hasClass('home');

    if (typeof jQuery('#features') !== 'undefined') {
        jQuery('.hestia-features .row .col-md-4, .features .row .col-md-4').matchHeight(byRow);
    }

    if (typeof jQuery('#products') !== 'undefined') {
        jQuery('.products .row .col-md-3, .products .row .col-sm-6').matchHeight(byRow);
    }

    if (typeof jQuery('#portfolio') !== 'undefined') {
        jQuery('.hestia-work .row .col-md-4, .work .row .col-md-4, .hestia-work .row .col-md-6, .work .row .col-md-6').matchHeight(byRow);
    }

    if (typeof jQuery('#team') !== 'undefined') {
        jQuery('.hestia-team .row .col-md-6, .team .row .col-md-6').matchHeight(byRow);
    }

    if (typeof jQuery('#testimonials') !== 'undefined') {
        jQuery('.hestia-testimonials .row .col-md-4, .testimonials .row .col-md-4').matchHeight(byRow);
    }

    if (typeof jQuery('#pricing') !== 'undefined') {
        jQuery('.pricing .row .col-md-7 .col-md-6').matchHeight(byRow);
    }

    if (typeof jQuery('#blog') !== 'undefined') {
        jQuery('.hestia-blogs .row .col-md-4, .blogs .row .col-md-4').matchHeight(byRow);
    }
}