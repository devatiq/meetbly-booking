( function ( wp ) {
	var registerBlockType = wp.blocks.registerBlockType;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var PanelBody = wp.components.PanelBody;
	var TextControl = wp.components.TextControl;
	var SelectControl = wp.components.SelectControl;
	var ToggleControl = wp.components.ToggleControl;
	var ServerSideRender = wp.serverSideRender;
	var __ = wp.i18n.__;
	var el = wp.element.createElement;

	registerBlockType( 'meetbly/booking', {
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var blockProps = useBlockProps( {
				className: 'meetbly-booking-block-editor',
			} );

			return el(
				'div',
				blockProps,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Meetbly', 'meetbly-booking' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Provider handle', 'meetbly-booking' ),
							help: __( 'Falls back to the default in Meetbly Booking settings.', 'meetbly-booking' ),
							value: attributes.handle,
							onChange: function ( value ) {
								setAttributes( { handle: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Service slug', 'meetbly-booking' ),
							value: attributes.service,
							onChange: function ( value ) {
								setAttributes( { service: value } );
							},
						} ),
						el( TextControl, {
							label: __( 'Min height (px)', 'meetbly-booking' ),
							type: 'number',
							value: attributes.minHeight ? String( attributes.minHeight ) : '',
							onChange: function ( value ) {
								var parsed = parseInt( value, 10 );
								setAttributes( { minHeight: isNaN( parsed ) ? 0 : parsed } );
							},
						} ),
						el( TextControl, {
							label: __( 'Max width', 'meetbly-booking' ),
							help: __( 'e.g. 100% or 800px', 'meetbly-booking' ),
							value: attributes.maxWidth,
							onChange: function ( value ) {
								setAttributes( { maxWidth: value } );
							},
						} ),
						el( SelectControl, {
							label: __( 'Alignment', 'meetbly-booking' ),
							value: attributes.align || 'center',
							options: [
								{ label: __( 'Left', 'meetbly-booking' ), value: 'left' },
								{ label: __( 'Center', 'meetbly-booking' ), value: 'center' },
								{ label: __( 'Right', 'meetbly-booking' ), value: 'right' },
							],
							onChange: function ( value ) {
								setAttributes( { align: value } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Show consent note', 'meetbly-booking' ),
							checked: !! attributes.showConsent,
							onChange: function ( value ) {
								setAttributes( { showConsent: value } );
							},
						} )
					)
				),
				el( ServerSideRender, {
					block: 'meetbly/booking',
					attributes: attributes,
				} )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );