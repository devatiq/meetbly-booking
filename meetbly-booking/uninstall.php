<?php
/**
 * Uninstall cleanup.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete plugin data for one site when the user opted in.
 */
function meetbly_booking_uninstall_site() {
	$settings = get_option( 'meetbly_booking_settings', array() );
	if ( ! is_array( $settings ) ) {
		$settings = array();
	}

	$delete = ! empty( $settings['delete_on_uninstall'] );
	if ( ! $delete ) {
		return;
	}

	delete_option( 'meetbly_booking_settings' );

	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
			$wpdb->esc_like( '_transient_meetbly_' ) . '%',
			$wpdb->esc_like( '_transient_timeout_meetbly_' ) . '%'
		)
	);
}

if ( is_multisite() ) {
	$site_ids = get_sites(
		array(
			'fields' => 'ids',
			'number' => 0,
		)
	);

	foreach ( $site_ids as $site_id ) {
		switch_to_blog( (int) $site_id );
		meetbly_booking_uninstall_site();
		restore_current_blog();
	}
} else {
	meetbly_booking_uninstall_site();
}