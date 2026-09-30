<?php
/**
 * Content controls for PH nav.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! function_exists( 'nexora_ph_nav_register_content_controls' ) ) {
	/**
	 * Register content controls. The bar comes from a WordPress menu.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_nav_register_content_controls( $widget ): void {
		if ( ! $widget instanceof \Elementor\Widget_Base || ! class_exists( Controls_Manager::class ) || ! class_exists( Repeater::class ) ) {
			return;
		}

		if ( ! function_exists( 'nexora_ph_nav_default_cards' ) ) {
			require_once NEXORA_ELE_PATH . 'widgets/ph-nav/data.php';
		}

		$logo  = defined( 'NEXORA_ELE_URL' ) ? NEXORA_ELE_URL . 'assets/images/prospects/logo-color.png' : '';
		$menus = [
			'' => esc_html__( 'Select a menu', 'nexora-elementor' ),
		];
		if ( function_exists( 'wp_get_nav_menus' ) ) {
			foreach ( wp_get_nav_menus() as $menu ) {
				if ( ! is_object( $menu ) ) {
					continue;
				}
				$menus[ (string) $menu->term_id ] = $menu->name;
			}
		}

		$widget->start_controls_section(
			'section_nav_logo',
			[
				'label' => esc_html__( 'Logo', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'logo_image',
			[
				'label'   => esc_html__( 'Image', 'nexora-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => $logo,
				],
			]
		);

		$widget->add_control(
			'logo_alt',
			[
				'label'   => esc_html__( 'Alt', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Prospects Hive',
			]
		);

		$widget->add_control(
			'logo_url',
			[
				'label'   => esc_html__( 'Link', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '#',
			]
		);

		$widget->add_control(
			'logo_label',
			[
				'label'       => esc_html__( 'Accessible label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Prospects Hive home',
				'label_block' => true,
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_nav_menu',
			[
				'label' => esc_html__( 'Menu', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'nav_menu',
			[
				'label'       => esc_html__( 'Menu', 'nexora-elementor' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => $menus,
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Top-level items come from this menu. Check Mega menu on one item under Appearance → Menus.', 'nexora-elementor' ),
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_nav_mega',
			[
				'label' => esc_html__( 'Mega menu', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'mega_view_text',
			[
				'label'       => esc_html__( 'View all text', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'View all services',
				'label_block' => true,
			]
		);

		$widget->add_control(
			'mega_view_url',
			[
				'label'       => esc_html__( 'View all link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '#services',
				'label_block' => true,
			]
		);

		$cards = new Repeater();

		$cards->add_control(
			'card_label',
			[
				'label'       => esc_html__( 'Label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$cards->add_control(
			'card_description',
			[
				'label'   => esc_html__( 'Description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => '',
				'rows'    => 2,
			]
		);

		$cards->add_control(
			'card_url',
			[
				'label'       => esc_html__( 'Link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$cards->add_control(
			'card_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => '',
					'library' => '',
				],
			]
		);

		$cards->add_control(
			'card_seed_svg',
			[
				'type'    => Controls_Manager::HIDDEN,
				'default' => '',
			]
		);

		$widget->add_control(
			'mega_cards',
			[
				'label'       => esc_html__( 'Service cards', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $cards->get_controls(),
				'default'     => nexora_ph_nav_default_cards(),
				'title_field' => '{{{ card_label }}}',
			]
		);

		$widget->add_control(
			'mega_others_label',
			[
				'label'       => esc_html__( 'Other services label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Other services',
				'label_block' => true,
			]
		);

		$others = new Repeater();

		$others->add_control(
			'other_label',
			[
				'label'       => esc_html__( 'Label', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$others->add_control(
			'other_url',
			[
				'label'       => esc_html__( 'Link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			]
		);

		$others->add_control(
			'other_icon',
			[
				'label'   => esc_html__( 'Icon', 'nexora-elementor' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => '',
					'library' => '',
				],
			]
		);

		$others->add_control(
			'other_seed_svg',
			[
				'type'    => Controls_Manager::HIDDEN,
				'default' => '',
			]
		);

		$widget->add_control(
			'mega_others',
			[
				'label'       => esc_html__( 'Other services', 'nexora-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $others->get_controls(),
				'default'     => nexora_ph_nav_default_others(),
				'title_field' => '{{{ other_label }}}',
			]
		);

		$widget->add_control(
			'mega_cta_title',
			[
				'label'       => esc_html__( 'Call to action title', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Book a Call',
				'label_block' => true,
			]
		);

		$widget->add_control(
			'mega_cta_text',
			[
				'label'   => esc_html__( 'Call to action description', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => "See how Prospects Hive's outbound system can fill your pipeline.",
				'rows'    => 2,
			]
		);

		$widget->add_control(
			'mega_cta_url',
			[
				'label'       => esc_html__( 'Call to action link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'https://tidycal.com/prospectshive/discovery-call',
				'label_block' => true,
			]
		);

		$widget->end_controls_section();

		$widget->start_controls_section(
			'section_nav_button',
			[
				'label' => esc_html__( 'Button', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'button_text',
			[
				'label'   => esc_html__( 'Text', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Book A Call',
			]
		);

		$widget->add_control(
			'button_url',
			[
				'label'       => esc_html__( 'Link', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'https://tidycal.com/prospectshive/discovery-call',
				'label_block' => true,
			]
		);

		$widget->end_controls_section();
	}
}
