<?php
/**
 * Content controls for CE Outlook. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_outlook_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_outlook_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_outlook_defaults();

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
			'section_outlook_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'outlook_heading_tag',
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
			'outlook_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'outlook_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['heading'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text before the highlighted words.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'outlook_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient at the end of the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'outlook_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['description'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_outlook_chart',
			[
				'label' => esc_html__( 'Chart', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'outlook_chart_label',
			[
				'label'       => esc_html__( 'Chart label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['chart_label'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Small label above the chart.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'outlook_legend_text',
			[
				'label'       => esc_html__( 'Legend text', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['legend_text'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text next to the legend dot.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_outlook_phases',
			[
				'label' => esc_html__( 'Phases', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$phases = new Repeater();

		$phases->add_control(
			'outlook_bar_label',
			[
				'label'       => esc_html__( 'Bar label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['phases'][0]['outlook_bar_label'],
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Week range shown inside the bar, e.g. Weeks 1-2.', 'nexora-elementor' ),
			]
		);

		$phases->add_control(
			'outlook_bar_height',
			[
				'label'       => esc_html__( 'Bar height %', 'nexora-elementor' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => $ce_d['phases'][0]['outlook_bar_height'],
				'min'         => 0,
				'max'         => 100,
				'step'        => 1,
				'description' => esc_html__( 'Bar height as a percentage of the chart.', 'nexora-elementor' ),
			]
		);

		$phases->add_control(
			'outlook_title_tag',
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
				'default' => $ce_d['phases'][0]['outlook_title_tag'],
			]
		);

		$phases->add_control(
			'outlook_title',
			[
				'label'       => esc_html__( 'Title', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['phases'][0]['outlook_title'],
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			]
		);

		$phases->add_control(
			'outlook_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['phases'][0]['outlook_description'],
				'rows'    => 4,
				'dynamic' => array( 'active' => true ),
			]
		);

		$phases->add_control(
			'outlook_phase_on',
			[
				'label'        => esc_html__( 'Highlight this phase', 'nexora-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'description'  => esc_html__( 'Marks the phase as active in the design.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'outlook_phases',
			[
				'label'         => esc_html__( 'Phases', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $phases->get_controls(),
				'default'       => $ce_d['phases'],
				'title_field'   => 'outlook_title',
				'prevent_empty' => false,
				'render_type'   => 'template',
			]
		);

		$widget->end_controls_section();
	}
}
