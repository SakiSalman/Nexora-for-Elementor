<?php
/**
 * Content tab controls for ELE Timeline.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_timeline_register_content_controls' ) ) {
/**
 * Register content controls.
 *
 * @param \Elementor\Widget_Base $widget Widget.
 */
function nexora_timeline_register_content_controls( $widget ): void {

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

	$widget->start_controls_section(
		'section_timeline_items',
		[
			'label' => esc_html__( 'Timeline Items', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		]
	);

	$repeater = new Repeater();

	$repeater->add_control(
		'item_number',
		[
			'label'       => esc_html__( 'Number', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '01',
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		]
	);

	$repeater->add_control(
		'item_title',
		[
			'label'       => esc_html__( 'Title', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Timeline Step', 'nexora-elementor' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		]
	);

	$repeater->add_control(
		'item_description',
		[
			'label'       => esc_html__( 'Description', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXTAREA,
			'default'     => '',
			'rows'        => 4,
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		]
	);

	$repeater->add_control(
		'item_image',
		[
			'label'   => esc_html__( 'Image', 'nexora-elementor' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => function_exists( 'nexora_timeline_media_default' )
				? nexora_timeline_media_default( 'step1_buyers_1787294348844.jpg' )
				: [ 'url' => '', 'id' => '' ],
		]
	);

	$repeater->add_control(
		'item_link',
		[
			'label'       => esc_html__( 'Link', 'nexora-elementor' ),
			'type'        => Controls_Manager::URL,
			'placeholder' => 'https://',
			'dynamic'     => [ 'active' => true ],
			'default'     => [
				'url'         => '',
				'is_external' => '',
				'nofollow'    => '',
			],
		]
	);

	$repeater->add_control(
		'item_class',
		[
			'label'       => esc_html__( 'Custom Class', 'nexora-elementor' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => '',
			'label_block' => true,
		]
	);

	$repeater->add_control(
		'item_custom_colors_heading',
		[
			'label'     => esc_html__( 'Custom Colors', 'nexora-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$repeater->add_control(
		'item_number_color',
		[
			'label' => esc_html__( 'Number Color', 'nexora-elementor' ),
			'type'  => Controls_Manager::COLOR,
		]
	);

	$repeater->add_control(
		'item_number_color_active',
		[
			'label' => esc_html__( 'Active Number Color', 'nexora-elementor' ),
			'type'  => Controls_Manager::COLOR,
		]
	);

	$repeater->add_control(
		'item_title_color',
		[
			'label' => esc_html__( 'Title Color', 'nexora-elementor' ),
			'type'  => Controls_Manager::COLOR,
		]
	);

	$repeater->add_control(
		'item_title_color_active',
		[
			'label' => esc_html__( 'Active Title Color', 'nexora-elementor' ),
			'type'  => Controls_Manager::COLOR,
		]
	);

	$repeater->add_control(
		'item_desc_color',
		[
			'label' => esc_html__( 'Description Color', 'nexora-elementor' ),
			'type'  => Controls_Manager::COLOR,
		]
	);

	$defaults = function_exists( 'nexora_timeline_default_items' )
		? nexora_timeline_default_items()
		: [];

	$widget->add_control(
		'timeline_items',
		[
			'label'       => esc_html__( 'Items', 'nexora-elementor' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater->get_controls(),
			'default'     => $defaults,
			'title_field' => '{{{ item_number }}} — {{{ item_title }}}',
		]
	);

	$widget->end_controls_section();
}
}
