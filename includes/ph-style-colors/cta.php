<?php
/**
 * Prospects Hive final CTA style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_cta' ) ) {
	/**
	 * Return the shared PH entries the final CTA renders.
	 *
	 * The widget uses these shared rules: base text and links, lead copy on the
	 * dark section, the primary button, accent orb, gradient text, and the dark
	 * "mid" section wash. It has no CTA-only solids or gradients.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_cta(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'lead',
				'on-accent',
				'on-dark',
				'lead-on-dark',
				'accent',
				'link',
				'link-blue',
				'btn-mid',
				'grad-warm',
				'grad-rose',
				'grad-sky',
			],
			[ 'btn_primary', 'grad_text', 'mid_orange', 'mid_blue', 'mid_base' ]
		);

		return [
			'include_shared' => false,
			'solids'         => $shared['solids'],
			'gradients'      => $shared['gradients'],
		];
	}
}
