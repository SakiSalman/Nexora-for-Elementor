<?php
/**
 * Content controls for LI Signal. Phase 2: every visible string is editable;
 * defaults are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_li_signal_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_li_signal_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		require_once __DIR__ . '/defaults.php';

		$d = nexora_ph_li_signal_defaults();

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
			'section_li_signal_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'heading_tag',
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
				'default'     => $d['heading_tag'],
			]
		);

		$widget->add_control(
			'eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $d['heading'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text before the highlighted words.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient after the line break.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$groups = new Repeater();

		$groups->add_control(
			'signal_group',
			[
				'label'   => esc_html__( 'Group key', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'social'  => esc_html__( 'Social', 'nexora-elementor' ),
					'intent'  => esc_html__( 'Intent', 'nexora-elementor' ),
					'company' => esc_html__( 'Company', 'nexora-elementor' ),
				],
				'default' => 'social',
			]
		);

		$groups->add_control(
			'signal_column',
			[
				'label'   => esc_html__( 'Column', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'left'  => esc_html__( 'Left', 'nexora-elementor' ),
					'right' => esc_html__( 'Right', 'nexora-elementor' ),
				],
				'default' => 'left',
			]
		);

		$groups->add_control(
			'signal_title',
			[
				'label'   => esc_html__( 'Title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$groups->add_control(
			'signal_subtitle',
			[
				'label'   => esc_html__( 'Subtitle', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$groups->add_control(
			'signal_bullets',
			[
				'label'       => esc_html__( 'Bullets', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'One item per line.', 'nexora-elementor' ),
			]
		);

		$widget->start_controls_section(
			'section_li_signal_groups',
			[
				'label' => esc_html__( 'Signal groups', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'groups_rows',
			[
				'label'         => esc_html__( 'Signal groups', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'render_type'   => 'template',
				'fields'        => $groups->get_controls(),
				'default'       => $d['groups_rows'],
				'title_field'   => '{{{ signal_title }}}',
				'prevent_empty' => false,
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_li_signal_core',
			[
				'label' => esc_html__( 'Radar core', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'core_image',
			[
				'label'   => esc_html__( 'Image', 'nexora-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => $d['core_image'],
			]
		);

		$widget->add_control(
			'core_alt',
			[
				'label'   => esc_html__( 'Image alt', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $d['core_alt'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'core_label',
			[
				'label'   => esc_html__( 'Label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $d['core_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$node_icon_options = [
			'5f8e77c078' => esc_html__( 'Post engagement', 'nexora-elementor' ),
			'e655d91b96' => esc_html__( 'Founder post', 'nexora-elementor' ),
			'a071a2a0ca' => esc_html__( 'Topic post', 'nexora-elementor' ),
			'92c388bb23' => esc_html__( 'Profile view', 'nexora-elementor' ),
			'bcac496763' => esc_html__( 'Page visit', 'nexora-elementor' ),
			'019f941764' => esc_html__( 'Job switch', 'nexora-elementor' ),
			'065c801ad3' => esc_html__( 'Hiring', 'nexora-elementor' ),
			'54a57182a0' => esc_html__( 'Funding', 'nexora-elementor' ),
			'2dba82e8b3' => esc_html__( 'LinkedIn / reach out', 'nexora-elementor' ),
		];

		$nodes = new Repeater();

		$nodes->add_control(
			'node_group',
			[
				'label'   => esc_html__( 'Group key', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'social'  => esc_html__( 'Social', 'nexora-elementor' ),
					'intent'  => esc_html__( 'Intent', 'nexora-elementor' ),
					'company' => esc_html__( 'Company', 'nexora-elementor' ),
				],
				'default' => 'social',
			]
		);

		$nodes->add_control(
			'node_label',
			[
				'label'   => esc_html__( 'Label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$nodes->add_control(
			'node_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $node_icon_options,
				'default' => '5f8e77c078',
			]
		);

		$nodes->add_control(
			'node_angle',
			[
				'label'   => esc_html__( 'Angle (deg)', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '0',
			]
		);

		$nodes->add_control(
			'node_delay',
			[
				'label'   => esc_html__( 'Delay index', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '0',
			]
		);

		$widget->start_controls_section(
			'section_li_signal_nodes',
			[
				'label' => esc_html__( 'Orbit nodes', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'nodes_rows',
			[
				'label'         => esc_html__( 'Orbit nodes', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'render_type'   => 'template',
				'fields'        => $nodes->get_controls(),
				'default'       => $d['nodes_rows'],
				'title_field'   => '{{{ node_label }}}',
				'prevent_empty' => false,
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_li_signal_outcome',
			[
				'label' => esc_html__( 'Outcome', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'outcome_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => $node_icon_options,
				'default' => $d['outcome_icon'],
			]
		);

		$widget->add_control(
			'outcome_title',
			[
				'label'   => esc_html__( 'Title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $d['outcome_title'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'outcome_body',
			[
				'label'   => esc_html__( 'Body', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $d['outcome_body'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();
	}
}
