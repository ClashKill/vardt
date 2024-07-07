/* global wp, jQuery */
/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					clip: 'rect(1px, 1px, 1px, 1px)',
					position: 'absolute',
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					clip: 'auto',
					position: 'relative',
				} );
				$( '.site-title a, .site-description' ).css( {
					color: to,
				} );
			}
		} );
	} );

	wp.customize( 'exalt_container_width', function( value ) {
		value.bind( function( to ) {
			if( ( 'blank' !== to ) && ( to >= 300 ) && (to <= 2000 ) ) {
				$( '.exalt-container' ).css( {
					'width': to + 'px'
				} );
			}
		} )
	} );

	wp.customize( 'exalt_boxed_width', function( value ) {
		value.bind( function( to ) {
			if( ( 'blank' !== to ) && ( to >= 300 ) && (to <= 2000 ) ) {
				$( 'body.exalt-boxed #page' ).css( {
					'width': to + 'px'
				} );
			}
		} )
	} );

}( jQuery ) );
