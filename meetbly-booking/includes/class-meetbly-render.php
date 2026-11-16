<?php
/**
 * Embed markup renderer.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Builds shared embed HTML for block, shortcode, and Elementor.
 */
class Meetbly_Render {

	/**
	 * Whether to enqueue frontend assets on this request.
	 *
	 * @var bool
	 */
	private static $enqueue_assets = false;

	/**
	 * Slug validation pattern.
	 */
	const SLUG_PATTERN = '/^[a-zA-Z0-9_-]+$/';

	/**
	 * Mark that frontend assets should load.
	 */
	public static function mark_enqueue_assets() {
		self::$enqueue_assets = true;
	}

	/**
	 * Whether frontend assets should be enqueued.
	 *
	 * @return bool
	 */
	public static function should_enqueue_assets() {
		return self::$enqueue_assets;
	}

	/**
	 * Get settings with defaults.
	 *
	 * @return array<string, mixed>
	 */
	public static function get_settings() {
		$defaults = array(
			'default_handle'        => '',
			'default_service'       => '',
			'min_height'            => 600,
			'max_width'             => '100%',
			'align'                 => 'center',
			'consent_note'          => '',
			'privacy_url'           => '',
			'delete_on_uninstall'   => false,
		);

		$stored = get_option( 'meetbly_booking_settings', array() );
		if ( ! is_array( $stored ) ) {
			$stored = array();
		}

		return wp_parse_args( $stored, $defaults );
	}

	/**
	 * Validate handle or service slug.
	 *
	 * @param string $value Value to validate.
	 * @return bool
	 */
	public static function is_valid_slug( $value ) {
		return is_string( $value ) && '' !== $value && (bool) preg_match( self::SLUG_PATTERN, $value );
	}

	/**
	 * Sanitize alignment value.
	 *
	 * @param string $align Alignment.
	 * @return string
	 */
	public static function sanitize_align( $align ) {
		$allowed = array( 'left', 'center', 'right' );
		return in_array( $align, $allowed, true ) ? $align : 'center';
	}

	/**
	 * Sanitize max width (px or %).
	 *
	 * @param string $max_width Max width value.
	 * @return string
	 */
	public static function sanitize_max_width( $max_width ) {
		$max_width = trim( (string) $max_width );
		if ( '' === $max_width ) {
			return '100%';
		}
		if ( preg_match( '/^\d+(\.\d+)?%$/', $max_width ) ) {
			return $max_width;
		}
		if ( preg_match( '/^\d+(\.\d+)?px$/i', $max_width ) ) {
			return strtolower( $max_width );
		}
		return '100%';
	}

	/**
	 * Build embed URL for handle and service.
	 *
	 * @param string $handle Provider handle.
	 * @param string $service Service slug.
	 * @return string
	 */
	public static function build_embed_url( $handle, $service ) {
		$path = rawurlencode( $handle ) . '/' . rawurlencode( $service );
		return MEETBLY_EMBED_BASE . $path;
	}

	/**
	 * Render the embed markup.
	 *
	 * @param array<string, mixed> $args Embed arguments.
	 * @return string
	 */
	public static function embed( $args = array() ) {
		$settings = self::get_settings();

		$handle = isset( $args['handle'] ) ? sanitize_text_field( (string) $args['handle'] ) : '';
		$service = isset( $args['service'] ) ? sanitize_text_field( (string) $args['service'] ) : '';

		if ( '' === $handle ) {
			$handle = sanitize_text_field( (string) $settings['default_handle'] );
		}
		if ( '' === $service ) {
			$service = sanitize_text_field( (string) $settings['default_service'] );
		}

		$min_height = isset( $args['min_height'] ) ? absint( $args['min_height'] ) : 0;
		if ( $min_height < 1 ) {
			$min_height = absint( $settings['min_height'] );
		}
		if ( $min_height < 1 ) {
			$min_height = 600;
		}

		$max_width = isset( $args['max_width'] ) ? self::sanitize_max_width( (string) $args['max_width'] ) : '';
		if ( '' === $max_width ) {
			$max_width = self::sanitize_max_width( (string) $settings['max_width'] );
		}

		$align = isset( $args['align'] ) ? self::sanitize_align( sanitize_key( (string) $args['align'] ) ) : '';
		if ( '' === $align ) {
			$align = self::sanitize_align( sanitize_key( (string) $settings['align'] ) );
		}

		$show_consent = ! empty( $args['show_consent'] );
		$consent_note = $show_consent ? (string) $settings['consent_note'] : '';
		$privacy_url  = $show_consent ? (string) $settings['privacy_url'] : '';

		if ( ! self::is_valid_slug( $handle ) || ! self::is_valid_slug( $service ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				return '<p class="meetbly-booking-notice" role="status">' . esc_html__( 'Set a Meetbly handle and service.', 'meetbly-booking' ) . '</p>';
			}
			return '';
		}

		self::mark_enqueue_assets();

		$embed_url = self::build_embed_url( $handle, $service );
		$iframe_id = 'meetbly-embed-' . sanitize_key( $service ) . '-' . wp_unique_id();

		$container_class = 'meetbly-booking-widget meetbly-booking-widget--align-' . esc_attr( $align );

		$iframe_style = sprintf(
			'width:100%%;border:none;min-height:%dpx;',
			$min_height
		);

		$output  = '<div class="' . esc_attr( $container_class ) . '" style="max-width:' . esc_attr( $max_width ) . ';">';
		$output .= '<iframe';
		$output .= ' id="' . esc_attr( $iframe_id ) . '"';
		$output .= ' class="meetbly-booking-embed"';
		$output .= ' src="' . esc_url( $embed_url ) . '"';
		$output .= ' title="' . esc_attr__( 'Meetbly booking widget', 'meetbly-booking' ) . '"';
		$output .= ' loading="lazy"';
		$output .= ' referrerpolicy="strict-origin-when-cross-origin"';
		$output .= ' style="' . esc_attr( $iframe_style ) . '"';
		$output .= '></iframe>';

		if ( '' !== trim( $consent_note ) ) {
			$output .= '<p class="meetbly-booking-consent">';
			$output .= esc_html( $consent_note );
			$privacy_url = esc_url_raw( $privacy_url );
			if ( '' !== $privacy_url ) {
				$output .= ' <a href="' . esc_url( $privacy_url ) . '">' . esc_html__( 'Privacy Policy', 'meetbly-booking' ) . '</a>';
			}
			$output .= '</p>';
		}

		$output .= '</div>';

		return $output;
	}
}