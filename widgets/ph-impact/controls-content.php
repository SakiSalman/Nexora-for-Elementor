<?php
/**
 * Content controls for PH impact.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_impact_register_content_controls' ) ) {
	/**
	 * Register content controls. Defaults are the original design copy.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_impact_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base || ! class_exists( Controls_Manager::class ) ) {
			return;
		}

		if ( ! function_exists( 'nexora_ph_impact_default_tabs' ) ) {
			require_once __DIR__ . '/data.php';
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
		nexora_ph_register_mapped_controls( $widget, 'impact' );

		if ( ! class_exists( Repeater::class ) ) {
			return;
		}

		$widget->start_controls_section(
			'section_impact_tabs',
			[
				'label' => esc_html__( 'Tabs', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$tabs = new Repeater();
		$default_image = [
			'url' => nexora_ph_impact_default_image_url(),
			'id'  => '',
		];

		$tabs->add_control(
			'tab_label',
			[
				'label'       => esc_html__( 'Tab label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Tab button and image badge.', 'nexora-elementor' ),
			]
		);

		$tabs->add_control(
			'tab_title',
			[
				'label'       => esc_html__( 'Title', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$tabs->add_control(
			'tab_body',
			[
				'label' => esc_html__( 'Body', 'nexora-elementor' ),
				'type'  => Controls_Manager::TEXTAREA,
				'default' => '',
				'rows'  => 4,
			]
		);

		$tabs->add_control(
			'tab_image',
			[
				'label'   => esc_html__( 'Image', 'nexora-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => $default_image,
			]
		);

		$tabs->add_control(
			'tab_alt',
			[
				'label'       => esc_html__( 'Image alt', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$tabs->add_control(
			'tab_points',
			[
				'label'       => esc_html__( 'Points', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 6,
				'description' => esc_html__( 'One point per line.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'impact_tabs',
			[
				'label'       => esc_html__( 'Tabs', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $tabs->get_controls(),
				'default'     => nexora_ph_impact_default_tabs(),
				'title_field' => '{{{ tab_label }}}',
			]
		);

		$widget->end_controls_section();
	}
}
