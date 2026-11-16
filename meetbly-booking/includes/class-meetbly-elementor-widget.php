<?php
/**
 * Elementor widget class.
 *
 * @package MeetblyBooking
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meetbly Booking Elementor widget.
 */
class Meetbly_Elementor_Widget extends \Elementor\Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'meetbly_booking';
	}

	/**
	 * Widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Meetbly Booking', 'meetbly-booking' );
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-calendar';
	}

	/**
	 * Widget categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'meetbly', 'booking', 'appointments', 'calendar' );
	}

	/**
	 * Register controls.
	 */
	protected function register_controls() {
		$settings = Meetbly_Render::get_settings();

		$this->start_controls_section(
			'section_content',
			array(
				'label' => esc_html__( 'Meetbly', 'meetbly-booking' ),
			)
		);

		$this->add_control(
			'handle',
			array(
				'label'       => esc_html__( 'Provider handle', 'meetbly-booking' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $settings['default_handle'],
				'placeholder' => 'nexiby',
			)
		);

		$this->add_control(
			'service',
			array(
				'label'       => esc_html__( 'Service slug', 'meetbly-booking' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => $settings['default_service'],
				'placeholder' => 'test',
			)
		);

		$this->add_control(
			'min_height',
			array(
				'label'   => esc_html__( 'Min height (px)', 'meetbly-booking' ),
				'type'    => \Elementor\Controls_Manager::NUMBER,
				'default' => (int) $settings['min_height'],
				'min'     => 100,
			)
		);

		$this->add_control(
			'max_width',
			array(
				'label'   => esc_html__( 'Max width', 'meetbly-booking' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => $settings['max_width'],
			)
		);

		$this->add_control(
			'align',
			array(
				'label'   => esc_html__( 'Alignment', 'meetbly-booking' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => $settings['align'],
				'options' => array(
					'left'   => esc_html__( 'Left', 'meetbly-booking' ),
					'center' => esc_html__( 'Center', 'meetbly-booking' ),
					'right'  => esc_html__( 'Right', 'meetbly-booking' ),
				),
			)
		);

		$this->add_control(
			'show_consent',
			array(
				'label'        => esc_html__( 'Show consent note', 'meetbly-booking' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'meetbly-booking' ),
				'label_off'    => esc_html__( 'No', 'meetbly-booking' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$args = array(
			'handle'     => isset( $settings['handle'] ) ? (string) $settings['handle'] : '',
			'service'    => isset( $settings['service'] ) ? (string) $settings['service'] : '',
			'min_height' => isset( $settings['min_height'] ) ? absint( $settings['min_height'] ) : 0,
			'max_width'  => isset( $settings['max_width'] ) ? (string) $settings['max_width'] : '',
			'align'      => isset( $settings['align'] ) ? (string) $settings['align'] : '',
		);

		if ( ! empty( $settings['show_consent'] ) && 'yes' === $settings['show_consent'] ) {
			$args['show_consent'] = true;
		}

		echo Meetbly_Render::embed( $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}