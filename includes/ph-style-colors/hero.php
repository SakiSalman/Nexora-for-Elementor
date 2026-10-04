<?php
/**
 * Prospects Hive hero style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_hero_wash_gradient' ) ) {
	/**
	 * Build one radial hero wash layer owned by CSS variables.
	 *
	 * @param string $name       Inventory name suffix.
	 * @param string $label      Control label.
	 * @param string $origin     Original CSS radial origin.
	 * @param string $size       Original CSS radial size.
	 * @param string $start      Start color.
	 * @param string $end        End color.
	 * @param int    $end_stop   End stop percentage.
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_hero_wash_gradient(
		string $name,
		string $label,
		string $origin,
		string $size,
		string $start,
		string $end,
		int $end_stop
	): array {
		$prefix = 'ph-hero-wash-' . str_replace( '_', '-', $name );

		return [
			'name'           => 'wash_' . $name,
			'label'          => $label,
			'group'          => 'fill',
			'selector'       => '{{WRAPPER}} .nexora-ph .hero-sec',
			'default'        => [
				'background'        => 'gradient',
				'color'             => $start,
				'color_b'           => $end,
				'color_stop'        => [ 'unit' => '%', 'size' => 0 ],
				'color_b_stop'      => [ 'unit' => '%', 'size' => $end_stop ],
				'gradient_type'     => 'radial',
				'gradient_position' => $origin,
			],
			'signature'      => sprintf(
				'radial-gradient(%1$s at var(--%2$s-position,%3$s),var(--%2$s,%4$s) var(--%2$s-start-stop,0%%),var(--%2$s-end,%5$s) var(--%2$s-end-stop,%6$d%%))',
				$size,
				$prefix,
				$origin,
				$start,
				$end,
				$end_stop
			),
			'fields_options' => nexora_ph_style_colors_bridge(
				nexora_ph_style_colors_vars( $prefix, 'radial' ),
				'radial',
				[ 'position_options' => nexora_ph_style_colors_position_options( $origin ) ]
			),
		];
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_inventory_hero' ) ) {
	/**
	 * Return hero-only colors plus the shared PH entries the hero renders.
	 *
	 * The hero uses these shared rules: body/link/lead/muted text, the primary
	 * button, tag, accent and blue icons, gradient text, and the glass surface.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_hero(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'muted',
				'lead',
				'on-accent',
				'accent',
				'link',
				'link-blue',
				'blue-accent',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'surface',
				'btn-mid',
				'tag-border',
			],
			[ 'btn_primary', 'tag', 'icon', 'icon_b', 'grad_text', 'grid_horizontal', 'grid_vertical' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Supporting text / captions',
				'lead' => 'Lead paragraph',
				'surface' => 'Hero surface / wash middle stop',
				'on-accent' => 'Text on buttons / accent fills',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'blue-accent' => 'Blue accent / orbit accents',
				'btn-mid' => 'Primary button middle stop',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'tag-border' => 'Eyebrow tag border',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'btn_primary' => 'Primary button',
				'icon' => 'Accent icon tile',
				'icon_b' => 'Blue icon tile',
				'grad_text' => 'Heading highlight gradient',
				'grid_horizontal' => 'Background grid — horizontal',
				'grid_vertical' => 'Background grid — vertical',
			],
			]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'signal-blue',
						'label'   => 'Signal accent',
						'default' => '#5FB0E8',
						'group'   => 'accent',
					],
					[
						'token'   => 'quiet-text',
						'label'   => 'Secondary caption text',
						'default' => '#8FA0AF',
						'group'   => 'text',
					],
					[
						'token'   => 'rating-start',
						'label'   => 'Rating badge gradient — start',
						'default' => '#FF9D6B',
						'group'   => 'accent',
					],
					[
						'token'   => 'rating-end',
						'label'   => 'Rating badge gradient — end',
						'default' => '#E4501A',
						'group'   => 'accent',
					],
					[
						'token'   => 'rating-border',
						'label'   => 'Rating badge border',
						'default' => '#EDE7E1',
						'group'   => 'surface',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_hero_wash_gradient( 'orange_top', 'Hero glow — orange top', '88% 8%', '760px 520px', 'rgba(241,94,34,.26)', 'rgba(241,94,34,0)', 60 ),
					nexora_ph_style_colors_hero_wash_gradient( 'blue_bottom', 'Hero glow — blue bottom', '72% 70%', '820px 560px', 'rgba(35,116,172,.26)', 'rgba(35,116,172,0)', 62 ),
					nexora_ph_style_colors_hero_wash_gradient( 'blue_top', 'Hero glow — blue top', '0% 0%', '700px 500px', 'rgba(35,116,172,.18)', 'rgba(35,116,172,0)', 60 ),
					nexora_ph_style_colors_hero_wash_gradient( 'orange_bottom', 'Hero glow — orange bottom', '8% 95%', '640px 460px', 'rgba(241,94,34,.16)', 'rgba(241,94,34,0)', 60 ),
					[
						'name'           => 'wash_base',
						'label'          => 'Hero background',
						'group'          => 'fill',
						'selector'       => '{{WRAPPER}} .nexora-ph .hero-sec',
						'default'        => [
							'background'     => 'gradient',
							'color'          => '#FFF3EC',
							'color_b'        => '#EAF3FB',
							'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'  => 'linear',
							'gradient_angle' => [ 'unit' => 'deg', 'size' => 135 ],
						],
						'signature'      => 'linear-gradient(var(--ph-hero-wash-base-angle,135deg),var(--ph-hero-wash-base,#FFF3EC) var(--ph-hero-wash-base-start-stop,0%),var(--ph-surface,#FFFFFF) 42%,var(--ph-hero-wash-base-end,#EAF3FB) var(--ph-hero-wash-base-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-hero-wash-base' ) ),
						'notes'          => 'The shared surface token remains the fixed middle stop (42%) so the two-stop picker controls the outer colors.',
					],
					[
						'name'           => 'ghost_text',
						'label'          => 'Ghost word fill',
						'group'          => 'fill',
						'selector'       => '{{WRAPPER}} .nexora-ph .ghost-word',
						'default'        => [
							'background'     => 'gradient',
							'color'          => 'rgba(241,94,34,.16)',
							'color_b'        => 'rgba(35,116,172,.02)',
							'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'  => 'linear',
							'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
						],
						'signature'      => 'linear-gradient(var(--ph-hero-ghost-angle,180deg),var(--ph-hero-ghost,rgba(241,94,34,.16)) var(--ph-hero-ghost-start-stop,0%),var(--ph-hero-ghost-end,rgba(35,116,172,.02)) var(--ph-hero-ghost-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-hero-ghost' ) ),
					],
					[
						'name'           => 'orbit_orange',
						'label'          => 'Orbit node (orange)',
						'group'          => 'fill',
						'selector'       => '{{WRAPPER}} .nexora-ph .hero-orbit-orange',
						'default'        => [
							'background'     => 'gradient',
							'color'          => '#FF8A4C',
							'color_b'        => '#F15E22',
							'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'  => 'linear',
							'gradient_angle' => [ 'unit' => 'deg', 'size' => 145 ],
						],
						'signature'      => 'linear-gradient(var(--ph-hero-orbit-orange-angle,145deg),var(--ph-hero-orbit-orange,#FF8A4C) var(--ph-hero-orbit-orange-start-stop,0%),var(--ph-hero-orbit-orange-end,#F15E22) var(--ph-hero-orbit-orange-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-hero-orbit-orange' ) ),
					],
					[
						'name'           => 'core_surface',
						'label'          => 'Hero core glass',
						'group'          => 'fill',
						'selector'       => '{{WRAPPER}} .nexora-ph .hero-core',
						'default'        => [
							'background'        => 'gradient',
							'color'             => 'rgba(255,255,255,.98)',
							'color_b'           => 'rgba(234,244,251,.9)',
							'color_stop'        => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'      => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'     => 'radial',
							'gradient_position' => '30% 20%',
						],
						'signature'      => 'radial-gradient(circle at var(--ph-hero-core-position,30% 20%),var(--ph-hero-core,rgba(255,255,255,.98)) var(--ph-hero-core-start-stop,0%),rgba(255,255,255,.82) 55%,var(--ph-hero-core-end,rgba(234,244,251,.9)) var(--ph-hero-core-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge(
							nexora_ph_style_colors_vars( 'ph-hero-core', 'radial' ),
							'radial',
							[ 'position_options' => nexora_ph_style_colors_position_options( '30% 20%' ) ]
						),
						'notes'          => 'The white translucent midpoint (rgba(255,255,255,.82) at 55%) is an excluded glass overlay and stays fixed in CSS.',
					],
				]
			),
		];
	}
}
