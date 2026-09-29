<?php
/**
 * Style tab controls for ELE GTM Funnel.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;

if ( ! function_exists( 'nexora_gtm_funnel_register_style_controls' ) ) {
/**
 * Register style controls.
 *
 * @param \Elementor\Widget_Base $widget Widget.
 */
function nexora_gtm_funnel_register_style_controls( $widget ): void {

	if ( ! $widget instanceof \Elementor\Widget_Base ) {
		return;
	}

	if ( ! class_exists( 'Nexora_Funnel_Geometry' ) ) {
		return;
	}

	/*
	 * Wrapper
	 */
	$widget->start_controls_section(
		'section_style_wrapper',
		[
			'label' => esc_html__( 'Wrapper', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_group_control(
		Group_Control_Background::get_type(),
		[
			'name'     => 'wrapper_background',
			'types'    => [ 'classic', 'gradient' ],
			'selector' => '{{WRAPPER}} .nexora-gtm__wrapper',
		]
	);

	$widget->add_responsive_control(
		'wrapper_padding',
		[
			'label'      => esc_html__( 'Padding', 'nexora-elementor' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .nexora-gtm__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$widget->add_responsive_control(
		'wrapper_min_height',
		[
			'label'      => esc_html__( 'Min Height', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [
				'px' => [ 'min' => 0, 'max' => 1200 ],
				'vh' => [ 'min' => 0, 'max' => 100 ],
			],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-min-height: {{SIZE}}{{UNIT}};',
			],
			'condition'  => [ 'use_min_height' => 'yes' ],
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
				'{{WRAPPER}}' => '--ngtm-gap: {{SIZE}}{{UNIT}}; --ngtm-gap-lg: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Group Headings
	 */
	$widget->start_controls_section(
		'section_style_group_headings',
		[
			'label' => esc_html__( 'Group Headings', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'     => 'group_heading_typography',
			'selector' => '{{WRAPPER}} .nexora-gtm__group-heading',
		]
	);

	$widget->add_control(
		'group_heading_color',
		[
			'label'     => esc_html__( 'Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}}' => '--ngtm-heading-color: {{VALUE}};',
			],
		]
	);

	$widget->add_responsive_control(
		'group_heading_letter_spacing',
		[
			'label'      => esc_html__( 'Letter Spacing', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'em', 'px' ],
			'range'      => [
				'em' => [ 'min' => 0, 'max' => 0.5, 'step' => 0.01 ],
				'px' => [ 'min' => 0, 'max' => 10 ],
			],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-heading-tracking: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_responsive_control(
		'group_heading_margin_bottom',
		[
			'label'      => esc_html__( 'Bottom Margin', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-heading-mb: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_responsive_control(
		'group_heading_align',
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
			'selectors' => [
				'{{WRAPPER}}' => '--ngtm-heading-align: {{VALUE}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Step Badge
	 */
	$widget->start_controls_section(
		'section_style_badge',
		[
			'label' => esc_html__( 'Step Badge', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_responsive_control(
		'badge_size',
		[
			'label'      => esc_html__( 'Size', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 24, 'max' => 80 ] ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-badge-size: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'     => 'badge_typography',
			'selector' => '{{WRAPPER}} .nexora-gtm__badge',
		]
	);

	$widget->add_control(
		'badge_radius',
		[
			'label'      => esc_html__( 'Border Radius', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-badge-radius: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_control(
		'badge_hover_scale',
		[
			'label'     => esc_html__( 'Hover Scale', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [ 'min' => 1, 'max' => 1.3, 'step' => 0.01 ],
			],
			'selectors' => [
				'{{WRAPPER}}' => '--ngtm-badge-hover-scale: {{SIZE}};',
			],
		]
	);

	$widget->add_control(
		'badge_accent_heading',
		[
			'label'     => esc_html__( 'Accent Badge', 'nexora-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$widget->add_control(
		'badge_bg_accent',
		[
			'label'     => esc_html__( 'Background', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-bg-accent: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_border_accent',
		[
			'label'     => esc_html__( 'Border', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-border-accent: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_text_accent',
		[
			'label'     => esc_html__( 'Text', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-text-accent: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_dark_heading',
		[
			'label'     => esc_html__( 'Dark Badge', 'nexora-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$widget->add_control(
		'badge_bg_dark',
		[
			'label'     => esc_html__( 'Background', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-bg-dark: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_border_dark',
		[
			'label'     => esc_html__( 'Border', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-border-dark: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_text_dark',
		[
			'label'     => esc_html__( 'Text', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-text-dark: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_blue_heading',
		[
			'label'     => esc_html__( 'Blue Badge', 'nexora-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$widget->add_control(
		'badge_bg_blue',
		[
			'label'     => esc_html__( 'Background', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-bg-blue: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_border_blue',
		[
			'label'     => esc_html__( 'Border', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-border-blue: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'badge_text_blue',
		[
			'label'     => esc_html__( 'Text', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-badge-text-blue: {{VALUE}};' ],
		]
	);

	$widget->end_controls_section();

	/*
	 * Step Card
	 */
	$widget->start_controls_section(
		'section_style_card',
		[
			'label' => esc_html__( 'Step Card', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_responsive_control(
		'card_radius',
		[
			'label'      => esc_html__( 'Border Radius', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-card-radius: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_responsive_control(
		'card_padding',
		[
			'label'      => esc_html__( 'Padding', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'rem' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-card-pad: {{SIZE}}{{UNIT}}; --ngtm-card-pad-sm: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->add_control(
		'card_bg_start',
		[
			'label'   => esc_html__( 'Gradient Start', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#2c374d',
		]
	);

	$widget->add_control(
		'card_bg_mid',
		[
			'label'   => esc_html__( 'Gradient Mid', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#3c4c6a',
		]
	);

	$widget->add_control(
		'card_bg_end',
		[
			'label'   => esc_html__( 'Gradient End', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#596d94',
		]
	);

	$widget->add_control(
		'card_border_color',
		[
			'label'     => esc_html__( 'Border Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-card-border: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'card_active_heading',
		[
			'label'     => esc_html__( 'Active State', 'nexora-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$widget->add_control(
		'card_active_bg_start',
		[
			'label'   => esc_html__( 'Active Gradient Start', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#34425d',
		]
	);

	$widget->add_control(
		'card_active_bg_mid',
		[
			'label'   => esc_html__( 'Active Gradient Mid', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#495a7c',
		]
	);

	$widget->add_control(
		'card_active_bg_end',
		[
			'label'   => esc_html__( 'Active Gradient End', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#6982b0',
		]
	);

	$widget->add_control(
		'card_dim_opacity',
		[
			'label'     => esc_html__( 'Dimmed Opacity', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [
				'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.05 ],
			],
			'selectors' => [
				'{{WRAPPER}}' => '--ngtm-card-dim-opacity: {{SIZE}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Step Title / Description / Icon
	 */
	$widget->start_controls_section(
		'section_style_card_text',
		[
			'label' => esc_html__( 'Step Title & Description', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'     => 'card_title_typography',
			'label'    => esc_html__( 'Title Typography', 'nexora-elementor' ),
			'selector' => '{{WRAPPER}} .nexora-gtm__card-title',
		]
	);

	$widget->add_control(
		'card_title_color',
		[
			'label'     => esc_html__( 'Title Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-title-color: {{VALUE}};' ],
		]
	);

	$widget->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name'     => 'card_desc_typography',
			'label'    => esc_html__( 'Description Typography', 'nexora-elementor' ),
			'selector' => '{{WRAPPER}} .nexora-gtm__card-desc',
		]
	);

	$widget->add_control(
		'card_desc_color',
		[
			'label'     => esc_html__( 'Description Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-desc-color: {{VALUE}};' ],
		]
	);

	$widget->add_responsive_control(
		'icon_size',
		[
			'label'      => esc_html__( 'Icon Size', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 12, 'max' => 40 ] ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-icon-size: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		]
	);

	$widget->add_control(
		'icon_color',
		[
			'label'     => esc_html__( 'Icon Accent Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-icon-color: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'icon_color_muted',
		[
			'label'     => esc_html__( 'Icon Muted Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-icon-color-muted: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'icon_frame_border',
		[
			'label'     => esc_html__( 'Icon Frame Border', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-icon-frame-border: {{VALUE}};' ],
		]
	);

	$widget->add_responsive_control(
		'icon_gap',
		[
			'label'      => esc_html__( 'Icon Gap', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-icon-gap: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$widget->end_controls_section();

	/*
	 * Funnel Geometry
	 */
	$widget->start_controls_section(
		'section_style_funnel_geometry',
		[
			'label' => esc_html__( 'Funnel Geometry', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$geometry_defaults = Nexora_Funnel_Geometry::defaults();

	foreach (
		[
			'top_width'    => [ 'Top Width', 200, 600 ],
			'bottom_width' => [ 'Bottom Width', 80, 400 ],
			'top_y'        => [ 'Top Y', 0, 80 ],
			'tier_height'  => [ 'Tier Height', 40, 160 ],
			'tier_gap'     => [ 'Tier Gap', 0, 40 ],
			'curve_depth'  => [ 'Curve Depth', 0, 30 ],
			'node_offset'  => [ 'Node Offset', 0, 60 ],
			'node_radius'  => [ 'Node Radius', 8, 40 ],
		] as $key => $meta
	) {
		$widget->add_control(
			$key,
			[
				'label'   => esc_html( $meta[0] ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => [
					'px' => [
						'min' => $meta[1],
						'max' => $meta[2],
					],
				],
				'default' => [
					'size' => $geometry_defaults[ $key ],
					'unit' => 'px',
				],
			]
		);
	}

	$widget->add_control(
		'geometry_note',
		[
			'type'            => Controls_Manager::RAW_HTML,
			'raw'             => esc_html__( 'Geometry uses SVG viewBox units and scales with container width. Changing these values switches from the exact 4-tier preset to parametric generation.', 'nexora-elementor' ),
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
		]
	);

	$widget->end_controls_section();

	/*
	 * Funnel Typography
	 */
	$widget->start_controls_section(
		'section_style_funnel_type',
		[
			'label' => esc_html__( 'Funnel Typography', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_control(
		'tier_value_heading',
		[
			'label' => esc_html__( 'Value', 'nexora-elementor' ),
			'type'  => Controls_Manager::HEADING,
		]
	);

	$widget->add_control(
		'tier_value_size',
		[
			'label'     => esc_html__( 'Font Size (SVG units)', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 12, 'max' => 48 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-value-size: {{SIZE}}px;' ],
		]
	);

	$widget->add_control(
		'tier_value_weight',
		[
			'label'     => esc_html__( 'Font Weight', 'nexora-elementor' ),
			'type'      => Controls_Manager::SELECT,
			'default'   => '',
			'options'   => [
				''    => esc_html__( 'Default', 'nexora-elementor' ),
				'400' => '400',
				'600' => '600',
				'700' => '700',
				'800' => '800',
				'900' => '900',
			],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-value-weight: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'tier_value_color',
		[
			'label'     => esc_html__( 'Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-value-color: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'tier_label_heading',
		[
			'label'     => esc_html__( 'Label', 'nexora-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$widget->add_control(
		'tier_label_size',
		[
			'label'     => esc_html__( 'Font Size (SVG units)', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 8, 'max' => 28 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-label-size: {{SIZE}}px;' ],
		]
	);

	$widget->add_control(
		'tier_label_color',
		[
			'label'     => esc_html__( 'Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-label-color: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'tier_sub_heading',
		[
			'label'     => esc_html__( 'Sublabel', 'nexora-elementor' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
		]
	);

	$widget->add_control(
		'tier_sub_size',
		[
			'label'     => esc_html__( 'Font Size (SVG units)', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 8, 'max' => 20 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-sub-size: {{SIZE}}px;' ],
		]
	);

	$widget->add_control(
		'tier_sub_color',
		[
			'label'     => esc_html__( 'Color', 'nexora-elementor' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-sub-color: {{VALUE}};' ],
		]
	);

	$widget->add_control(
		'tier_sub_opacity',
		[
			'label'     => esc_html__( 'Opacity', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0.2, 'max' => 1, 'step' => 0.05 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-sub-opacity: {{SIZE}};' ],
		]
	);

	$widget->end_controls_section();

	/*
	 * Funnel Interaction & Container
	 */
	$widget->start_controls_section(
		'section_style_funnel_interaction',
		[
			'label' => esc_html__( 'Funnel Interaction', 'nexora-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$widget->add_control(
		'tier_active_scale',
		[
			'label'     => esc_html__( 'Active Scale', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1, 'max' => 1.1, 'step' => 0.005 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-active-scale: {{SIZE}};' ],
		]
	);

	$widget->add_control(
		'tier_hover_scale',
		[
			'label'     => esc_html__( 'Hover Scale', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 1, 'max' => 1.1, 'step' => 0.005 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-hover-scale: {{SIZE}};' ],
		]
	);

	$widget->add_control(
		'tier_dim_opacity',
		[
			'label'     => esc_html__( 'Dimmed Opacity', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0.1, 'max' => 1, 'step' => 0.05 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-dim-opacity: {{SIZE}};' ],
		]
	);

	$widget->add_control(
		'tier_transition',
		[
			'label'     => esc_html__( 'Transition (s)', 'nexora-elementor' ),
			'type'      => Controls_Manager::SLIDER,
			'range'     => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.05 ] ],
			'selectors' => [ '{{WRAPPER}}' => '--ngtm-tier-transition: {{SIZE}}s;' ],
		]
	);

	$widget->add_control(
		'glow_dy',
		[
			'label'   => esc_html__( 'Glow Offset Y', 'nexora-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 20 ] ],
			'default' => [ 'size' => 6 ],
		]
	);

	$widget->add_control(
		'glow_blur',
		[
			'label'   => esc_html__( 'Glow Blur', 'nexora-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 30 ] ],
			'default' => [ 'size' => 10 ],
		]
	);

	$widget->add_control(
		'glow_color',
		[
			'label'   => esc_html__( 'Glow Color', 'nexora-elementor' ),
			'type'    => Controls_Manager::COLOR,
			'default' => '#000000',
		]
	);

	$widget->add_control(
		'glow_opacity',
		[
			'label'   => esc_html__( 'Glow Opacity', 'nexora-elementor' ),
			'type'    => Controls_Manager::SLIDER,
			'range'   => [ 'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ] ],
			'default' => [ 'size' => 0.22 ],
		]
	);

	$widget->add_responsive_control(
		'funnel_max_width',
		[
			'label'      => esc_html__( 'Funnel Max Width', 'nexora-elementor' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 280, 'max' => 900 ] ],
			'selectors'  => [
				'{{WRAPPER}}' => '--ngtm-funnel-max-w: {{SIZE}}{{UNIT}};',
			],
			'separator'  => 'before',
		]
	);

	$widget->end_controls_section();
}
} // function_exists nexora_gtm_funnel_register_style_controls
