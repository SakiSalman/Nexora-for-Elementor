<?php
/**
 * Cold Email ce-services style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_services' ) ) {
	/**
	 * Curated color controls for the ce-services widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_services(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'muted', 'on-dark', 'link-blue' ],
			[ 'grad_text', 'card', 'icon_o', 'icon_b' ],
			[
				'solids'    => [
					'ink'       => 'Heading text',
					'muted'     => 'Card description',
					'on-dark'   => 'Card title',
					'link-blue' => 'Chip text',
				],
				'gradients' => [
					'grad_text' => 'Highlighted words (gradient)',
					'card'      => 'Card background',
					'icon_o'    => 'Icon badge tint — orange',
					'icon_b'    => 'Icon badge tint — blue',
				],
			]
		);

		return [
			'solids'         => $subset['solids'],
			'gradients'      => $subset['gradients'],
			'include_shared' => false,
		];
	}
}
