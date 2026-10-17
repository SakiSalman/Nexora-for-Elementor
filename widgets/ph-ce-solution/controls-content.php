<?php
/**
 * Content controls for CE solution. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_solution_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_solution_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_solution_defaults();

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
			'section_solution_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'solution_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'solution_heading_tag',
			[
				'label'       => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
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
				'default'     => $ce_d['heading_tag'],
				'render_type' => 'template',
			]
		);

		$widget->add_control(
			'solution_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['heading'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text before the highlighted words.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'solution_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient at the end of the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'solution_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['description'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_solution_flow',
			[
				'label' => esc_html__( 'Delivery flow', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'solution_flow_label',
			[
				'label'       => esc_html__( 'Delivery flow label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['flow_label'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Accessible label on the pill list in the sticky column.', 'nexora-elementor' ),
			]
		);

		$flow = new Repeater();

		$flow->add_control(
			'solution_pill',
			[
				'label'       => esc_html__( 'Label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'solution_flow_rows',
			[
				'label'         => esc_html__( 'Delivery flow', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $flow->get_controls(),
				'default'       => $ce_d['flow'],
				'title_field'   => 'solution_pill',
				'prevent_empty' => false,
				'render_type'   => 'template',
				'description'   => esc_html__( 'The sticky pill list. Independent of the card count.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_solution_action',
			[
				'label' => esc_html__( 'Call to action', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'solution_button_text',
			[
				'label'   => esc_html__( 'Button text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['button_text'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'solution_button_link',
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

		$widget->start_controls_section(
			'section_solution_cards',
			[
				'label' => esc_html__( 'Solution cards', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$cards = new Repeater();

		$cards->add_control(
			'solution_card_icon',
			[
				'label'       => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'5c15cf1a97' => esc_html__( 'Target the right buyers', 'nexora-elementor' ),
					'b830b08605' => esc_html__( 'Improve cold email deliverability', 'nexora-elementor' ),
					'20614a6dbe' => esc_html__( 'Human touch in every outreach', 'nexora-elementor' ),
					'7286789170' => esc_html__( 'Qualified sales meetings', 'nexora-elementor' ),
				],
				'default'     => '5c15cf1a97',
				'description' => esc_html__( 'Design icon set for this section.', 'nexora-elementor' ),
			]
		);

		$cards->add_control(
			'solution_card_title',
			[
				'label'       => esc_html__( 'Title', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			]
		);

		$cards->add_control(
			'solution_card_title_tag',
			[
				'label'       => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
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
				'default'     => 'h3',
				'render_type' => 'template',
			]
		);

		$cards->add_control(
			'solution_card_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'rows'    => 4,
				'dynamic' => array( 'active' => true ),
			]
		);

		$cards->add_control(
			'solution_card_result',
			[
				'label'       => esc_html__( 'Result text', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'The small result strip at the bottom of the card.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'solution_cards',
			[
				'label'         => esc_html__( 'Solution cards', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $cards->get_controls(),
				'default'       => $ce_d['cards'],
				'title_field'   => 'solution_card_title',
				'prevent_empty' => false,
				'render_type'   => 'template',
				'description'   => esc_html__( 'The card number (01, 02, …) comes from the row position.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();
	}
}
