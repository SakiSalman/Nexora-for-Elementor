<?php
/**
 * Content controls for CE Why. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_why_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_why_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_why_defaults();

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

		$widget->start_controls_section(
			'section_why_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'why_heading_tag',
			[
				'label'       => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'render_type' => 'template',
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'p'    => 'p',
					'div'  => 'div',
					'span' => 'span',
				],
				'default' => $ce_d['heading_tag'],
			]
		);

		$widget->add_control(
			'why_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'why_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['heading'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text before the highlighted words.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'why_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient at the end of the heading.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$repeater = new Repeater();

		$repeater->add_control(
			'why_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1ab43d91f1' => esc_html__( 'High-fit prospect lists', 'nexora-elementor' ),
					'9e176b6b43' => esc_html__( 'Context-led personalization', 'nexora-elementor' ),
					'aea904a085' => esc_html__( 'Tools for every workflow', 'nexora-elementor' ),
					'893b566282' => esc_html__( 'Safer sending setup', 'nexora-elementor' ),
					'549f8e1641' => esc_html__( 'Email + LinkedIn follow-up', 'nexora-elementor' ),
					'5408056c57' => esc_html__( 'CRM-ready lead handoff', 'nexora-elementor' ),
				],
				'default' => $ce_d['rows'][0]['why_icon'],
			]
		);

		$repeater->add_control(
			'why_title',
			[
				'label'   => esc_html__( 'Title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$repeater->add_control(
			'why_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'p'    => 'p',
					'div'  => 'div',
					'span' => 'span',
				],
				'default' => $ce_d['row_tag'],
			]
		);

		$repeater->add_control(
			'why_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->start_controls_section(
			'section_why_reasons',
			[
				'label' => esc_html__( 'Reasons', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'why_rows',
			[
				'label'         => esc_html__( 'Reasons', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'render_type'   => 'template',
				'fields'        => $repeater->get_controls(),
				'default'       => $ce_d['rows'],
				'title_field'   => 'why_title',
				'prevent_empty' => false,
			]
		);

		$widget->end_controls_section();
	}
}
