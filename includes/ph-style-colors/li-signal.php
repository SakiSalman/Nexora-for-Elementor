<?php
/**
 * LinkedIn li-signal style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_li_signal' ) ) {
	/**
	 * Curated color controls for the li-signal widget.
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_li_signal(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'muted' ],
			[ 'grad_text' ],
			[
				'solids'    => [
					'ink'   => 'Heading text',
					'muted' => 'Card subtitle',
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
					[
						'token'   => 'wash',
						'label'   => 'Section wash',
						'default' => '#F7FAFD',
						'group'   => 'fill',
						'shared'  => false,
					],
					[
						'token'   => 'card',
						'label'   => 'Side card surface',
						'default' => '#FFFFFF',
						'group'   => 'surface',
						'shared'  => false,
					],
					[
						'token'   => 'card-border',
						'label'   => 'Side card border',
						'default' => '#EFE7DE',
						'group'   => 'surface',
						'shared'  => false,
					],
					[
						'token'   => 'accent',
						'label'   => 'Node / check accent',
						'default' => '#FF8A4C',
						'group'   => 'accent',
						'shared'  => false,
					],
					[
						'token'   => 'accent-end',
						'label'   => 'Node / check accent end',
						'default' => '#F15E22',
						'group'   => 'accent',
						'shared'  => false,
					],
				]
			),
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					nexora_ph_style_colors_linear_gradient(
						'core',
						'Radar core fill',
						'fill',
						'{{WRAPPER}} .nexora-ph-li-signal .sc-core',
						'ph-li-signal-core',
						'#16283C',
						'#0B1620',
						160,
						0,
						70
					),
					nexora_ph_style_colors_linear_gradient(
						'outcome',
						'Outcome card surface',
						'surface',
						'{{WRAPPER}} .nexora-ph-li-signal .sc-out',
						'ph-li-signal-outcome',
						'#FFF3EC',
						'#FFFFFF',
						145,
						0,
						70
					),
				]
			),
			'include_shared' => false,
		];
	}
}
