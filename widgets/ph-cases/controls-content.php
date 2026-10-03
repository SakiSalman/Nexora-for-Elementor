<?php
/**
 * Content controls for PH cases.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_cases_register_content_controls' ) ) {
	/**
	 * Register content controls. Defaults are the original design copy.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_cases_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base || ! class_exists( Controls_Manager::class ) ) {
			return;
		}

		if ( ! function_exists( 'nexora_ph_cases_default_slides' ) ) {
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
		nexora_ph_register_mapped_controls( $widget, 'cases' );

		if ( ! class_exists( Repeater::class ) ) {
			return;
		}

		$widget->start_controls_section(
			'section_cases_slides',
			[
				'label' => esc_html__( 'Slides', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$slides = new Repeater();

		$slides->add_control(
			'case_title',
			[
				'label'       => esc_html__( 'Title', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$slides->add_control(
			'case_summary',
			[
				'label'   => esc_html__( 'Summary', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'rows'    => 4,
			]
		);

		$slides->add_control(
			'case_client',
			[
				'label'       => esc_html__( 'Client', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$slides->add_control(
			'case_industry',
			[
				'label'       => esc_html__( 'Industry', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$slides->add_control(
			'case_services',
			[
				'label'       => esc_html__( 'Services', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$slides->add_control(
			'case_image',
			[
				'label'   => esc_html__( 'Image', 'nexora-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => nexora_ph_cases_default_image_url( 'case1.webp' ),
					'id'  => '',
				],
			]
		);

		$slides->add_control(
			'case_alt',
			[
				'label'       => esc_html__( 'Image alt', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$slides->add_control(
			'case_button_text',
			[
				'label'       => esc_html__( 'Button text', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$slides->add_control(
			'case_button_url',
			[
				'label'       => esc_html__( 'Button link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$slides->add_control(
			'case_stats',
			[
				'label'       => esc_html__( 'Stats', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 5,
				'description' => esc_html__( 'One stat per line as value | label.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'cases_slides',
			[
				'label'       => esc_html__( 'Slides', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $slides->get_controls(),
				'default'     => nexora_ph_cases_default_slides(),
				'title_field' => '{{{ case_title }}}',
			]
		);

		$widget->end_controls_section();
	}
}
