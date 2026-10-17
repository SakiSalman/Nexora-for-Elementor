<?php
/**
 * Content controls for CE Signal. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_signal_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_signal_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_signal_defaults();

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
			'section_signal_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'signal_heading_tag',
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
			'signal_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_heading',
			[
				'label'   => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['heading'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient inside the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'signal_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['description'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_signal_account',
			[
				'label' => esc_html__( 'Account', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'signal_account_name',
			[
				'label'   => esc_html__( 'Account name', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['account_name'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_account_label',
			[
				'label'   => esc_html__( 'Account label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['account_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_account_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'a5f1cbccc8' => esc_html__( 'Account icon', 'nexora-elementor' ),
				),
				'default' => $ce_d['account_icon'],
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_signal_signals',
			[
				'label' => esc_html__( 'Signals', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'signal_signals_label',
			[
				'label'   => esc_html__( 'Signals label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['signals_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$signals_repeater = new Repeater();

		$signals_repeater->add_control(
			'signal_text',
			[
				'label'   => esc_html__( 'Text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_signals_rows',
			[
				'label'       => esc_html__( 'Signals', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $signals_repeater->get_controls(),
				'default'     => $ce_d['signals_rows'],
				'title_field' => 'signal_text',
				'render_type' => 'template',
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_signal_email',
			[
				'label' => esc_html__( 'Email', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'signal_subject_label',
			[
				'label'   => esc_html__( 'Subject label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['subject_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_subject',
			[
				'label'   => esc_html__( 'Email subject', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['subject'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$paragraphs_repeater = new Repeater();

		$paragraphs_repeater->add_control(
			'signal_paragraph_text',
			[
				'label'       => esc_html__( 'Text', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Wrap words in [[double brackets]] to highlight them.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'signal_paragraphs_rows',
			[
				'label'       => esc_html__( 'Email body paragraphs', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $paragraphs_repeater->get_controls(),
				'default'     => $ce_d['paragraphs_rows'],
				'title_field' => 'signal_paragraph_text',
				'render_type' => 'template',
			]
		);

		$widget->add_control(
			'signal_signature',
			[
				'label'       => esc_html__( 'Signature', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => $ce_d['signature'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'One line per line; lines are joined with a soft line break.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_signal_stats',
			[
				'label' => esc_html__( 'Stats', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$stats_repeater = new Repeater();

		$stats_repeater->add_control(
			'signal_stat_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'fb04fde6c8' => esc_html__( 'Contacted', 'nexora-elementor' ),
					'8f9db77c3f' => esc_html__( 'Meetings booked', 'nexora-elementor' ),
				),
				'default' => 'fb04fde6c8',
			]
		);

		$stats_repeater->add_control(
			'signal_stat_value',
			[
				'label'   => esc_html__( 'Value', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$stats_repeater->add_control(
			'signal_stat_label',
			[
				'label'   => esc_html__( 'Label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_stats_rows',
			[
				'label'       => esc_html__( 'Stats', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $stats_repeater->get_controls(),
				'default'     => $ce_d['stats_rows'],
				'title_field' => 'signal_stat_value',
				'render_type' => 'template',
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_signal_action',
			[
				'label' => esc_html__( 'Call to action', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'signal_button_text',
			[
				'label'   => esc_html__( 'Button text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['button_text'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'signal_button_link',
			[
				'label'   => esc_html__( 'Button link', 'nexora-elementor' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'url'         => $ce_d['button_url'],
					'is_external' => 'true',
					'nofollow'    => 'false',
				),
			]
		);

		$widget->end_controls_section();
	}
}
