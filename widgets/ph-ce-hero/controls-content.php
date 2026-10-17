<?php
/**
 * Content controls for CE Hero. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_hero_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_hero_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_hero_defaults();

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
			'section_hero_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'hero_heading_tag',
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
			'hero_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_heading',
			[
				'label'   => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['heading'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient at the end of the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'hero_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['description'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_hero_buttons',
			[
				'label' => esc_html__( 'Buttons', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'hero_button_text',
			[
				'label'   => esc_html__( 'Primary button text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['button_text'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_button_link',
			[
				'label'   => esc_html__( 'Primary button link', 'nexora-elementor' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'url'         => $ce_d['button_url'],
					'is_external' => 'true',
					'nofollow'    => 'false',
				),
			]
		);

		$widget->add_control(
			'hero_secondary_text',
			[
				'label'   => esc_html__( 'Secondary button text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['secondary_text'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_secondary_link',
			[
				'label'   => esc_html__( 'Secondary button link', 'nexora-elementor' ),
				'type'    => Controls_Manager::URL,
				'default' => array(
					'url'         => $ce_d['secondary_url'],
					'is_external' => 'false',
					'nofollow'    => 'false',
				),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_hero_campaign',
			[
				'label' => esc_html__( 'Campaign card', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'hero_campaign_title',
			[
				'label'   => esc_html__( 'Campaign title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['campaign_title'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_campaign_subtitle',
			[
				'label'   => esc_html__( 'Subtitle', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['campaign_subtitle'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_live_text',
			[
				'label'   => esc_html__( 'Live badge text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['live_text'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_meter_label',
			[
				'label'   => esc_html__( 'Inbox meter label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['meter_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_meter_value',
			[
				'label'   => esc_html__( 'Inbox meter value', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['meter_value'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps_repeater = new Repeater();

		$steps_repeater->add_control(
			'hero_step_state',
			[
				'label'       => esc_html__( 'State', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'sent'    => esc_html__( 'Sent', 'nexora-elementor' ),
					'running' => esc_html__( 'Running', 'nexora-elementor' ),
					'queued'  => esc_html__( 'Queued', 'nexora-elementor' ),
				),
				'default'     => 'sent',
				'render_type' => 'template',
			]
		);

		$steps_repeater->add_control(
			'hero_step_title',
			[
				'label'   => esc_html__( 'Step title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps_repeater->add_control(
			'hero_step_note',
			[
				'label'   => esc_html__( 'Step note', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps_repeater->add_control(
			'hero_step_status',
			[
				'label'   => esc_html__( 'Status label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_steps',
			[
				'label'         => esc_html__( 'Sequence steps', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $steps_repeater->get_controls(),
				'default'       => $ce_d['steps'],
				'title_field'   => 'hero_step_title',
				'prevent_empty' => false,
				'render_type'   => 'template',
			]
		);

		$results_repeater = new Repeater();

		$results_repeater->add_control(
			'hero_result_value',
			[
				'label'   => esc_html__( 'Value', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$results_repeater->add_control(
			'hero_result_label',
			[
				'label'   => esc_html__( 'Label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_results',
			[
				'label'         => esc_html__( 'Result numbers', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $results_repeater->get_controls(),
				'default'       => $ce_d['results'],
				'title_field'   => 'hero_result_value',
				'prevent_empty' => false,
				'render_type'   => 'template',
			]
		);

		$badges_repeater = new Repeater();

		$badges_repeater->add_control(
			'hero_badge_title',
			[
				'label'   => esc_html__( 'Title', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$badges_repeater->add_control(
			'hero_badge_sub',
			[
				'label'   => esc_html__( 'Subtitle', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_badges',
			[
				'label'         => esc_html__( 'Floating badges', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $badges_repeater->get_controls(),
				'default'       => $ce_d['badges'],
				'title_field'   => 'hero_badge_title',
				'prevent_empty' => false,
				'render_type'   => 'template',
				'description'   => esc_html__( 'Rows cycle through the two badge designs of the template (position and icon).', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_hero_logos',
			[
				'label' => esc_html__( 'Logo strip', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'hero_stat_number',
			[
				'label'   => esc_html__( 'Stat number', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['stat_number'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_stat_label',
			[
				'label'   => esc_html__( 'Stat label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['stat_label'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_stat_desc',
			[
				'label'   => esc_html__( 'Stat description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['stat_desc'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$logos_repeater = new Repeater();

		$logos_repeater->add_control(
			'hero_logo_image',
			[
				'label'   => esc_html__( 'Image', 'nexora-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => 'assets/logoc1.png',
					'id'  => '',
				),
			]
		);

		$logos_repeater->add_control(
			'hero_logo_alt',
			[
				'label'       => esc_html__( 'Image alt', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_logos',
			[
				'label'         => esc_html__( 'Client logos', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $logos_repeater->get_controls(),
				'default'       => $ce_d['logos'],
				'title_field'   => 'hero_logo_alt',
				'prevent_empty' => false,
				'render_type'   => 'template',
			]
		);

		$widget->add_control(
			'hero_cert_text',
			[
				'label'       => esc_html__( 'Certified partners text', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['cert_text'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Plain-text part before the bold partner list.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'hero_cert_bold',
			[
				'label'       => esc_html__( 'Certified partners bold', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['cert_bold'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Partner names shown in bold.', 'nexora-elementor' ),
			]
		);

		$partners_repeater = new Repeater();

		$partners_repeater->add_control(
			'hero_partner_image',
			[
				'label'   => esc_html__( 'Image', 'nexora-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => 'assets/badge-instantly.webp',
					'id'  => '',
				),
			]
		);

		$partners_repeater->add_control(
			'hero_partner_alt',
			[
				'label'       => esc_html__( 'Image alt', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'hero_partners',
			[
				'label'         => esc_html__( 'Partner badges', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $partners_repeater->get_controls(),
				'default'       => $ce_d['partners'],
				'title_field'   => 'hero_partner_alt',
				'prevent_empty' => false,
				'render_type'   => 'template',
			]
		);

		$widget->end_controls_section();
	}
}
