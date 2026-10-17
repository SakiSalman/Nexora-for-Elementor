<?php
/**
 * Content controls for CE Services. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_services_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_services_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_services_defaults();

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
			'section_services_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'services_heading_tag',
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
				'default' => $ce_d['heading_tag'],
			]
		);

		$widget->add_control(
			'services_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'services_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['heading'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text before the highlighted words.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'services_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient at the end of the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'services_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['description'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_services_cards',
			[
				'label' => esc_html__( 'Service cards', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$cards = new Repeater();

		$cards->add_control(
			'services_card_layout',
			[
				'label'       => esc_html__( 'Card layout', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'wide'      => esc_html__( 'Wide feature', 'nexora-elementor' ),
					'standard'  => esc_html__( 'Standard', 'nexora-elementor' ),
					'checklist' => esc_html__( 'Wide with checklist', 'nexora-elementor' ),
				],
				'default'     => 'wide',
				'description' => esc_html__( 'Picks the shell, padding, and heading size the design uses for this card.', 'nexora-elementor' ),
				'render_type' => 'template',
			]
		);

		$cards->add_control(
			'services_icon',
			[
				'label'       => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'de0ce30a25' => esc_html__( 'Lead Research & Prospect Targeting', 'nexora-elementor' ),
					'33167c9565' => esc_html__( 'Cold Email Copy & Personalization', 'nexora-elementor' ),
					'092b71c242' => esc_html__( 'Email Deliverability Setup', 'nexora-elementor' ),
					'7190abeb62' => esc_html__( 'Cold Email Sequences & Automation', 'nexora-elementor' ),
					'eec0a62124' => esc_html__( 'Reply Qualification & Meeting Handoff', 'nexora-elementor' ),
					'e322230590' => esc_html__( 'CRM & Pipeline Tracking', 'nexora-elementor' ),
				],
				'default'     => 'de0ce30a25',
				'description' => esc_html__( 'Design icon set for this section.', 'nexora-elementor' ),
			]
		);

		$cards->add_control(
			'services_icon_tint',
			[
				'label'       => esc_html__( 'Icon tint', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'orange' => esc_html__( 'Orange', 'nexora-elementor' ),
					'blue'   => esc_html__( 'Blue', 'nexora-elementor' ),
					'dark'   => esc_html__( 'Dark', 'nexora-elementor' ),
				],
				'default'     => 'dark',
				'description' => esc_html__( 'Colors the icon stroke and its badge.', 'nexora-elementor' ),
			]
		);

		$cards->add_control(
			'services_badge',
			[
				'label'     => esc_html__( 'Badge text', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'services_card_layout' => 'wide' ],
			]
		);

		$cards->add_control(
			'services_title',
			[
				'label'       => esc_html__( 'Title', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			]
		);

		$cards->add_control(
			'services_title_tag',
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
				'default' => 'h3',
			]
		);

		$cards->add_control(
			'services_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'rows'    => 4,
				'dynamic' => array( 'active' => true ),
			]
		);

		$cards->add_control(
			'services_chips',
			[
				'label'       => esc_html__( 'Chips', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 4,
				'description' => esc_html__( 'One chip per line. Wide feature cards only.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'services_card_layout' => 'wide' ],
			]
		);

		$cards->add_control(
			'services_checklist',
			[
				'label'       => esc_html__( 'Checklist', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 6,
				'description' => esc_html__( 'One item per line. Wide with checklist cards only.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'services_card_layout' => 'checklist' ],
			]
		);

		$widget->add_control(
			'services_cards',
			[
				'label'       => esc_html__( 'Service cards', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $cards->get_controls(),
				'default'     => $ce_d['cards'],
				'title_field' => 'services_title',
				'prevent_empty' => false,
			]
		);

		$widget->end_controls_section();
	}
}
