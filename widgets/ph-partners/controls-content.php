<?php
/**
 * Content controls for PH partners.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

if ( ! function_exists( 'nexora_ph_partners_register_content_controls' ) ) {
	/**
	 * Register content controls. Defaults are the original design copy.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_partners_register_content_controls( $widget ): void {
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
			'section_partners_template',
			[
				'label' => esc_html__( 'Template style', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'partners_template',
			[
				'label'       => esc_html__( 'Template style', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default'     => 'default',
				'options'     => [
					'default'   => esc_html__( 'Default (side heading + badge list)', 'nexora-elementor' ),
					'b2b-cards' => esc_html__( 'B2B badge cards (centered heading + 5 cards)', 'nexora-elementor' ),
				],
			]
		);

		$widget->end_controls_section();

		$b2b_condition = [ 'partners_template' => 'b2b-cards' ];

		require_once __DIR__ . '/defaults-b2b.php';
		$d = nexora_ph_partners_b2b_defaults();

		$widget->start_controls_section(
			'section_partners_b2b_header',
			[
				'label'     => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => $b2b_condition,
			]
		);

		$widget->add_control(
			'pr_heading_tag',
			[
				'label'       => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default'     => $d['heading_tag'],
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
			]
		);

		$widget->add_control(
			'pr_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $d['eyebrow'],
				'dynamic' => [ 'active' => true ],
			]
		);

		$widget->add_control(
			'pr_heading',
			[
				'label'   => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $d['heading'],
				'dynamic' => [ 'active' => true ],
			]
		);

		$widget->add_control(
			'pr_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $d['highlight'],
				'dynamic'     => [ 'active' => true ],
				'description' => esc_html__( 'Shown with the gradient on the line after the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'pr_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $d['description'],
				'dynamic' => [ 'active' => true ],
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_partners_b2b_cards',
			[
				'label'     => esc_html__( 'Partner cards', 'nexora-elementor' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => $b2b_condition,
			]
		);

		$cards = new \Elementor\Repeater();

		$cards->add_control(
			'card_image',
			[
				'label' => esc_html__( 'Badge image', 'nexora-elementor' ),
				'type'  => Controls_Manager::MEDIA,
			]
		);

		$cards->add_control(
			'card_alt',
			[
				'label'   => esc_html__( 'Image alt', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
			]
		);

		$cards->add_control(
			'card_name',
			[
				'label'   => esc_html__( 'Name', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
			]
		);

		$cards->add_control(
			'card_chip',
			[
				'label'       => esc_html__( 'Badge text', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [ 'active' => true ],
				'description' => esc_html__( 'Leave empty to hide the pill.', 'nexora-elementor' ),
			]
		);

		$cards->add_control(
			'card_summary',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'dynamic' => [ 'active' => true ],
			]
		);

		$widget->add_control(
			'pr_cards',
			[
				'label'         => esc_html__( 'Partner cards', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'render_type'   => 'template',
				'fields'        => $cards->get_controls(),
				'default'       => $d['cards'],
				'title_field'   => '{{{ card_name }}}',
				'prevent_empty' => false,
			]
		);

		$widget->end_controls_section();

		if ( ! function_exists( 'nexora_ph_register_mapped_controls' ) ) {
			require_once NEXORA_ELE_PATH . 'includes/ph-content.php';
		}
		nexora_ph_register_mapped_controls( $widget, 'partners' );
	}
}
