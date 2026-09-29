<?php
/**
 * Content tab controls for ELE GTM Funnel.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_gtm_funnel_register_content_controls' ) ) {
/**
 * Register content controls.
 *
 * @param \Elementor\Widget_Base $widget Widget.
 */
function nexora_gtm_funnel_register_content_controls( $widget ): void {

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

	/*
	 * Layout
	 */
	$widget->start_controls_section(
		'section_layout',
		[
			'label' => esc_html__( 'Layout', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]
	);

	$widget->add_control(
		'funnel_position',
		[
			'label'   => esc_html__( 'Funnel Position', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'right',
			'options' => [
				'left'  => esc_html__( 'Left', 'nexora-elementor' ),
				'right' => esc_html__( 'Right', 'nexora-elementor' ),
			],
		]
	);

	$widget->add_control(
		'process_col_span',
		[
			'label'   => esc_html__( 'Process Column Span', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '6',
			'options' => [
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
			],
		]
	);

	$widget->add_control(
		'funnel_col_span',
		[
			'label'   => esc_html__( 'Funnel Column Span', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => '6',
			'options' => [
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
			],
			'description' => esc_html__( 'Process + Funnel spans should total 12 on large screens.', 'nexora-elementor' ),
		]
	);

	$widget->add_control(
		'use_min_height',
		[
			'label'        => esc_html__( 'Full Viewport Height', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'nexora-elementor' ),
			'label_off'    => esc_html__( 'No', 'nexora-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
		]
	);

	$widget->add_control(
		'mobile_stack_order',
		[
			'label'   => esc_html__( 'Mobile Stack Order', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'process_first',
			'options' => [
				'process_first' => esc_html__( 'Process then Funnel', 'nexora-elementor' ),
				'funnel_first'  => esc_html__( 'Funnel then Process', 'nexora-elementor' ),
			],
		]
	);

	$widget->add_control(
		'hide_funnel_mobile',
		[
			'label'        => esc_html__( 'Hide Funnel on Mobile', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		]
	);

	$widget->add_control(
		'show_section_heading',
		[
			'label'        => esc_html__( 'Show Section Heading', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
			'separator'    => 'before',
		]
	);

	$widget->add_control(
		'section_eyebrow',
		[
			'label'       => esc_html__( 'Eyebrow', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
			'condition'   => [ 'show_section_heading' => 'yes' ],
		]
	);

	$widget->add_control(
		'section_title',
		[
			'label'       => esc_html__( 'Title', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
			'condition'   => [ 'show_section_heading' => 'yes' ],
		]
	);

	$widget->add_control(
		'section_subtitle',
		[
			'label'       => esc_html__( 'Subtitle', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
			'dynamic'     => [ 'active' => true ],
			'condition'   => [ 'show_section_heading' => 'yes' ],
		]
	);

	$widget->end_controls_section();

	/*
	 * Process Steps
	 */
	$widget->start_controls_section(
		'section_process_steps',
		[
			'label' => esc_html__( 'Process Steps', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]
	);

	$steps_repeater = new Repeater();

	$steps_repeater->add_control(
		'group_heading',
		[
			'label'       => esc_html__( 'Group Heading', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'description' => esc_html__( 'Leave empty to continue the previous group. Non-empty starts a new group.', 'nexora-elementor' ),
			'dynamic'     => [ 'active' => true ],
		]
	);

	$steps_repeater->add_control(
		'step_number',
		[
			'label'   => esc_html__( 'Step Number', 'nexora-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '01',
			'dynamic' => [ 'active' => true ],
		]
	);

	$steps_repeater->add_control(
		'step_icon',
		[
			'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
			'type'    => Controls_Manager::ICONS,
			'default' => function_exists( 'nexora_gtm_default_icon' )
				? nexora_gtm_default_icon( 'users' )
				: [
					'value'   => 'fas fa-users',
					'library' => 'fa-solid',
				],
			'recommended' => [
				'fa-solid' => [
					'users',
					'user',
					'comments',
					'comment',
					'briefcase',
					'envelope',
					'calendar',
					'trophy',
					'bullseye',
					'chart-line',
				],
			],
		]
	);

	$steps_repeater->add_control(
		'icon_frame',
		[
			'label'        => esc_html__( 'Icon Frame', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		]
	);

	$steps_repeater->add_control(
		'icon_tone',
		[
			'label'   => esc_html__( 'Icon Tone', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'accent',
			'options' => [
				'accent' => esc_html__( 'Accent', 'nexora-elementor' ),
				'muted'  => esc_html__( 'Muted', 'nexora-elementor' ),
			],
		]
	);

	$steps_repeater->add_control(
		'step_title',
		[
			'label'       => esc_html__( 'Title', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		]
	);

	$steps_repeater->add_control(
		'step_description',
		[
			'label'   => esc_html__( 'Description', 'nexora-elementor' ),
			'type'    => Controls_Manager::TEXTAREA,
			'default' => '',
			'dynamic' => [ 'active' => true ],
		]
	);

	$steps_repeater->add_control(
		'badge_style',
		[
			'label'   => esc_html__( 'Badge Style', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'dark',
			'options' => [
				'accent' => esc_html__( 'Accent', 'nexora-elementor' ),
				'dark'   => esc_html__( 'Dark', 'nexora-elementor' ),
				'blue'   => esc_html__( 'Blue', 'nexora-elementor' ),
			],
		]
	);

	$steps_repeater->add_control(
		'linked_tier',
		[
			'label'       => esc_html__( 'Linked Funnel Tier', 'nexora-elementor' ),
			'type'        => Controls_Manager::NUMBER,
			'min'         => 1,
			'default'     => 1,
			'description' => esc_html__( '1-based tier index to highlight on hover/click.', 'nexora-elementor' ),
		]
	);

	$widget->add_control(
		'process_steps',
		[
			'label'       => esc_html__( 'Steps', 'nexora-elementor' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $steps_repeater->get_controls(),
			'default'     => nexora_gtm_funnel_default_steps(),
			'title_field' => '{{{ step_number }}} — {{{ step_title }}}',
		]
	);

	$widget->end_controls_section();

	/*
	 * Funnel Tiers
	 */
	$widget->start_controls_section(
		'section_funnel_tiers',
		[
			'label' => esc_html__( 'Funnel Tiers', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]
	);

	$tiers_repeater = new Repeater();

	$tiers_repeater->add_control(
		'tier_value',
		[
			'label'   => esc_html__( 'Value', 'nexora-elementor' ),
			'type'    => Controls_Manager::TEXT,
			'default' => '1,000+',
			'dynamic' => [ 'active' => true ],
		]
	);

	$tiers_repeater->add_control(
		'tier_label',
		[
			'label'       => esc_html__( 'Label', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		]
	);

	$tiers_repeater->add_control(
		'tier_sublabel',
		[
			'label'       => esc_html__( 'Sublabel', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		]
	);

	$tiers_repeater->add_control(
		'gradient_start',
		[
			'label'   => esc_html__( 'Gradient Start', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#7325e8',
		]
	);

	$tiers_repeater->add_control(
		'gradient_mid',
		[
			'label'   => esc_html__( 'Gradient Mid', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#8432f6',
		]
	);

	$tiers_repeater->add_control(
		'gradient_end',
		[
			'label'   => esc_html__( 'Gradient End', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#8e37f8',
		]
	);

	$tiers_repeater->add_control(
		'show_node',
		[
			'label'        => esc_html__( 'Show Node', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		]
	);

	$tiers_repeater->add_control(
		'node_color',
		[
			'label'     => esc_html__( 'Node Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#6d26e4',
			'condition' => [ 'show_node' => 'yes' ],
		]
	);

	$tiers_repeater->add_control(
		'node_icon',
		[
			'label'     => esc_html__( 'Node Icon', 'nexora-elementor' ),
			'type'      => Controls_Manager::ICONS,
			'default'   => function_exists( 'nexora_gtm_default_icon' )
				? nexora_gtm_default_icon( 'users' )
				: [
					'value'   => 'fas fa-users',
					'library' => 'fa-solid',
				],
			'condition' => [ 'show_node' => 'yes' ],
			'recommended' => [
				'fa-solid' => [
					'users',
					'envelope',
					'calendar',
					'trophy',
					'briefcase',
					'user',
					'comments',
					'bullseye',
				],
			],
		]
	);

	$widget->add_control(
		'funnel_tiers',
		[
			'label'       => esc_html__( 'Tiers', 'nexora-elementor' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $tiers_repeater->get_controls(),
			'default'     => nexora_gtm_funnel_default_tiers(),
			'title_field' => '{{{ tier_value }}} — {{{ tier_label }}}',
		]
	);

	$widget->end_controls_section();

	/*
	 * Interaction
	 */
	$widget->start_controls_section(
		'section_interaction',
		[
			'label' => esc_html__( 'Interaction', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]
	);

	$widget->add_control(
		'interaction_mode',
		[
			'label'   => esc_html__( 'Mode', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'hover',
			'options' => [
				'hover' => esc_html__( 'Hover', 'nexora-elementor' ),
				'click' => esc_html__( 'Click', 'nexora-elementor' ),
				'both'  => esc_html__( 'Both', 'nexora-elementor' ),
			],
		]
	);

	$widget->add_control(
		'default_active',
		[
			'label'   => esc_html__( 'Default Active', 'nexora-elementor' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'none',
			'options' => [
				'none'     => esc_html__( 'None', 'nexora-elementor' ),
				'first'    => esc_html__( 'First Tier', 'nexora-elementor' ),
				'specific' => esc_html__( 'Specific Index', 'nexora-elementor' ),
			],
		]
	);

	$widget->add_control(
		'default_active_index',
		[
			'label'     => esc_html__( 'Active Tier Index', 'nexora-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1,
			'default'   => 1,
			'condition' => [ 'default_active' => 'specific' ],
		]
	);

	$widget->add_control(
		'dim_inactive',
		[
			'label'        => esc_html__( 'Dim Inactive Items', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		]
	);

	$widget->add_control(
		'auto_rotate',
		[
			'label'        => esc_html__( 'Auto Rotate', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => '',
		]
	);

	$widget->add_control(
		'rotate_interval',
		[
			'label'     => esc_html__( 'Rotate Interval (ms)', 'nexora-elementor' ),
			'type'      => Controls_Manager::NUMBER,
			'min'       => 1000,
			'step'      => 100,
			'default'   => 3000,
			'condition' => [ 'auto_rotate' => 'yes' ],
		]
	);

	$widget->add_control(
		'enable_keyboard',
		[
			'label'        => esc_html__( 'Keyboard Accessibility', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'return_value' => 'yes',
			'default'      => 'yes',
		]
	);

	$widget->end_controls_section();
}
} // function_exists nexora_gtm_funnel_register_content_controls
