<?php
/**
 * Admin settings screen.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings page and option handling.
 */
class Meetbly_Settings {

	/**
	 * Option name.
	 */
	const OPTION_NAME = 'meetbly_booking_settings';

	/**
	 * Register hooks.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Add top-level admin menu.
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'Meetbly Booking', 'meetbly-booking' ),
			__( 'Meetbly Booking', 'meetbly-booking' ),
			'manage_options',
			'meetbly-booking',
			array( __CLASS__, 'render_page' ),
			MEETBLY_PLUGIN_URL . 'assets/icon.svg',
			58
		);
	}

	/**
	 * Register Settings API option.
	 */
	public static function register_settings() {
		register_setting(
			'meetbly_booking',
			self::OPTION_NAME,
			array(
				'type'              => 'array',
				'sanitize_callback' => array( __CLASS__, 'sanitize_settings' ),
				'default'           => Meetbly_Render::get_settings(),
			)
		);
	}

	/**
	 * Sanitize settings on save.
	 *
	 * @param array<string, mixed>|mixed $input Raw input.
	 * @return array<string, mixed>
	 */
	public static function sanitize_settings( $input ) {
		if ( ! is_array( $input ) ) {
			$input = array();
		}

		$clean = Meetbly_Render::get_settings();

		if ( isset( $input['default_handle'] ) ) {
			$handle = sanitize_text_field( (string) $input['default_handle'] );
			$clean['default_handle'] = Meetbly_Render::is_valid_slug( $handle ) || '' === $handle ? $handle : '';
		}

		if ( isset( $input['default_service'] ) ) {
			$service = sanitize_text_field( (string) $input['default_service'] );
			$clean['default_service'] = Meetbly_Render::is_valid_slug( $service ) || '' === $service ? $service : '';
		}

		if ( isset( $input['min_height'] ) ) {
			$clean['min_height'] = max( 100, absint( $input['min_height'] ) );
		}

		if ( isset( $input['max_width'] ) ) {
			$clean['max_width'] = Meetbly_Render::sanitize_max_width( (string) $input['max_width'] );
		}

		if ( isset( $input['align'] ) ) {
			$clean['align'] = Meetbly_Render::sanitize_align( sanitize_key( (string) $input['align'] ) );
		}

		if ( isset( $input['consent_note'] ) ) {
			$clean['consent_note'] = sanitize_textarea_field( (string) $input['consent_note'] );
		}

		if ( isset( $input['privacy_url'] ) ) {
			$clean['privacy_url'] = esc_url_raw( (string) $input['privacy_url'] );
		}

		$clean['delete_on_uninstall'] = ! empty( $input['delete_on_uninstall'] );

		return $clean;
	}

	/**
	 * Render settings page.
	 */
	public static function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = Meetbly_Render::get_settings();
		?>
		<div class="wrap meetbly-admin-wrap">
			<h1><?php echo esc_html__( 'Meetbly Booking', 'meetbly-booking' ); ?></h1>

			<div class="meetbly-admin-grid">
				<div class="meetbly-admin-main">
					<form method="post" action="options.php">
						<?php settings_fields( 'meetbly_booking' ); ?>

