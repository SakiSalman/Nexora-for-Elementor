<?php
/**
 * Content controls for PH video.
 *
 * @param \Elementor\Widget_Base $widget Widget instance.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Elementor\Controls_Manager;

if ( ! function_exists( 'nexora_ph_video_register_content_controls' ) ) {
	/**
	 * Register content controls. Defaults are the original design copy.
	 *
	 * @param \Elementor\Widget_Base $widget Widget.
	 */
	function nexora_ph_video_register_content_controls( $widget ): void {
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
			'section_video_player',
			[
				'label' => esc_html__( 'Video player', 'nexora-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$widget->add_control(
			'video_source',
			[
				'label'   => esc_html__( 'Source', 'nexora-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'self',
				'options' => [
					'self'    => esc_html__( 'Self Hosted', 'nexora-elementor' ),
					'youtube' => esc_html__( 'YouTube', 'nexora-elementor' ),
					'vimeo'   => esc_html__( 'Vimeo', 'nexora-elementor' ),
				],
			]
		);

		$widget->add_control(
			'video_file',
			[
				'label'      => esc_html__( 'Video', 'nexora-elementor' ),
				'type'       => Controls_Manager::MEDIA,
				'media_types' => [ 'video' ],
				'condition'  => [
					'video_source' => 'self',
				],
			]
		);

		$widget->add_control(
			'video_url',
			[
				'label'       => esc_html__( 'URL', 'nexora-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'input_type'  => 'url',
				'placeholder' => 'https://',
				'label_block' => true,
				'condition'   => [
					'video_source' => [ 'youtube', 'vimeo' ],
				],
			]
		);

		$widget->add_control(
			'video_poster',
			[
				'label'   => esc_html__( 'Poster', 'nexora-elementor' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => '',
				],
			]
		);

		$widget->add_control(
			'video_play_label',
			[
				'label'   => esc_html__( 'Play label', 'nexora-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'Play video',
			]
		);

		$widget->end_controls_section();

		if ( ! function_exists( 'nexora_ph_register_mapped_controls' ) ) {
			require_once NEXORA_ELE_PATH . 'includes/ph-content.php';
		}
		nexora_ph_register_mapped_controls( $widget, 'video' );
	}
}
