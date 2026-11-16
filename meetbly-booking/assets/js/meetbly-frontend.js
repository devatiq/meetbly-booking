( function () {
	'use strict';

	var config = window.meetblyBooking || {};
	var allowedOrigin = config.embedOrigin || 'https://meetbly.com';
	var maxHeight = typeof config.maxHeight === 'number' ? config.maxHeight : 20000;

	window.addEventListener( 'message', function ( event ) {
		if ( event.origin !== allowedOrigin ) {
			return;
		}

		if ( ! event.data || event.data.meetblyEmbed !== true ) {
			return;
		}

		if ( typeof event.data.height !== 'number' || ! isFinite( event.data.height ) ) {
			return;
		}

		var height = Math.min( Math.max( 0, event.data.height ), maxHeight );
		var iframes = document.querySelectorAll( 'iframe.meetbly-booking-embed' );

		for ( var i = 0; i < iframes.length; i++ ) {
			var iframe = iframes[ i ];
			if ( iframe.contentWindow && event.source === iframe.contentWindow ) {
				iframe.style.height = height + 'px';
				break;
			}
		}
	} );
} )();