<?php
/**
 * Cold Email ce-solution style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_solution' ) ) {
	/**
	 * Curated color controls for the ce-solution widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_solution(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'lead' ],
			[ 'grad_text', 'btn_dark' ],
			[
				'solids'    => [
					'ink'  => 'Heading text',
					'lead' => 'Description text',
				],
				'gradients' => [
					'grad_text' => 'Highlighted words (gradient)',
					'btn_dark'  => 'Button',
				],
			]
		);

		return [
			'solids'         => array_merge(
				$subset['solids'],
				[
					[
						'token'   => 'card-bg',
						'label'   => 'Card background (applies to all cards)',
						'default' => '#FFFFFF',
						'group'   => 'surface',
						'shared'  => false,
					],
				]
			),
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					// Three-stop number gradient: the fixed #F15E22 middle stop keeps the
					// design value; the picker owns the outer colors, stops and angle.
					nexora_ph_style_colors_stops_gradient(
						[
							'name'     => 'ss-num',
							'label'    => 'Card number / result accent',
							'group'    => 'accent',
							'selector' => '{{WRAPPER}} .nexora-ph-ce-solution .ss-num',
							'prefix'   => 'ph-ce-solution-ss-num',
							'type'     => 'linear',
							'angle'    => 160,
							'stops'    => [
								[ '#FF9D72', 0 ],
								[ '#F15E22', 45 ],
								[ '#2374AC', 100 ],
							],
						]
					),
				]
			),
			'include_shared' => false,
		];
	}
}
