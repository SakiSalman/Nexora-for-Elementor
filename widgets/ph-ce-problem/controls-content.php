<?php
/**
 * Content controls for CE Problem. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_problem_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_problem_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_problem_defaults();

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
			'section_problem_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'problem_heading_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'p'    => 'p',
					'div'  => 'div',
					'span' => 'span',
				),
				'default' => $ce_d['heading_tag'],
			]
		);

		$widget->add_control(
			'problem_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'problem_heading',
			[
				'label'   => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['heading'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'problem_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient inside the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'problem_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['description'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_problem_chart',
			[
				'label' => esc_html__( 'Leak chart', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'problem_chart_label',
			[
				'label'   => esc_html__( 'Chart label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['chart_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'problem_leaks_label',
			[
				'label'   => esc_html__( 'Leaks label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['leaks_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'problem_footnote',
			[
				'label'   => esc_html__( 'Chart footnote', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['footnote'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_problem_rows',
			[
				'label' => esc_html__( 'Timeline steps', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$rows_repeater = new Repeater();

		$rows_repeater->add_control(
			'problem_num',
			[
				'label'   => esc_html__( 'Number', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$rows_repeater->add_control(
			'problem_title',
			[
				'label'   => esc_html__( 'Title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$rows_repeater->add_control(
			'problem_body',
			[
				'label'   => esc_html__( 'Body', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'problem_rows',
			[
				'label'       => esc_html__( 'Timeline steps', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $rows_repeater->get_controls(),
				'default'     => $ce_d['rows'],
				'title_field' => 'problem_title',
				'render_type' => 'template',
			]
		);

		$widget->end_controls_section();
	}
}
