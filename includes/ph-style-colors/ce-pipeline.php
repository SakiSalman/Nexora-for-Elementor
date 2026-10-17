<?php
/**
 * Cold Email ce-pipeline style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_pipeline' ) ) {
	/**
	 * Curated color controls for the ce-pipeline widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_pipeline(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink' ],
			[ 'grad_text', 'play_btn', 'icon_o' ],
			[
				'solids'    => [
					'ink' => 'Heading text',
				],
				'gradients' => [
					'grad_text' => 'Highlighted words (gradient)',
					'play_btn'  => 'Active stage',
					'icon_o'    => 'Completed stage',
				],
			]
		);

		return [
			'solids'         => $subset['solids'],
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					// Progress fill: four-stop design gradient (blue → sky → warm → accent)
					// painted by .pl-line span; the state classes only animate its width.
					nexora_ph_style_colors_stops_gradient(
						[
							'name'     => 'progress',
							'label'    => 'Progress line',
							'group'    => 'accent',
							'selector' => '{{WRAPPER}} .nexora-ph-ce-pipeline .pl-line span',
							'prefix'   => 'ph-ce-pipeline-progress',
							'type'     => 'linear',
							'angle'    => 90,
							'stops'    => [
								[ '#2374AC', 0 ],
								[ '#5FB0E8', 35 ],
								[ '#FF8A4C', 70 ],
								[ '#F15E22', 100 ],
							],
						]
					),
					// Stage panel (.pl-card.glass): the !important translucent white pair
					// that wins over the shared .glass rule.
					nexora_ph_style_colors_linear_gradient(
						'panel',
						'Panel background',
						'surface',
						'{{WRAPPER}} .nexora-ph-ce-pipeline .glass',
						'ph-ce-pipeline-panel',
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
