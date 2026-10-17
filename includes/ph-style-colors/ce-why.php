<?php
/**
 * Cold Email ce-why style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_why' ) ) {
	/**
	 * Curated color controls for the ce-why widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_why(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'on-dark', 'muted' ],
			[ 'grad_text', 'card' ],
			[
				'solids'    => [
					'ink'     => 'Heading text',
					'on-dark' => 'Card title',
					'muted'   => 'Card description',
				],
				'gradients' => [
					'grad_text' => 'Highlighted words (gradient)',
					'card'      => 'Card background',
				],
			]
		);

		return [
			'solids'         => $subset['solids'],
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					nexora_ph_style_colors_linear_gradient(
						'icon-top',
						'Icon accent',
						'fill',
						'{{WRAPPER}} .nexora-ph-ce-why .icon-top',
						'ph-ce-why-icon-top',
						'#FFFFFF',
						'#FFF1E9',
						145
					),
				]
			),
			'include_shared' => false,
		];
	}
}
