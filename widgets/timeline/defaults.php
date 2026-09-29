<?php
/**
 * Default content helpers for ELE Timeline.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_timeline_image_url' ) ) {
	/**
	 * Plugin-relative URL for a bundled timeline image.
	 *
	 * @param string $filename Image filename.
	 */
	function nexora_timeline_image_url( string $filename ): string {
		if ( ! defined( 'NEXORA_ELE_FILE' ) ) {
			return '';
		}

		return plugins_url( 'assets/images/timeline/' . ltrim( $filename, '/' ), NEXORA_ELE_FILE );
	}
}

if ( ! function_exists( 'nexora_timeline_media_default' ) ) {
	/**
	 * Elementor MEDIA control default from bundled filename.
	 *
	 * @param string $filename Image filename.
	 * @return array{url: string, id: string}
	 */
	function nexora_timeline_media_default( string $filename ): array {
		return [
			'url' => nexora_timeline_image_url( $filename ),
			'id'  => '',
		];
	}
}

if ( ! function_exists( 'nexora_timeline_default_items' ) ) {
	/**
	 * Default timeline repeater items (matches original timeline.ts).
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_timeline_default_items(): array {
		return [
			[
				'item_number'      => '01',
				'item_title'       => 'Understand Your Business & Buyers',
				'item_description' => 'We uncover what your audience is searching for before creating a single piece of content.',
				'item_image'       => nexora_timeline_media_default( 'step1_buyers_1787294348844.jpg' ),
				'item_link'        => [ 'url' => '', 'is_external' => '', 'nofollow' => '' ],
				'item_class'       => '',
			],
			[
				'item_number'      => '02',
				'item_title'       => 'Build a Strategy with Purpose',
				'item_description' => 'Every topic, keyword, and content asset is planned around your business goals.',
				'item_image'       => nexora_timeline_media_default( 'step2_strategy_1787294367537.jpg' ),
				'item_link'        => [ 'url' => '', 'is_external' => '', 'nofollow' => '' ],
				'item_class'       => '',
			],
			[
				'item_number'      => '03',
				'item_title'       => 'Create Content That Earns Trust',
				'item_description' => 'We develop high-quality content that educates buyers and positions your brand as an authority.',
				'item_image'       => nexora_timeline_media_default( 'step3_trust_1787294380277.jpg' ),
				'item_link'        => [ 'url' => '', 'is_external' => '', 'nofollow' => '' ],
				'item_class'       => '',
			],
			[
				'item_number'      => '04',
				'item_title'       => 'Optimize for Search & AI Visibility',
				'item_description' => 'Your content is refined to perform across search engines and AI-powered search experiences.',
				'item_image'       => nexora_timeline_media_default( 'step4_search_ai_1787294405055.jpg' ),
				'item_link'        => [ 'url' => '', 'is_external' => '', 'nofollow' => '' ],
				'item_class'       => '',
			],
			[
				'item_number'      => '05',
				'item_title'       => 'Launch Multi-Channel Campaigns',
				'item_description' => 'Email, LinkedIn, and follow-up campaigns work together to engage prospects across multiple touchpoints.',
				'item_image'       => nexora_timeline_media_default( 'step5_multichannel_1787294419181.jpg' ),
				'item_link'        => [ 'url' => '', 'is_external' => '', 'nofollow' => '' ],
				'item_class'       => '',
			],
			[
				'item_number'      => '06',
				'item_title'       => 'Analyze Performance & Improve',
				'item_description' => 'We review responses, improve campaign performance, and scale the strategies driving better results.',
				'item_image'       => nexora_timeline_media_default( 'step6_analytics_1787294433563.jpg' ),
				'item_link'        => [ 'url' => '', 'is_external' => '', 'nofollow' => '' ],
				'item_class'       => '',
			],
		];
	}
}

if ( ! function_exists( 'nexora_timeline_slider_css' ) ) {
	/**
	 * Build a CSS size value from an Elementor slider setting.
	 *
	 * @param mixed  $value    Slider setting.
	 * @param string $fallback Fallback CSS value when empty.
	 */
	function nexora_timeline_slider_css( $value, string $fallback = '' ): string {
		if ( ! is_array( $value ) || ! isset( $value['size'] ) || '' === $value['size'] || null === $value['size'] ) {
			return $fallback;
		}

		$unit = isset( $value['unit'] ) && is_string( $value['unit'] ) && '' !== $value['unit']
			? $value['unit']
			: 'px';

		return $value['size'] . $unit;
	}
}

