<?php
/**
 * Style tab controls for ELE Timeline.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

if ( ! function_exists( 'nexora_timeline_register_style_controls' ) ) {
/**
 * Register style controls.
 *
 * @param \Elementor\Widget_Base $widget Widget.
 */
function nexora_timeline_register_style_controls( $widget ): void {

	if ( ! $widget instanceof \Elementor\Widget_Base ) {
		return;
	}

	/*
	 * Layout
	 */
	$widget->start_controls_section(
		'section_style_layout',
		[
			'label' => esc_html__( 'Layout', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_control(
		'section_background_color',
		[
			'label'     => esc_html__( 'Background Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#0a0a0a',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-bg: {{VALUE}}; background-color: {{VALUE}};',
			],
		]
	);

	$widget->add_responsive_control(
		'section_max_width',
		[
			'label'      => esc_html__( 'Max Width', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [ 'min' => 640, 'max' => 1600 ],
				'%'  => [ 'min' => 50, 'max' => 100 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 1280,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-max-width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .nexora-ele-timeline__inner' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_responsive_control(
		'section_padding',
		[
			'label'      => esc_html__( 'Section Padding', 'nexora-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline__inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		]
	);

	$widget->add_responsive_control(
		'column_gap',
		[
			'label'      => esc_html__( 'Column Gap', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'rem' ],
			'range'      => [
				'px'  => [ 'min' => 0, 'max' => 120 ],
				'rem' => [ 'min' => 0, 'max' => 8 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-gap: {{SIZE}}{{UNIT}}; --nexora-tl-gap-lg: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_control(
		'left_col_span',
		[
			'label'       => esc_html__( 'Left Column Span', 'nexora-elementor' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => '6',
			'options'     => [
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
			],
			'render_type' => 'template',
		]
	);

	$widget->add_control(
		'right_col_span',
		[
			'label'       => esc_html__( 'Right Column Span', 'nexora-elementor' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => '6',
			'options'     => [
				'4' => '4',
				'5' => '5',
				'6' => '6',
				'7' => '7',
				'8' => '8',
			],
			'render_type' => 'template',
		]
	);

	$widget->add_responsive_control(
		'sticky_top',
		[
			'label'       => esc_html__( 'Visual Align Offset', 'nexora-elementor' ),
			'type'        => Controls_Manager::SLIDER,
			'size_units'  => [ 'px', 'rem' ],
			'range'       => [
				'px' => [ 'min' => -80, 'max' => 200 ],
			],
			'default'     => [
				'unit' => 'px',
				'size' => 0,
			],
			'description' => esc_html__( 'Extra vertical offset when aligning the image beside the active card.', 'nexora-elementor' ),
			'selectors'   => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-visual-offset: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_responsive_control(
		'steps_spacing',
		[
			'label'      => esc_html__( 'Steps Vertical Spacing', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'rem' ],
			'range'      => [
				'px' => [ 'min' => 24, 'max' => 160 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-step-gap: {{SIZE}}{{UNIT}}; --nexora-tl-step-gap-sm: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Image Panel
	 */
	$widget->start_controls_section(
		'section_style_image_panel',
		[
			'label' => esc_html__( 'Image Panel', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_responsive_control(
		'image_panel_max_width',
		[
			'label'      => esc_html__( 'Max Width', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [ 'min' => 200, 'max' => 800 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 540,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-image-max: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .nexora-ele-timeline__image-frame' => 'max-width: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_control(
		'image_panel_bg',
		[
			'label'     => esc_html__( 'Background Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#000000',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-image-bg: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline__image-frame' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline__mobile-frame' => 'background-color: {{VALUE}};',
			],
		]
	);

	$widget->add_responsive_control(
		'image_panel_radius',
		[
			'label'      => esc_html__( 'Border Radius', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 80 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 36,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-image-radius: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .nexora-ele-timeline__image-frame' => 'border-radius: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'     => 'image_panel_border',
			'selector' => '{{WRAPPER}} .nexora-ele-timeline__image-frame',
		]
	);

	$widget->add_group_control(
		Group_Control_Box_Shadow::get_type(),
		[
			'name'     => 'image_panel_shadow',
			'selector' => '{{WRAPPER}} .nexora-ele-timeline__image-frame',
		]
	);

	$widget->add_control(
		'image_object_fit',
		[
			'label'     => esc_html__( 'Object Fit', 'nexora-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => 'cover',
			'options'   => [
				'cover'   => 'cover',
				'contain' => 'contain',
				'fill'    => 'fill',
			],
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-image-fit: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline__desktop-image' => 'object-fit: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline__mobile-frame img' => 'object-fit: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'image_opacity',
		[
			'label'      => esc_html__( 'Image Opacity', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 1,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-image-opacity: {{SIZE}};',
				'{{WRAPPER}} .nexora-ele-timeline__desktop-image' => 'opacity: {{SIZE}};',
			],
		]
	);

	$widget->add_control(
		'image_overlay_color',
		[
			'label'     => esc_html__( 'Overlay Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-overlay-color: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline__image-overlay' => 'background-color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'image_overlay_opacity',
		[
			'label'      => esc_html__( 'Overlay Opacity', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 0,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-overlay-opacity: {{SIZE}};',
				'{{WRAPPER}} .nexora-ele-timeline__image-overlay' => 'opacity: {{SIZE}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Large Active Number
	 */
	$widget->start_controls_section(
		'section_style_large_number',
		[
			'label' => esc_html__( 'Large Active Number', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_control(
		'show_large_active_number',
		[
			'label'        => esc_html__( 'Show Large Active Number', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Show', 'nexora-elementor' ),
			'label_off'    => esc_html__( 'Hide', 'nexora-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
			'render_type'  => 'template',
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'      => 'large_number_typography',
			'selector'  => '{{WRAPPER}} .nexora-ele-timeline__active-num',
			'condition' => [
				'show_large_active_number' => 'yes',
			],
		]
	);

	$widget->add_control(
		'large_number_fill',
		[
			'label'     => esc_html__( 'Fill Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'transparent',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-large-fill: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline__active-num' => 'color: {{VALUE}};',
			],
			'condition' => [
				'show_large_active_number' => 'yes',
			],
		]
	);

	$widget->add_control(
		'large_number_stroke',
		[
			'label'     => esc_html__( 'Stroke Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(255,255,255,0.35)',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-large-stroke: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline__active-num' => '-webkit-text-stroke-color: {{VALUE}};',
			],
			'condition' => [
				'show_large_active_number' => 'yes',
			],
		]
	);

	$widget->add_control(
		'large_number_stroke_width',
		[
			'label'      => esc_html__( 'Stroke Width', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [ 'min' => 0.5, 'max' => 4, 'step' => 0.1 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 1.5,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-large-stroke-width: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [
				'show_large_active_number' => 'yes',
			],
		]
	);

	$widget->add_control(
		'large_number_opacity',
		[
			'label'      => esc_html__( 'Opacity', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 1,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-large-opacity: {{SIZE}};',
			],
			'condition'  => [
				'show_large_active_number' => 'yes',
			],
		]
	);

	$widget->add_responsive_control(
		'large_number_margin',
		[
			'label'      => esc_html__( 'Margin', 'nexora-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline__active-num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition'  => [
				'show_large_active_number' => 'yes',
			],
		]
	);

	$widget->add_responsive_control(
		'large_number_align',
		[
			'label'     => esc_html__( 'Alignment', 'nexora-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [
					'title' => esc_html__( 'Left', 'nexora-elementor' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => esc_html__( 'Center', 'nexora-elementor' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => esc_html__( 'Right', 'nexora-elementor' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'default'   => 'left',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline__active-num' => 'text-align: {{VALUE}};',
			],
			'condition' => [
				'show_large_active_number' => 'yes',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Timeline Line
	 */
	$widget->start_controls_section(
		'section_style_timeline_line',
		[
			'label' => esc_html__( 'Timeline Line', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_control(
		'track_color',
		[
			'label'     => esc_html__( 'Track Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#fed7aa',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-track: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-track-line' => 'background-color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'progress_color',
		[
			'label'     => esc_html__( 'Progress Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f97316',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-progress: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-progress-line' => 'background-color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'line_width',
		[
			'label'      => esc_html__( 'Line Width', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [ 'min' => 1, 'max' => 6, 'step' => 0.5 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 1.5,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-line-width: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_control(
		'line_left',
		[
			'label'      => esc_html__( 'Line Left Offset', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 40 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 14,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-line-left: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Timeline Marker
	 */
	$widget->start_controls_section(
		'section_style_marker',
		[
			'label' => esc_html__( 'Timeline Marker', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_control(
		'marker_size',
		[
			'label'      => esc_html__( 'Size', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [ 'min' => 8, 'max' => 28 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 14,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-marker-size: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_control(
		'marker_bg',
		[
			'label'     => esc_html__( 'Background', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#fdba74',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-marker-bg: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-node' => 'background-color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'marker_bg_active',
		[
			'label'     => esc_html__( 'Active Background', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f97316',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-marker-bg-active: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-step.active .timeline-node' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-step.passed .timeline-node' => 'background-color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'marker_border_color',
		[
			'label'     => esc_html__( 'Border Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-marker-border: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'marker_border_width',
		[
			'label'      => esc_html__( 'Border Width', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 6 ],
			],
			'default'    => [
				'unit' => 'px',
				'size' => 2,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-marker-border-width: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_control(
		'marker_glow',
		[
			'label'     => esc_html__( 'Active Glow', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => 'rgba(249, 115, 22, 0.3)',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-marker-glow: {{VALUE}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Item Number
	 */
	$widget->start_controls_section(
		'section_style_item_number',
		[
			'label' => esc_html__( 'Item Number', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'     => 'item_number_typography',
			'selector' => '{{WRAPPER}} .nexora-ele-timeline .step-num',
		]
	);

	$widget->add_control(
		'item_number_color',
		[
			'label'     => esc_html__( 'Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#64748b',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-num-color: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .step-num' => 'color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'item_number_color_active',
		[
			'label'     => esc_html__( 'Active Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f97316',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-num-color-active: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-step.active .step-num' => 'color: {{VALUE}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Title
	 */
	$widget->start_controls_section(
		'section_style_title',
		[
			'label' => esc_html__( 'Title', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'     => 'item_title_typography',
			'selector' => '{{WRAPPER}} .nexora-ele-timeline .step-title',
		]
	);

	$widget->add_control(
		'item_title_color',
		[
			'label'     => esc_html__( 'Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#94a3b8',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-title-color: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .step-title' => 'color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'item_title_color_active',
		[
			'label'     => esc_html__( 'Active Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#f8fafc',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-title-color-active: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-step.active .step-title' => 'color: {{VALUE}};',
			],
		]
	);

	$widget->add_responsive_control(
		'item_title_margin',
		[
			'label'      => esc_html__( 'Margin', 'nexora-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline .step-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Description
	 */
	$widget->start_controls_section(
		'section_style_description',
		[
			'label' => esc_html__( 'Description', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'     => 'item_desc_typography',
			'selector' => '{{WRAPPER}} .nexora-ele-timeline .step-desc',
		]
	);

	$widget->add_control(
		'item_desc_color',
		[
			'label'     => esc_html__( 'Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#94a3b8',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-desc-color: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .step-desc' => 'color: {{VALUE}};',
			],
		]
	);

	$widget->add_control(
		'item_desc_color_active',
		[
			'label'     => esc_html__( 'Active Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#cbd5e1',
			'selectors' => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-desc-color-active: {{VALUE}};',
				'{{WRAPPER}} .nexora-ele-timeline .timeline-step.active .step-desc' => 'color: {{VALUE}};',
			],
		]
	);

	$widget->add_responsive_control(
		'item_desc_max_width',
		[
			'label'      => esc_html__( 'Max Width', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'rem' ],
			'range'      => [
				'px' => [ 'min' => 200, 'max' => 800 ],
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-desc-max: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_responsive_control(
		'item_desc_margin',
		[
			'label'      => esc_html__( 'Margin', 'nexora-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline .step-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Animation
	 */
	$widget->start_controls_section(
		'section_style_animation',
		[
			'label' => esc_html__( 'Animation', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_control(
		'enable_interactions',
		[
			'label'        => esc_html__( 'Enable Scroll & Click', 'nexora-elementor' ),
			'type'         => Controls_Manager::SWITCHER,
			'label_on'     => esc_html__( 'Yes', 'nexora-elementor' ),
			'label_off'    => esc_html__( 'No', 'nexora-elementor' ),
			'return_value' => 'yes',
			'default'      => 'yes',
			'render_type'  => 'template',
		]
	);

	$widget->add_control(
		'image_swap_delay',
		[
			'label'       => esc_html__( 'Image Swap Delay (ms)', 'nexora-elementor' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 150,
			'min'         => 0,
			'max'         => 1000,
			'description' => esc_html__( 'Matches original timeline.ts fade-out before src change.', 'nexora-elementor' ),
			'render_type' => 'template',
		]
	);

	$widget->add_control(
		'progress_duration',
		[
			'label'      => esc_html__( 'Progress Duration', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 's' ],
			'range'      => [
				's' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.05 ],
			],
			'default'    => [
				'unit' => 's',
				'size' => 0.35,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-progress-duration: {{SIZE}}s;',
			],
		]
	);

	$widget->add_control(
		'image_transition_duration',
		[
			'label'      => esc_html__( 'Image Transition Duration', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 's' ],
			'range'      => [
				's' => [ 'min' => 0.1, 'max' => 2, 'step' => 0.05 ],
			],
			'default'    => [
				'unit' => 's',
				'size' => 0.4,
			],
			'selectors'  => [
				'{{WRAPPER}} .nexora-ele-timeline' => '--nexora-tl-fade-duration: {{SIZE}}s;',
			],
		]
	);

	$widget->end_controls_section();
}
}
