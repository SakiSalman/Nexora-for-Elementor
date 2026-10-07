<?php
/**
 * Content controls for PH footer.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

if ( ! function_exists( 'nexora_ph_footer_register_content_controls' ) ) {
	/**
	 * Register content controls. Defaults are the original design copy.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_footer_register_content_controls( $widget ): void {
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

		if ( ! function_exists( 'nexora_ph_register_mapped_controls' ) ) {
			require_once NEXORA_ELE_PATH . 'includes/ph-content.php';
		}
		nexora_ph_register_mapped_controls( $widget, 'footer' );

		$widget->start_controls_section(
			'section_newsletter_form',
			[
				'label' => esc_html__( 'Newsletter form', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'footer_newsletter_shortcode',
			[
				'label'       => esc_html__( 'Form shortcode', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '[contact-form-7 id="151" title="Untitled"]',
				'rows'        => 3,
				'label_block' => true,
				'description' => esc_html__( 'Paste a Contact Form 7 shortcode. It replaces the email field and Subscribe button only.', 'nexora-elementor' ),
				'placeholder' => '[contact-form-7 id="151" title="Untitled"]',
			]
		);

		$widget->end_controls_section();
	}
}
