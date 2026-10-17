<?php
/**
 * Cold Email ce-outlook style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_outlook' ) ) {
	/**
	 * Curated color controls for the ce-outlook widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_outlook(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink' ],
			[ 'grad_text' ],
			[
				'solids'    => [
					'ink' => 'Heading text',
				],
				'gradients' => [
					'grad_text' => 'Highlighted words (gradient)',
				],
			]
		);

		return [
			'solids'         => $subset['solids'],
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					// Resting bars: the per-position fills of phases 1-3 (nth-child overrides).
					nexora_ph_style_colors_linear_gradient(
						'bar',
						'Bar color',
						'accent',
						'{{WRAPPER}} .nexora-ph-ce-outlook .ce-g-bar',
						'ph-ce-outlook-bar',
						'#7CC2F0',
						'#2374AC',
						180
					),
					// Base .ce-g-bar fill, the accent bar shown by the highlighted (.on) phase.
					nexora_ph_style_colors_solid_mid_gradient(
						'bar_on',
						'Highlighted bar',
						'accent',
						'{{WRAPPER}} .nexora-ph-ce-outlook .ce-g-bar',
						'ph-ce-outlook-bar-on',
						'#FF8A4C',
						'#DC5016',
						180,
						'#F15E22',
						60
					),
					// Growth chart glass panel.
					nexora_ph_style_colors_linear_gradient(
						'panel',
						'Panel background',
						'surface',
						'{{WRAPPER}} .nexora-ph-ce-outlook .glass',
						'ph-ce-outlook-panel',
						'rgba(255,255,255,.96)',
						'rgba(255,255,255,.86)',
						180
					),
				]
			),
			'include_shared' => false,
		];
	}
}
