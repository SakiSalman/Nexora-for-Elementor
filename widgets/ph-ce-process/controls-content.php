<?php
/**
 * Content controls for CE Process. Phase 2: every visible string is editable; defaults
 * are the design copy from defaults.php.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_ce_process_register_content_controls' ) ) {
	/**
	 * Register content controls.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_ce_process_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base ) {
			return;
		}

		// Pulled in here so the defaults function is available in this scope.
		require_once __DIR__ . '/defaults.php';

		$ce_d = nexora_ph_ce_process_defaults();

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
			'section_process_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'process_heading_tag',
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
			'process_eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $ce_d['eyebrow'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->add_control(
			'process_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['heading'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Text before the highlighted words.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'process_highlight',
			[
				'label'       => esc_html__( 'Highlighted words', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $ce_d['highlight'],
				'dynamic'     => array( 'active' => true ),
				'description' => esc_html__( 'Shown with the gradient at the end of the heading.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'process_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $ce_d['description'],
				'dynamic' => array( 'active' => true ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_process_steps',
			[
				'label' => esc_html__( 'Process steps', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$steps = new Repeater();

		$steps->add_control(
			'process_step_label',
			[
				'label'   => esc_html__( 'Step label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps->add_control(
			'process_step_title_tag',
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

		$steps->add_control(
			'process_step_title',
			[
				'label'       => esc_html__( 'Title', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic'     => array( 'active' => true ),
			]
		);

		$steps->add_control(
			'process_step_desc',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps->add_control(
			'process_step_tools',
			[
				'label'   => esc_html__( 'Tools', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps->add_control(
			'process_step_timeline',
			[
				'label'   => esc_html__( 'Timeline', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps->add_control(
			'process_step_outcome_label',
			[
				'label'   => esc_html__( 'Outcome label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'What you get',
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps->add_control(
			'process_step_outcome',
			[
				'label'   => esc_html__( 'Outcome', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => array( 'active' => true ),
			]
		);

		$steps->add_control(
			'process_card_bg',
			[
				'label'       => esc_html__( 'Card background override', 'nexora-elementor' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => '',
				'description' => esc_html__( 'Leave empty to use the Style → Colors step card pickers.', 'nexora-elementor' ),
			]
		);

		$steps->add_control(
			'process_step_icon',
			[
				'label'       => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'802fbc7be4' => esc_html__( 'Check circle', 'nexora-elementor' ),
				],
				'default'     => '802fbc7be4',
				'description' => esc_html__( 'Design icon set for the outcome badge.', 'nexora-elementor' ),
			]
		);

		$steps->add_control(
			'process_visual',
			[
				'label'       => esc_html__( 'Visual', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => [
					'v1' => esc_html__( 'Lead funnel', 'nexora-elementor' ),
					'v2' => esc_html__( 'Mailbox warm-up', 'nexora-elementor' ),
					'v3' => esc_html__( 'Email preview', 'nexora-elementor' ),
					'v4' => esc_html__( 'CRM board', 'nexora-elementor' ),
					'v5' => esc_html__( 'Results chart', 'nexora-elementor' ),
				],
				'default'     => 'v1',
				'description' => esc_html__( 'Picks the panel visual; its fields appear below.', 'nexora-elementor' ),
				'render_type' => 'template',
			]
		);

		$steps->add_control(
			'process_v1_market_label',
			[
				'label'     => esc_html__( 'Market label', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v1']['process_v1_market_label'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v1' ],
			]
		);

		$steps->add_control(
			'process_v1_market_value',
			[
				'label'     => esc_html__( 'Market number', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v1']['process_v1_market_value'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v1' ],
			]
		);

		$steps->add_control(
			'process_v1_market_unit',
			[
				'label'     => esc_html__( 'Market unit', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v1']['process_v1_market_unit'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v1' ],
			]
		);

		$steps->add_control(
			'process_v1_chips',
			[
				'label'       => esc_html__( 'Chips', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => $ce_d['visual_defaults']['v1']['process_v1_chips'],
				'description' => esc_html__( 'One chip per line.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v1' ],
			]
		);

		$steps->add_control(
			'process_v1_list_label',
			[
				'label'     => esc_html__( 'List label', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v1']['process_v1_list_label'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v1' ],
			]
		);

		$steps->add_control(
			'process_v1_list_value',
			[
				'label'     => esc_html__( 'List number', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v1']['process_v1_list_value'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v1' ],
			]
		);

		$steps->add_control(
			'process_v1_list_unit',
			[
				'label'     => esc_html__( 'List unit', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v1']['process_v1_list_unit'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v1' ],
			]
		);

		$steps->add_control(
			'process_v2_auth',
			[
				'label'       => esc_html__( 'Authentication chips', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => $ce_d['visual_defaults']['v2']['process_v2_auth'],
				'description' => esc_html__( 'One chip per line.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v2' ],
			]
		);

		$steps->add_control(
			'process_v2_rows',
			[
				'label'       => esc_html__( 'Mailbox rows', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => $ce_d['visual_defaults']['v2']['process_v2_rows'],
				'description' => esc_html__( 'One mailbox per line as "address | status". Rows with the status "Ready" show the finished state.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v2' ],
			]
		);

		$steps->add_control(
			'process_v3_counter',
			[
				'label'     => esc_html__( 'Counter text', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v3']['process_v3_counter'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v3' ],
			]
		);

		$steps->add_control(
			'process_v3_subject',
			[
				'label'     => esc_html__( 'Email subject', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v3']['process_v3_subject'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v3' ],
			]
		);

		$steps->add_control(
			'process_v3_body',
			[
				'label'       => esc_html__( 'Email body', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => $ce_d['visual_defaults']['v3']['process_v3_body'],
				'description' => esc_html__( 'Wrap words in [[double brackets]] to highlight them.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v3' ],
			]
		);

		$steps->add_control(
			'process_v3_body2',
			[
				'label'       => esc_html__( 'Second paragraph', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 2,
				'default'     => $ce_d['visual_defaults']['v3']['process_v3_body2'],
				'description' => esc_html__( 'Wrap words in [[double brackets]] to highlight them.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v3' ],
			]
		);

		$steps->add_control(
			'process_v3_steps',
			[
				'label'       => esc_html__( 'Sequence steps', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => $ce_d['visual_defaults']['v3']['process_v3_steps'],
				'description' => esc_html__( 'One step per line as "Day | label". The first step is highlighted.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v3' ],
			]
		);

		$steps->add_control(
			'process_v4_columns',
			[
				'label'       => esc_html__( 'Board columns', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 4,
				'default'     => $ce_d['visual_defaults']['v4']['process_v4_columns'],
				'description' => esc_html__( 'One column per line as "Column name | card | card …". The last card of the last column gets the highlighted style.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v4' ],
			]
		);

		$steps->add_control(
			'process_v4_mover_text',
			[
				'label'     => esc_html__( 'Moving card text', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v4']['process_v4_mover_text'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v4' ],
			]
		);

		$steps->add_control(
			'process_v4_mover_tag',
			[
				'label'     => esc_html__( 'Moving card tag', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v4']['process_v4_mover_tag'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v4' ],
			]
		);

		$steps->add_control(
			'process_v5_bars',
			[
				'label'       => esc_html__( 'Bar heights', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => $ce_d['visual_defaults']['v5']['process_v5_bars'],
				'description' => esc_html__( 'One number (percent) per line.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v5' ],
			]
		);

		$steps->add_control(
			'process_v5_labels',
			[
				'label'       => esc_html__( 'Bar labels', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => $ce_d['visual_defaults']['v5']['process_v5_labels'],
				'description' => esc_html__( 'One label per line, shown under the bars.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v5' ],
			]
		);

		$steps->add_control(
			'process_v5_label',
			[
				'label'     => esc_html__( 'Chart label', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v5']['process_v5_label'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v5' ],
			]
		);

		$steps->add_control(
			'process_v5_value',
			[
				'label'     => esc_html__( 'Chart value', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => $ce_d['visual_defaults']['v5']['process_v5_value'],
				'dynamic'   => array( 'active' => true ),
				'condition' => [ 'process_visual' => 'v5' ],
			]
		);

		$steps->add_control(
			'process_v5_chips',
			[
				'label'       => esc_html__( 'Chart chips', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => $ce_d['visual_defaults']['v5']['process_v5_chips'],
				'description' => esc_html__( 'One chip per line.', 'nexora-elementor' ),
				'dynamic'     => array( 'active' => true ),
				'condition'   => [ 'process_visual' => 'v5' ],
			]
		);

		$ce_feature_icons = [
			'dc5a359c62' => esc_html__( 'ICP filters', 'nexora-elementor' ),
			'7b1e267bee' => esc_html__( 'Buyer roles', 'nexora-elementor' ),
			'350f943d49' => esc_html__( 'Verified data', 'nexora-elementor' ),
			'b73a073a52' => esc_html__( 'Protected domains', 'nexora-elementor' ),
			'605cd96548' => esc_html__( 'Authentication', 'nexora-elementor' ),
			'42bd279770' => esc_html__( 'Warm-up', 'nexora-elementor' ),
			'5b5fc3a5af' => esc_html__( 'Personal copy', 'nexora-elementor' ),
			'68bf95dece' => esc_html__( 'Multi-step sequences', 'nexora-elementor' ),
			'8f90dd285f' => esc_html__( 'Automated sending', 'nexora-elementor' ),
			'281daaa3c1' => esc_html__( 'CRM routing', 'nexora-elementor' ),
			'187955e2a1' => esc_html__( 'Meeting booking', 'nexora-elementor' ),
		];

		// Three fixed feature slots per step (the design's 3-column fw-cards row).
		foreach ( array( 1, 2, 3 ) as $ce_f ) {
			$steps->add_control(
				'process_f' . $ce_f . '_icon',
				[
					'label'   => esc_html__( 'Feature ' . $ce_f . ' icon', 'nexora-elementor' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $ce_feature_icons,
					'default' => $ce_d['steps'][0]['process_f' . $ce_f . '_icon'],
				]
			);

			$steps->add_control(
				'process_f' . $ce_f . '_title_tag',
				[
					'label'   => esc_html__( 'Feature ' . $ce_f . ' HTML Tag', 'nexora-elementor' ),
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
					'default' => 'h4',
				]
			);

			$steps->add_control(
				'process_f' . $ce_f . '_title',
				[
					'label'       => esc_html__( 'Feature ' . $ce_f . ' title', 'nexora-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'label_block' => true,
					'dynamic'     => array( 'active' => true ),
				]
			);

			$steps->add_control(
				'process_f' . $ce_f . '_desc',
				[
					'label'   => esc_html__( 'Feature ' . $ce_f . ' description', 'nexora-elementor' ),
					'type'    => Controls_Manager::TEXTAREA,
					'rows'    => 3,
					'dynamic' => array( 'active' => true ),
				]
			);
		}

		$widget->add_control(
			'process_steps',
			[
				'label'         => esc_html__( 'Process steps', 'nexora-elementor' ),
				'type'          => Controls_Manager::REPEATER,
				'fields'        => $steps->get_controls(),
				'default'       => $ce_d['steps'],
				'title_field'   => 'process_step_label',
				'prevent_empty' => false,
				'render_type'   => 'template',
			]
		);

		$widget->end_controls_section();
	}
}
