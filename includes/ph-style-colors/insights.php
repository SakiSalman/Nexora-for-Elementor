<?php
/**
 * Prospects Hive insights style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_insights' ) ) {
	/**
	 * Return the shared PH entries the insights section renders.
	 *
	 * The widget uses these shared rules: base text and links, the blue tag,
	 * the card with its chips and "more" link, the dark button with its hover
	 * fill, and gradient text. The image placeholder uses the shared ink color
	 * and the "Read article" link uses the shared orange link color.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_insights(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'on-accent',
				'accent',
				'link',
				'link-blue',
				'blue-accent',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'card-border',
				'card-hover-border',
				'tag-border',
				'tag-blue-border',
				'chip-blue',
				'chip-orange',
				'chip-orange-text',
				'btn-mid',
			],
			[ 'tag', 'tag_blue', 'card', 'grad_text', 'btn_dark', 'btn_dark_hover' ]
		);

		return [
			'include_shared' => false,
			'solids'         => $shared['solids'],
			'gradients'      => $shared['gradients'],
		];
	}
}
