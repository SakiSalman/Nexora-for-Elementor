<?php
/**
 * Content controls for CE Pipeline. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_pipeline_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_pipeline_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_pipeline_defaults();

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
			'section_pipeline_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'pipeline_heading_tag',
			[
				'label'       => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'render_type' => 'template',
				'options'     => [
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
			'pipeline_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'pipeline_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['heading'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text before the highlighted words.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'pipeline_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient at the end of the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'pipeline_lead',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['lead'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'pipeline_tablist_label',
			[
				'label'       => esc_html__( 'Stages accessible label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['tablist_label'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'aria-label on the row of stage tabs.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$repeater = new Repeater();

		$repeater->add_control(
			'pipeline_stage_label',
			[
				'label'   => esc_html__( 'Stage label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$repeater->add_control(
			'pipeline_stage_sub',
			[
				'label'   => esc_html__( 'Stage sub-label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$repeater->add_control(
			'pipeline_stage_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'4c823d4f7b' => esc_html__( 'Targeting', 'nexora-elementor' ),
					'65b3d804f3' => esc_html__( 'Deliverability', 'nexora-elementor' ),
					'd4db34a0d0' => esc_html__( 'Relevance', 'nexora-elementor' ),
					'2e54581cff' => esc_html__( 'Qualification', 'nexora-elementor' ),
					'e7153d2b23' => esc_html__( 'Scale', 'nexora-elementor' ),
				],
				'default' => $ce_d['stages'][0]['pipeline_stage_icon'],
			]
		);

		$repeater->add_control(
			'pipeline_stage_title',
			[
				'label'   => esc_html__( 'Title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$repeater->add_control(
			'pipeline_stage_body',
			[
				'label'   => esc_html__( 'Body', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => array( 'active' => true ),
			]
		);

		$repeater->add_control(
			'pipeline_stage_points',
			[
				'label'       => esc_html__( 'Points', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'One point per line.', 'nexora-elementor' ),
			]
		);

		$widget->start_controls_section(
			'section_pipeline_stages',
			[
				'label' => esc_html__( 'Stages', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'pipeline_stages',
			[
				'label'         => esc_html__( 'Stages', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'render_type'   => 'template',
				'fields'        => $repeater->get_controls(),
				'default'       => $ce_d['stages'],
				'title_field'   => 'pipeline_stage_label',
				'prevent_empty' => false,
				'min_items'     => 2,
				'max_items'     => 8,
			]
		);

		$widget->end_controls_section();
	}
}
