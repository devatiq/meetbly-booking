<?php
/**
 * Shortcode handler.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers [meetbly] shortcode.
 */
class Meetbly_Shortcode {

	/**
	 * Register shortcode.
	 */
	public static function init() {
		add_shortcode( 'meetbly', array( __CLASS__, 'render' ) );
	}

	/**
	 * Render shortcode output.
	 *
	 * @param array<string, string>|string $atts Shortcode attributes.
	 * @return string
	 */
	public static function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'handle'     => '',
				'service'    => '',
				'min_height' => '',
				'max_width'  => '',
				'align'      => '',
			),
			$atts,
			'meetbly'
		);

		$args = array(
			'handle'  => $atts['handle'],
			'service' => $atts['service'],
			'align'   => $atts['align'],
		);

		if ( '' !== $atts['min_height'] ) {
			$args['min_height'] = absint( $atts['min_height'] );
		}
		if ( '' !== $atts['max_width'] ) {
			$args['max_width'] = $atts['max_width'];
		}

		return Meetbly_Render::embed( $args );
	}
}