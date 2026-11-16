<?php
/**
 * Plugin Name:       Meetbly Booking: Appointment Scheduling & Booking Page
 * Plugin URI:        https://meetbly.com/wordpress
 * Description:       Embed your Meetbly service booking widget with real-time availability using a block, shortcode, or Elementor widget. Visitors book appointments without leaving your site.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Nexiby LLC
 * Author URI:        https://nexiby.com
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       meetbly-booking
 * Domain Path:       /languages
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'MEETBLY_VERSION', '1.0.0' );
define( 'MEETBLY_PLUGIN_FILE', __FILE__ );
define( 'MEETBLY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MEETBLY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MEETBLY_EMBED_BASE', 'https://meetbly.com/embed/' );

require_once MEETBLY_PLUGIN_DIR . 'includes/class-meetbly-booking.php';

/**
 * Bootstrap the plugin.
 *
 * @return Meetbly_Booking
 */
function meetbly_booking() {
	return Meetbly_Booking::instance();
}

meetbly_booking();