if ( ! function_exists( 'nexora_timeline_safe_color' ) ) {
	/**
	 * Sanitize a color for inline CSS.
	 *
	 * @param mixed $color Raw color.
	 */
	function nexora_timeline_safe_color( $color ): string {
		if ( ! is_string( $color ) || '' === trim( $color ) ) {
			return '';
		}

		$color = trim( $color );

		if ( function_exists( 'sanitize_hex_color' ) ) {
			$hex = sanitize_hex_color( $color );
			if ( $hex ) {
				return $hex;
			}
		}

		if ( preg_match( '/^(#([0-9a-f]{3}|[0-9a-f]{6}|[0-9a-f]{8})|rgba?\([^;{}<>]*\)|hsla?\([^;{}<>]*\)|[a-z]+)$/i', $color ) ) {
			return $color;
		}

		return '';
	}
}

if ( ! function_exists( 'nexora_timeline_build_root_styles' ) ) {
	/**
	 * Inline CSS custom properties so Style controls always apply.
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 */
	function nexora_timeline_build_root_styles( array $settings ): string {
		$map = [];

		$bg = nexora_timeline_safe_color( $settings['section_background_color'] ?? '' );
		if ( '' !== $bg ) {
			$map['--nexora-tl-bg']     = $bg;
			$map['background-color'] = $bg;
		}

		$max = nexora_timeline_slider_css( $settings['section_max_width'] ?? null );
		if ( '' !== $max ) {
			$map['--nexora-tl-max-width'] = $max;
		}

		$gap = nexora_timeline_slider_css( $settings['column_gap'] ?? null );
		if ( '' !== $gap ) {
			$map['--nexora-tl-gap']    = $gap;
			$map['--nexora-tl-gap-lg'] = $gap;
		}

		$step_gap = nexora_timeline_slider_css( $settings['steps_spacing'] ?? null );
		if ( '' !== $step_gap ) {
			$map['--nexora-tl-step-gap']    = $step_gap;
			$map['--nexora-tl-step-gap-sm'] = $step_gap;
		}

		$visual_offset = nexora_timeline_slider_css( $settings['sticky_top'] ?? null );
		if ( '' !== $visual_offset ) {
			$map['--nexora-tl-visual-offset'] = $visual_offset;
		}

		$image_max = nexora_timeline_slider_css( $settings['image_panel_max_width'] ?? null );
		if ( '' !== $image_max ) {
			$map['--nexora-tl-image-max'] = $image_max;
		}

		$image_bg = nexora_timeline_safe_color( $settings['image_panel_bg'] ?? '' );
		if ( '' !== $image_bg ) {
			$map['--nexora-tl-image-bg'] = $image_bg;
		}

		$image_radius = nexora_timeline_slider_css( $settings['image_panel_radius'] ?? null );
		if ( '' !== $image_radius ) {
			$map['--nexora-tl-image-radius'] = $image_radius;
		}

		$fit = isset( $settings['image_object_fit'] ) ? (string) $settings['image_object_fit'] : '';
		if ( in_array( $fit, [ 'cover', 'contain', 'fill' ], true ) ) {
			$map['--nexora-tl-image-fit'] = $fit;
		}

		if ( is_array( $settings['image_opacity'] ?? null ) && isset( $settings['image_opacity']['size'] ) && '' !== $settings['image_opacity']['size'] && is_numeric( $settings['image_opacity']['size'] ) ) {
			$map['--nexora-tl-image-opacity'] = (string) $settings['image_opacity']['size'];
		}

		$overlay = nexora_timeline_safe_color( $settings['image_overlay_color'] ?? '' );
		if ( '' !== $overlay ) {
			$map['--nexora-tl-overlay-color'] = $overlay;
		}

		if ( is_array( $settings['image_overlay_opacity'] ?? null ) && isset( $settings['image_overlay_opacity']['size'] ) && '' !== $settings['image_overlay_opacity']['size'] ) {
			$map['--nexora-tl-overlay-opacity'] = (string) $settings['image_overlay_opacity']['size'];
		}

		$large_fill = nexora_timeline_safe_color( $settings['large_number_fill'] ?? '' );
		if ( '' !== $large_fill ) {
			$map['--nexora-tl-large-fill'] = $large_fill;
		}

		$large_stroke = nexora_timeline_safe_color( $settings['large_number_stroke'] ?? '' );
		if ( '' !== $large_stroke ) {
			$map['--nexora-tl-large-stroke'] = $large_stroke;
		}

		$stroke_w = nexora_timeline_slider_css( $settings['large_number_stroke_width'] ?? null );
		if ( '' !== $stroke_w ) {
			$map['--nexora-tl-large-stroke-width'] = $stroke_w;
		}

		if ( is_array( $settings['large_number_opacity'] ?? null ) && isset( $settings['large_number_opacity']['size'] ) && '' !== $settings['large_number_opacity']['size'] ) {
			$map['--nexora-tl-large-opacity'] = (string) $settings['large_number_opacity']['size'];
		}

		$track = nexora_timeline_safe_color( $settings['track_color'] ?? '' );
		if ( '' !== $track ) {
			$map['--nexora-tl-track'] = $track;
		}

		$progress = nexora_timeline_safe_color( $settings['progress_color'] ?? '' );
		if ( '' !== $progress ) {
			$map['--nexora-tl-progress'] = $progress;
		}

		$line_w = nexora_timeline_slider_css( $settings['line_width'] ?? null );
		if ( '' !== $line_w ) {
			$map['--nexora-tl-line-width'] = $line_w;
		}

		$line_left = nexora_timeline_slider_css( $settings['line_left'] ?? null );
		if ( '' !== $line_left ) {
			$map['--nexora-tl-line-left'] = $line_left;
		}

		$marker_size = nexora_timeline_slider_css( $settings['marker_size'] ?? null );
		if ( '' !== $marker_size ) {
			$map['--nexora-tl-marker-size'] = $marker_size;
		}

		$marker_bg = nexora_timeline_safe_color( $settings['marker_bg'] ?? '' );
		if ( '' !== $marker_bg ) {
			$map['--nexora-tl-marker-bg'] = $marker_bg;
		}

		$marker_bg_active = nexora_timeline_safe_color( $settings['marker_bg_active'] ?? '' );
		if ( '' !== $marker_bg_active ) {
			$map['--nexora-tl-marker-bg-active'] = $marker_bg_active;
		}

		$marker_border = nexora_timeline_safe_color( $settings['marker_border_color'] ?? '' );
		if ( '' !== $marker_border ) {
			$map['--nexora-tl-marker-border'] = $marker_border;
		}

		$marker_bw = nexora_timeline_slider_css( $settings['marker_border_width'] ?? null );
		if ( '' !== $marker_bw ) {
			$map['--nexora-tl-marker-border-width'] = $marker_bw;
		}

		$marker_glow = nexora_timeline_safe_color( $settings['marker_glow'] ?? '' );
		if ( '' !== $marker_glow ) {
			$map['--nexora-tl-marker-glow'] = $marker_glow;
		}

		$num = nexora_timeline_safe_color( $settings['item_number_color'] ?? '' );
		if ( '' !== $num ) {
			$map['--nexora-tl-num-color'] = $num;
		}

		$num_active = nexora_timeline_safe_color( $settings['item_number_color_active'] ?? '' );
		if ( '' !== $num_active ) {
			$map['--nexora-tl-num-color-active'] = $num_active;
		}

		$title = nexora_timeline_safe_color( $settings['item_title_color'] ?? '' );
		if ( '' !== $title ) {
			$map['--nexora-tl-title-color'] = $title;
		}

		$title_active = nexora_timeline_safe_color( $settings['item_title_color_active'] ?? '' );
		if ( '' !== $title_active ) {
			$map['--nexora-tl-title-color-active'] = $title_active;
		}

		$desc = nexora_timeline_safe_color( $settings['item_desc_color'] ?? '' );
		if ( '' !== $desc ) {
			$map['--nexora-tl-desc-color'] = $desc;
		}

		$desc_active = nexora_timeline_safe_color( $settings['item_desc_color_active'] ?? '' );
		if ( '' !== $desc_active ) {
			$map['--nexora-tl-desc-color-active'] = $desc_active;
		}

		$desc_max = nexora_timeline_slider_css( $settings['item_desc_max_width'] ?? null );
		if ( '' !== $desc_max ) {
			$map['--nexora-tl-desc-max'] = $desc_max;
		}

		$progress_dur = nexora_timeline_slider_css( $settings['progress_duration'] ?? null );
		if ( '' !== $progress_dur ) {
			$map['--nexora-tl-progress-duration'] = $progress_dur;
		}

		$fade_dur = nexora_timeline_slider_css( $settings['image_transition_duration'] ?? null );
		if ( '' !== $fade_dur ) {
			$map['--nexora-tl-fade-duration'] = $fade_dur;
		}

		if ( empty( $map ) ) {
			return '';
		}

		$parts = [];
		foreach ( $map as $prop => $val ) {
			$parts[] = $prop . ':' . $val;
		}

		return implode( ';', $parts );
	}
}
