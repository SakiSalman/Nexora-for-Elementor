<?php
/**
 * Cold Email ce-signal style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_signal' ) ) {
	/**
	 * Curated color controls for the ce-signal widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_signal(): array {
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
			'solids'         => array_merge(
				$subset['solids'],
				[
					// Signal chips: the orange highlight flash of the cesig animation.
					[
						'token'   => 'chip-hl',
						'label'   => 'Signal chip highlight',
						'default' => '#FFF1E9',
						'group'   => 'tag',
						'shared'  => false,
					],
					// Email <mark> wash behind the [[…]]-matched words (cemark family).
					[
						'token'   => 'mark',
						'label'   => 'Email highlight (`<mark>`)',
						'default' => '#F4F1ED',
						'group'   => 'fill',
						'shared'  => false,
					],
				]
			),
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					// Account card glass panel: the winning .glass rule also styles
					// .ce-stat, which keeps the original fallbacks because the vars
					// are only set on .ce-acct.
					nexora_ph_style_colors_linear_gradient(
						'acct',
						'Account card background',
						'surface',
						'{{WRAPPER}} .nexora-ph-ce-signal .ce-acct',
						'ph-ce-signal-acct',
						'rgba(255,255,255,.96)',
						'rgba(255,255,255,.86)',
						180
					),
					// Stat numbers: the seven-stop shine of .grad-text, scoped to the
					// stat values so the two gradient pickers stay independent.
					nexora_ph_style_colors_stops_gradient(
						[
							'name'     => 'stat',
							'label'    => 'Stat numbers (gradient)',
							'group'    => 'fill',
							'selector' => '{{WRAPPER}} .nexora-ph-ce-signal .ce-stat b',
							'prefix'   => 'ph-ce-signal-stat',
							'type'     => 'linear',
							'angle'    => 90,
							'stops'    => [
								[ '#F15E22', 0 ],
								[ '#FF8A4C', 20 ],
								[ '#E0617A', 36 ],
								[ '#2B8AE0', 52 ],
								[ '#E0617A', 68 ],
								[ '#FF8A4C', 84 ],
								[ '#F15E22', 100 ],
							],
						]
					),
				]
			),
			'include_shared' => false,
		];
	}
}
