<?php
/**
 * Main plugin loader.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Singleton plugin bootstrap.
 */
class Meetbly_Booking {

	/**
	 * Plugin instance.
	 *
	 * @var Meetbly_Booking|null
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return Meetbly_Booking
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->includes();
		$this->hooks();
	}

	/**
	 * Load class files.
	 */
	private function includes() {
		require_once MEETBLY_PLUGIN_DIR . 'includes/class-meetbly-settings.php';
		require_once MEETBLY_PLUGIN_DIR . 'includes/class-meetbly-render.php';
		require_once MEETBLY_PLUGIN_DIR . 'includes/class-meetbly-shortcode.php';
		require_once MEETBLY_PLUGIN_DIR . 'includes/class-meetbly-block.php';
	}

	/**
	 * Register hooks.
	 */
	private function hooks() {
		add_action( 'init', array( $this, 'load_textdomain' ) );
		add_action( 'init', array( $this, 'init_components' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_frontend_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_frontend_assets' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );

		add_action(
			'elementor/loaded',
			function () {
				require_once MEETBLY_PLUGIN_DIR . 'includes/class-meetbly-elementor.php';
				Meetbly_Elementor::init();
			}
		);
	}

	/**
	 * Load plugin text domain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'meetbly-booking',
			false,
			dirname( plugin_basename( MEETBLY_PLUGIN_FILE ) ) . '/languages'
		);
	}

	/**
	 * Initialize settings, shortcode, and block.
	 */
	public function init_components() {
		Meetbly_Settings::init();
		Meetbly_Shortcode::init();
		Meetbly_Block::init();
	}

	/**
	 * Register frontend scripts and styles (not enqueued globally).
	 */
	public function register_frontend_assets() {
		wp_register_style(
			'meetbly-frontend',
			MEETBLY_PLUGIN_URL . 'assets/css/meetbly-frontend.css',
			array(),
			MEETBLY_VERSION
		);

		wp_register_script(
			'meetbly-frontend',
			MEETBLY_PLUGIN_URL . 'assets/js/meetbly-frontend.js',
			array(),
			MEETBLY_VERSION,
			true
		);

		$embed_origin = apply_filters( 'meetbly_embed_origin', 'https://meetbly.com' );

		wp_localize_script(
			'meetbly-frontend',
			'meetblyBooking',
			array(
				'embedOrigin' => esc_url_raw( $embed_origin ),
				'maxHeight'   => 20000,
			)
		);
	}

	/**
	 * Enqueue frontend assets when a widget rendered on this request.
	 */
	public function maybe_enqueue_frontend_assets() {
		if ( Meetbly_Render::should_enqueue_assets() ) {
			wp_enqueue_style( 'meetbly-frontend' );
			wp_enqueue_script( 'meetbly-frontend' );
		}
	}

	/**
	 * Register admin assets.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function register_admin_assets( $hook_suffix ) {
		if ( 'toplevel_page_meetbly-booking' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'meetbly-admin',
			MEETBLY_PLUGIN_URL . 'assets/css/meetbly-admin.css',
			array(),
			MEETBLY_VERSION
		);

		wp_enqueue_script(
			'meetbly-admin',
			MEETBLY_PLUGIN_URL . 'assets/js/meetbly-admin.js',
			array(),
			MEETBLY_VERSION,
			true
		);

		wp_localize_script(
			'meetbly-admin',
			'meetblyAdmin',
			array(
				'copiedLabel' => esc_html__( 'Copied!', 'meetbly-booking' ),
				'copyLabel'   => esc_html__( 'Copy shortcode', 'meetbly-booking' ),
			)
		);

		Meetbly_Render::mark_enqueue_assets();
		wp_enqueue_style( 'meetbly-frontend' );
		wp_enqueue_script( 'meetbly-frontend' );
	}
}