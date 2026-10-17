<?php
/**
 * Cold Email ce-process style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_process' ) ) {
	/**
	 * Curated color controls for the ce-process widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_process(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'surface' ],
			[ 'grad_text' ],
			[
				'solids'    => [
					'ink'     => 'Heading text',
					'surface' => 'Outcome badge',
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
						'token'   => 'card-1',
						'label'   => 'Step card 1 background',
						'default' => '#D9EAF7',
						'group'   => 'surface',
						'shared'  => false,
					],
					[
						'token'   => 'card-2',
						'label'   => 'Step card 2 background',
						'default' => '#FFE3D1',
						'group'   => 'surface',
						'shared'  => false,
					],
					[
						'token'   => 'card-3',
						'label'   => 'Step card 3 background',
						'default' => '#FFF0C9',
						'group'   => 'surface',
						'shared'  => false,
					],
					[
						'token'   => 'card-4',
						'label'   => 'Step card 4 background',
						'default' => '#DDF1E6',
						'group'   => 'surface',
						'shared'  => false,
					],
					[
						'token'   => 'card-5',
						'label'   => 'Step card 5 background',
						'default' => '#FFD6C2',
						'group'   => 'surface',
						'shared'  => false,
					],
				]
			),
			'gradients'      => $subset['gradients'],
			'include_shared' => false,
		];
	}
}
