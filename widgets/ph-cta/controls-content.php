<?php
/**
 * Content controls for PH Final CTA.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

if ( ! function_exists( 'nexora_ph_cta_register_content_controls' ) ) {
	/**
	 * Register the hidden schema control.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_cta_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		$widget->start_controls_section(
			'section_schema',
			[
				'label' => esc_html__( 'Schema', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'_schema_version',
			[
				'type'    => Controls_Manager::HIDDEN,
				'default' => '1',
			]
		);

		$widget->end_controls_section();
	}
}
