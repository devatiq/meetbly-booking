<?php
/**
 * Elementor widget integration.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers Elementor widget when Elementor is active.
 */
class Meetbly_Elementor {

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widget' ) );
	}

	/**
	 * Register widget class.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 */
	public static function register_widget( $widgets_manager ) {
		require_once MEETBLY_PLUGIN_DIR . 'includes/class-meetbly-elementor-widget.php';
		$widgets_manager->register( new Meetbly_Elementor_Widget() );
	}
}