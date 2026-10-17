<?php
/**
 * Cold Email ce-hero style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_hero' ) ) {
	/**
	 * Curated color controls for the ce-hero widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_hero(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'lead' ],
			[ 'grad_text', 'btn_primary' ],
			[
				'solids'    => [
					'ink'  => 'Heading text',
					'lead' => 'Description text',
				],
				'gradients' => [
					'grad_text'   => 'Highlighted words (gradient)',
					'btn_primary' => 'Primary button',
				],
			]
		);

		return [
			'solids'         => $subset['solids'],
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					// The shared btn_white catalog entry carries no var bridge, so the
					// white button owns a two-stop picker with the design colors.
					nexora_ph_style_colors_linear_gradient(
						'btn-white',
						'Secondary button',
						'button',
						'{{WRAPPER}} .nexora-ph-ce-hero .btn.btn-white',
						'ph-ce-hero-btn-white',
						'#FFFFFF',
						'#F6F2EE',
						180
					),
					// Campaign card meter fill: the picker owns the outer colors,
					// stops and angle; the #FF8A4C 45% middle stop stays the design value.
					nexora_ph_style_colors_stops_gradient(
						[
							'name'     => 'card-accent',
							'label'    => 'Campaign card accent',
							'group'    => 'accent',
							'selector' => '{{WRAPPER}} .nexora-ph-ce-hero .ce-meter .ce-bar span',
							'prefix'   => 'ph-ce-hero-card-accent',
							'type'     => 'linear',
							'angle'    => 90,
							'stops'    => [
								[ '#FFB08C', 0 ],
								[ '#FF8A4C', 45 ],
								[ '#F15E22', 100 ],
							],
						]
					),
					// The hero background glows are inline layers on .hero-sec: the
					// template keeps every size, origin, fade and stop byte for byte and
					// reads only the glow colors, so the stop and position pickers stay
					// fixed in the markup.
					[
						'name'     => 'glow-orange',
						'label'    => 'Hero background glow — orange',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph-ce-hero .hero-sec',
						'default'  => [
							'background'        => 'gradient',
							'color'             => 'rgba(241,94,34,.26)',
							'color_b'           => 'rgba(0,0,0,0)',
							'color_stop'        => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'      => [ 'unit' => '%', 'size' => 60 ],
							'gradient_type'     => 'radial',
							'gradient_position' => '88% 8%',
						],
						'fields_options' => nexora_ph_style_colors_bridge(
							[
								'color'        => '--ph-ce-hero-glow-orange',
								'color_b'      => '--ph-ce-hero-glow-orange-end',
								'color_b_stop' => '--ph-ce-hero-glow-orange-end-stop',
							],
							'radial',
							[ 'hidden' => [ 'color_stop', 'gradient_position' ] ]
						),
					],
					[
						'name'     => 'glow-blue',
						'label'    => 'Hero background glow — blue',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph-ce-hero .hero-sec',
						'default'  => [
							'background'        => 'gradient',
							'color'             => 'rgba(35,116,172,.26)',
							'color_b'           => 'rgba(0,0,0,0)',
							'color_stop'        => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'      => [ 'unit' => '%', 'size' => 62 ],
							'gradient_type'     => 'radial',
							'gradient_position' => '72% 70%',
						],
						'fields_options' => nexora_ph_style_colors_bridge(
							[
								'color'        => '--ph-ce-hero-glow-blue',
								'color_b'      => '--ph-ce-hero-glow-blue-end',
								'color_b_stop' => '--ph-ce-hero-glow-blue-end-stop',
							],
							'radial',
							[ 'hidden' => [ 'color_stop', 'gradient_position' ] ]
						),
					],
				]
			),
			'include_shared' => false,
		];
	}
}
