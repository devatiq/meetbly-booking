( function () {
	'use strict';

	function updateShortcodePreview() {
		var handleInput = document.getElementById( 'meetbly_default_handle' );
		var serviceInput = document.getElementById( 'meetbly_default_service' );
		var output = document.getElementById( 'meetbly-shortcode-output' );

		if ( ! output || ! handleInput || ! serviceInput ) {
			return;
		}

		var handle = handleInput.value || '';
		var service = serviceInput.value || '';
		output.textContent =
			'[meetbly handle="' + handle + '" service="' + service + '"]';
	}

	document.addEventListener( 'DOMContentLoaded', function () {
		var handleInput = document.getElementById( 'meetbly_default_handle' );
		var serviceInput = document.getElementById( 'meetbly_default_service' );
		var copyBtn = document.getElementById( 'meetbly-copy-shortcode' );
		var output = document.getElementById( 'meetbly-shortcode-output' );
		var labels = window.meetblyAdmin || {};

		if ( handleInput ) {
			handleInput.addEventListener( 'input', updateShortcodePreview );
		}
		if ( serviceInput ) {
			serviceInput.addEventListener( 'input', updateShortcodePreview );
		}

		if ( copyBtn && output ) {
			copyBtn.addEventListener( 'click', function () {
				var text = output.textContent || '';

				function onCopied() {
					var original = copyBtn.textContent;
					copyBtn.textContent = labels.copiedLabel || 'Copied!';
					window.setTimeout( function () {
						copyBtn.textContent = labels.copyLabel || original;
					}, 2000 );
				}

				if ( navigator.clipboard && navigator.clipboard.writeText ) {
					navigator.clipboard.writeText( text ).then( onCopied ).catch( function () {
						window.prompt( labels.copyLabel || 'Copy shortcode', text );
					} );
				} else {
					window.prompt( labels.copyLabel || 'Copy shortcode', text );
					onCopied();
				}
			} );
		}
	} );
} )();