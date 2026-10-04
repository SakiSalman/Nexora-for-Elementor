<?php
/**
 * Prospects Hive challenge style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_challenge' ) ) {
	/**
	 * Return the shared PH entries the challenge section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag, lead and
	 * muted copy, the card with its hover-swap dark panel, the orange icon tile
	 * and its hover fill, and gradient text. It has no challenge-only colors.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_challenge(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
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
				'card-border',
				'card-hover-border',
				'tag-border',
			],
			[ 'tag', 'card', 'icon_o', 'grad_text', 'hv_orange', 'hv_blue', 'hv_dark', 'hv_icon' ]
		);

		return [
			'include_shared' => false,
			'solids'         => $shared['solids'],
			'gradients'      => $shared['gradients'],
		];
	}
}
