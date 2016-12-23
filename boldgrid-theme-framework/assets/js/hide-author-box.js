
// Toggle Author Bio on and off.
jQuery( document ).ready( function(  ) {
    jQuery( '.reveal-bio a' ).click( function( ) {
        jQuery( '.author-info' ).slideToggle( 'slow' );
        if ( jQuery( '.reveal-bio a' ).hasClass( 'fa-minus-circle' ) ) {
            jQuery( '.reveal-bio a' ).removeClass( 'fa-minus-circle' ).addClass( 'fa-plus-circle reveal-fix' );
            jQuery( '.author-index' ).addClass( 'hide-fix' );
        } else {
            jQuery( '.reveal-bio a' ).removeClass( 'fa-plus-circle reveal-fix' ).addClass( 'fa-minus-circle' );
            jQuery( '.author-index' ).removeClass( 'hide-fix' );
        }

        return false;
    });
});
