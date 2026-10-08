<?php
/**
 * Content controls for PH pricing.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_pricing_register_content_controls' ) ) {
	/**
	 * Register content controls. Defaults are the original design copy.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_pricing_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base || ! class_exists( Controls_Manager::class ) || ! class_exists( Repeater::class ) ) {
			return;
		}

		if ( ! function_exists( 'nexora_ph_pricing_defaults' ) ) {
			require_once NEXORA_ELE_PATH . 'widgets/ph-pricing/data.php';
		}

		$defaults = nexora_ph_pricing_defaults();
		$tags     = [
			'h1'   => 'H1',
			'h2'   => 'H2',
			'h3'   => 'H3',
			'h4'   => 'H4',
			'h5'   => 'H5',
			'h6'   => 'H6',
			'div'  => 'div',
			'span' => 'span',
			'p'    => 'p',
		];

		$widget->start_controls_section(
			'section_pricing_header',
			[
				'label' => esc_html__( 'Header', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'_schema_version',
			[
				'type'    => Controls_Manager::HIDDEN,
				'default' => '2',
			]
		);

		$widget->add_control(
			'eyebrow',
			[
				'label'   => esc_html__( 'Eyebrow', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $defaults['eyebrow'],
			]
		);

		$widget->add_control(
			'heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $defaults['heading'],
				'label_block' => true,
			]
		);

		$widget->add_control(
			'heading_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $defaults['heading_tag'],
				'options' => $tags,
			]
		);

		$widget->add_control(
			'highlight',
			[
				'label'   => esc_html__( 'Highlight', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $defaults['highlight'],
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_pricing_availability',
			[
				'label' => esc_html__( 'Availability', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$spots = new Repeater();

		$spots->add_control(
			'spots_note',
			[
				'label'       => esc_html__( 'Note', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 2,
				'description' => esc_html__( 'First line, then a new line for the second line.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'spots_notes',
			[
				'label'       => esc_html__( 'Notes', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $spots->get_controls(),
				'default'     => [
					[
						'spots_note' => $defaults['spots_note'],
					],
				],
				'title_field' => '{{{ spots_note }}}',
				'description' => esc_html__( 'Shown one at a time on the sticky note.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'spots_rotate',
			[
				'label'        => esc_html__( 'Auto rotate', 'nexora-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'label_on'     => esc_html__( 'On', 'nexora-elementor' ),
				'label_off'    => esc_html__( 'Off', 'nexora-elementor' ),
				'return_value' => 'yes',
				'description'  => esc_html__( 'Rotate through the notes, one at a time.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'spots_interval',
			[
				'label'     => esc_html__( 'Seconds per note', 'nexora-elementor' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4,
				'min'       => 1,
				'step'      => 1,
				'condition' => [
					'spots_rotate' => 'yes',
				],
			]
		);

		$widget->add_control(
			'spots_label',
			[
				'label'       => esc_html__( 'Accessible label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $defaults['spots_label'],
				'label_block' => true,
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_pricing_plans',
			[
				'label' => esc_html__( 'Plans', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$plans = new Repeater();
		$standard = [ 'plan_template' => 'standard' ];
		$custom   = [ 'plan_template' => 'custom' ];

		$plans->add_control(
			'plan_template',
			[
				'label'   => esc_html__( 'Template', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard' => esc_html__( 'Standard', 'nexora-elementor' ),
					'custom'   => esc_html__( 'Custom', 'nexora-elementor' ),
				],
			]
		);

		$plans->add_control(
			'plan_name',
			[
				'label'       => esc_html__( 'Name', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Tab label. On a Standard plan this is also the card title.', 'nexora-elementor' ),
			]
		);

		$plans->add_control(
			'plan_head_card',
			[
				'label'     => esc_html__( 'Price card', 'nexora-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_badge',
			[
				'label'     => esc_html__( 'Badge', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_badge_style',
			[
				'label'     => esc_html__( 'Badge style', 'nexora-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'outline',
				'options'   => [
					'outline'   => esc_html__( 'Outline', 'nexora-elementor' ),
					'highlight' => esc_html__( 'Highlight', 'nexora-elementor' ),
				],
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_description',
			[
				'label'     => esc_html__( 'Description', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'rows'      => 3,
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_price',
			[
				'label'     => esc_html__( 'Price', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_period',
			[
				'label'     => esc_html__( 'Period', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '/ month',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_term',
			[
				'label'     => esc_html__( 'Term', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_total',
			[
				'label'     => esc_html__( 'Total', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_upfront',
			[
				'label'     => esc_html__( 'Upfront price', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_savings',
			[
				'label'     => esc_html__( 'Savings label', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_fit',
			[
				'label'       => esc_html__( 'Fit label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => $standard,
			]
		);

		$plans->add_control(
			'plan_included',
			[
				'label'       => esc_html__( 'Included items', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 6,
				'description' => esc_html__( 'One item per line. Blank lines are skipped.', 'nexora-elementor' ),
				'condition'   => $standard,
			]
		);

		$plans->add_control(
			'plan_day_title',
			[
				'label'     => esc_html__( 'At day 90 title', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => 'At day 90',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_day_body',
			[
				'label'     => esc_html__( 'At day 90 text', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'rows'      => 3,
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_button_text',
			[
				'label'     => esc_html__( 'Button text', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_button_url',
			[
				'label'       => esc_html__( 'Button link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => $standard,
			]
		);

		$plans->add_control(
			'plan_head_build',
			[
				'label'     => esc_html__( '90-day build', 'nexora-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_build_heading',
			[
				'label'       => esc_html__( 'Build heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'What we build in 90 days',
				'label_block' => true,
				'condition'   => $standard,
			]
		);

		$plans->add_control(
			'plan_build_description',
			[
				'label'     => esc_html__( 'Build description', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'rows'      => 3,
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_phase',
			[
				'label'       => esc_html__( 'Phase label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => $standard,
			]
		);

		foreach ( [ 1, 2, 3 ] as $month ) {
			$plans->add_control(
				'plan_m' . $month . '_head',
				[
					'label'     => sprintf(
						/* translators: %d: month number */
						esc_html__( 'Month %d', 'nexora-elementor' ),
						$month
					),
					'type'      => Controls_Manager::HEADING,
					'condition' => $standard,
				]
			);

			$plans->add_control(
				'plan_m' . $month . '_label',
				[
					'label'     => esc_html__( 'Label', 'nexora-elementor' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'Month ' . $month,
					'condition' => $standard,
				]
			);

			$plans->add_control(
				'plan_m' . $month . '_title',
				[
					'label'       => esc_html__( 'Title', 'nexora-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'condition'   => $standard,
				]
			);

			$plans->add_control(
				'plan_m' . $month . '_tasks',
				[
					'label'       => esc_html__( 'Tasks', 'nexora-elementor' ),
					'type'        => Controls_Manager::TEXTAREA,
					'default'     => '',
					'rows'        => 5,
					'description' => esc_html__( 'One task per line. Blank lines are skipped.', 'nexora-elementor' ),
					'condition'   => $standard,
				]
			);

			$plans->add_control(
				'plan_m' . $month . '_output',
				[
					'label'       => esc_html__( 'Output', 'nexora-elementor' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'condition'   => $standard,
				]
			);
		}

		$plans->add_control(
			'plan_head_outcomes',
			[
				'label'     => esc_html__( 'Outcomes', 'nexora-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => $standard,
			]
		);

		$plans->add_control(
			'plan_outcomes_heading',
			[
				'label'       => esc_html__( 'Outcomes heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => $standard,
			]
		);

		$plans->add_control(
			'plan_outcomes',
			[
				'label'       => esc_html__( 'Outcome cards', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 6,
				'description' => esc_html__( 'One card per line: Title | text. A line without | is a title only. Blank lines are skipped.', 'nexora-elementor' ),
				'condition'   => $standard,
			]
		);

		$plans->add_control(
			'plan_head_custom',
			[
				'label'     => esc_html__( 'Custom card', 'nexora-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'condition' => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_badge',
			[
				'label'     => esc_html__( 'Badge', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_heading_tag',
			[
				'label'     => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h3',
				'options'   => $tags,
				'condition' => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_description',
			[
				'label'     => esc_html__( 'Description', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'rows'      => 3,
				'condition' => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_tags',
			[
				'label'       => esc_html__( 'Tags', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'rows'        => 5,
				'description' => esc_html__( 'One tag per line. Blank lines are skipped.', 'nexora-elementor' ),
				'condition'   => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_price',
			[
				'label'     => esc_html__( 'Price word', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_note',
			[
				'label'       => esc_html__( 'Note', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_button_text',
			[
				'label'     => esc_html__( 'Button text', 'nexora-elementor' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => $custom,
			]
		);

		$plans->add_control(
			'plan_custom_button_url',
			[
				'label'       => esc_html__( 'Button link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => $custom,
			]
		);

		$widget->add_control(
			'plans',
			[
				'label'       => esc_html__( 'Plans', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $plans->get_controls(),
				'default'     => nexora_ph_pricing_default_plans(),
				'title_field' => '{{{ plan_name }}}',
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_pricing_after',
			[
				'label' => esc_html__( 'After 90 days', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'after_heading',
			[
				'label'       => esc_html__( 'Heading', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $defaults['after_heading'],
				'label_block' => true,
			]
		);

		$widget->add_control(
			'after_tag',
			[
				'label'   => esc_html__( 'HTML Tag', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $defaults['after_tag'],
				'options' => $tags,
			]
		);

		$widget->add_control(
			'after_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => $defaults['after_description'],
				'rows'    => 3,
			]
		);

		$widget->add_control(
			'after_chips',
			[
				'label'       => esc_html__( 'Chips', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => $defaults['after_chips'],
				'rows'        => 4,
				'description' => esc_html__( 'One chip per line. Blank lines are skipped.', 'nexora-elementor' ),
			]
		);

		$widget->add_control(
			'after_button_text',
			[
				'label'   => esc_html__( 'Button text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => $defaults['after_button_text'],
			]
		);

		$widget->add_control(
			'after_button_url',
			[
				'label'       => esc_html__( 'Button link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => $defaults['after_button_url'],
				'label_block' => true,
			]
		);

		$widget->end_controls_section();
	}
}