						<table class="form-table" role="presentation">
							<tr>
								<th scope="row">
									<label for="meetbly_default_handle"><?php esc_html_e( 'Default provider handle', 'meetbly-booking' ); ?></label>
								</th>
								<td>
									<input name="<?php echo esc_attr( self::OPTION_NAME ); ?>[default_handle]" type="text" id="meetbly_default_handle" value="<?php echo esc_attr( $settings['default_handle'] ); ?>" class="regular-text" autocomplete="off" />
									<p class="description"><?php esc_html_e( 'Your Meetbly @handle, e.g. nexiby.', 'meetbly-booking' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="meetbly_default_service"><?php esc_html_e( 'Default service slug', 'meetbly-booking' ); ?></label>
								</th>
								<td>
									<input name="<?php echo esc_attr( self::OPTION_NAME ); ?>[default_service]" type="text" id="meetbly_default_service" value="<?php echo esc_attr( $settings['default_service'] ); ?>" class="regular-text" autocomplete="off" />
									<p class="description"><?php esc_html_e( 'The service public link slug, e.g. test.', 'meetbly-booking' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="meetbly_min_height"><?php esc_html_e( 'Default min height (px)', 'meetbly-booking' ); ?></label>
								</th>
								<td>
									<input name="<?php echo esc_attr( self::OPTION_NAME ); ?>[min_height]" type="number" id="meetbly_min_height" value="<?php echo esc_attr( (string) $settings['min_height'] ); ?>" min="100" step="1" class="small-text" />
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="meetbly_max_width"><?php esc_html_e( 'Default max width', 'meetbly-booking' ); ?></label>
								</th>
								<td>
									<input name="<?php echo esc_attr( self::OPTION_NAME ); ?>[max_width]" type="text" id="meetbly_max_width" value="<?php echo esc_attr( $settings['max_width'] ); ?>" class="regular-text" />
									<p class="description"><?php esc_html_e( 'Pixels (e.g. 800px) or percentage (e.g. 100%).', 'meetbly-booking' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Default alignment', 'meetbly-booking' ); ?></th>
								<td>
									<fieldset>
										<label>
											<input type="radio" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[align]" value="left" <?php checked( $settings['align'], 'left' ); ?> />
											<?php esc_html_e( 'Left', 'meetbly-booking' ); ?>
										</label><br />
										<label>
											<input type="radio" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[align]" value="center" <?php checked( $settings['align'], 'center' ); ?> />
											<?php esc_html_e( 'Center', 'meetbly-booking' ); ?>
										</label><br />
										<label>
											<input type="radio" name="<?php echo esc_attr( self::OPTION_NAME ); ?>[align]" value="right" <?php checked( $settings['align'], 'right' ); ?> />
											<?php esc_html_e( 'Right', 'meetbly-booking' ); ?>
										</label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="meetbly_consent_note"><?php esc_html_e( 'Consent note (optional)', 'meetbly-booking' ); ?></label>
								</th>
								<td>
									<textarea name="<?php echo esc_attr( self::OPTION_NAME ); ?>[consent_note]" id="meetbly_consent_note" rows="3" class="large-text"><?php echo esc_textarea( $settings['consent_note'] ); ?></textarea>
									<p class="description"><?php esc_html_e( 'Shown below the widget when enabled on a block or widget.', 'meetbly-booking' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="meetbly_privacy_url"><?php esc_html_e( 'Privacy policy URL', 'meetbly-booking' ); ?></label>
								</th>
								<td>
									<input name="<?php echo esc_attr( self::OPTION_NAME ); ?>[privacy_url]" type="url" id="meetbly_privacy_url" value="<?php echo esc_attr( $settings['privacy_url'] ); ?>" class="regular-text" />
								</td>
							</tr>
							<tr>
								<th scope="row"><?php esc_html_e( 'Uninstall data', 'meetbly-booking' ); ?></th>
								<td>
									<label for="meetbly_delete_on_uninstall">
										<input name="<?php echo esc_attr( self::OPTION_NAME ); ?>[delete_on_uninstall]" type="checkbox" id="meetbly_delete_on_uninstall" value="1" <?php checked( ! empty( $settings['delete_on_uninstall'] ) ); ?> />
										<?php esc_html_e( 'Delete all plugin data on uninstall', 'meetbly-booking' ); ?>
									</label>
									<p class="description"><?php esc_html_e( 'When unchecked, your settings are preserved (recommended).', 'meetbly-booking' ); ?></p>
								</td>
							</tr>
						</table>

						<?php submit_button(); ?>
					</form>
				</div>

				<div class="meetbly-admin-sidebar">
					<div class="meetbly-panel meetbly-panel-help">
						<h2><?php esc_html_e( 'Allow your domain in Meetbly', 'meetbly-booking' ); ?></h2>
						<p><?php esc_html_e( 'The embed only renders on domains you add under Manage Allowed Domains in Meetbly. Without this step, the widget may appear blank on your site.', 'meetbly-booking' ); ?></p>
						<p>
							<a class="button button-secondary" href="https://meetbly.com" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Open Meetbly', 'meetbly-booking' ); ?>
							</a>
						</p>
						<p class="meetbly-connect-note">
							<a href="https://meetbly.com/wordpress" target="_blank" rel="noopener noreferrer">
								<?php esc_html_e( 'Connect your Meetbly account', 'meetbly-booking' ); ?>
							</a>
						</p>
					</div>

					<div class="meetbly-panel meetbly-panel-preview">
						<h2><?php esc_html_e( 'Preview', 'meetbly-booking' ); ?></h2>
						<div id="meetbly-admin-preview">
							<?php
							echo Meetbly_Render::embed(
								array(
									'show_consent' => true,
								)
							); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
						<p class="meetbly-shortcode-row">
							<code id="meetbly-shortcode-output">[meetbly handle="<?php echo esc_attr( $settings['default_handle'] ); ?>" service="<?php echo esc_attr( $settings['default_service'] ); ?>"]</code>
							<button type="button" class="button" id="meetbly-copy-shortcode"><?php esc_html_e( 'Copy shortcode', 'meetbly-booking' ); ?></button>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}