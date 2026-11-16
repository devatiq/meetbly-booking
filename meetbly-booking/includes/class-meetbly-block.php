<?php
/**
 * Block registration.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Server-rendered Meetbly block.
 */
class Meetbly_Block {

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_block' ) );
	}

	/**
	 * Register block type and editor assets.
	 */
	public static function register_block() {
		wp_register_script(
			'meetbly-booking-block-editor',
			MEETBLY_PLUGIN_URL . 'blocks/meetbly/index.js',
			array(
				'wp-blocks',
				'wp-element',
				'wp-block-editor',
				'wp-components',
				'wp-i18n',
				'wp-server-side-render',
			),
			MEETBLY_VERSION,
			true
		);

		wp_register_style(
			'meetbly-booking-block-editor',
			MEETBLY_PLUGIN_URL . 'blocks/meetbly/editor.css',
			array(),
			MEETBLY_VERSION
		);

		register_block_type(
			MEETBLY_PLUGIN_DIR . 'blocks/meetbly/block.json',
			array(
				'render_callback' => array( __CLASS__, 'render_block' ),
			)
		);
	}

	/**
	 * Server-side block render.
	 *
	 * @param array<string, mixed> $attributes Block attributes.
	 * @return string
	 */
	public static function render_block( $attributes ) {
		$args = array(
			'handle'  => isset( $attributes['handle'] ) ? (string) $attributes['handle'] : '',
			'service' => isset( $attributes['service'] ) ? (string) $attributes['service'] : '',
			'align'   => isset( $attributes['align'] ) ? (string) $attributes['align'] : '',
		);

		if ( isset( $attributes['minHeight'] ) && (int) $attributes['minHeight'] > 0 ) {
			$args['min_height'] = (int) $attributes['minHeight'];
		}
		if ( ! empty( $attributes['maxWidth'] ) ) {
			$args['max_width'] = (string) $attributes['maxWidth'];
		}
		if ( ! empty( $attributes['showConsent'] ) ) {
			$args['show_consent'] = true;
		}

		return Meetbly_Render::embed( $args );
	}
}