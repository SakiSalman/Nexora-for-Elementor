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
			[ 'tag', 'card', 'icon_o', 'grad_text', 'hv_orange', 'hv_blue', 'hv_dark', 'hv_icon' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Card description / captions',
				'lead' => 'Lead / supporting text',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'on-dark' => 'Card title (hover)',
				'lead-on-dark' => 'Card description (hover)',
				'accent' => 'Accent / highlight',
				'link' => 'Learn more link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'btn-mid' => 'Primary button middle stop',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'card-border' => 'Card border',
				'card-hover-border' => 'Card border (hover)',
				'tag-border' => 'Eyebrow tag border',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'card' => 'Card background',
				'icon_o' => 'Orange icon tile',
				'grad_text' => 'Heading highlight gradient',
				'hv_orange' => 'Card panel glow — orange (hover)',
				'hv_blue' => 'Card panel glow — blue (hover)',
				'hv_dark' => 'Card panel (hover)',
				'hv_icon' => 'Card icon tile (hover)',
			],
			]
		);

		return [
			'include_shared' => false,
			'solids'         => $shared['solids'],
			'gradients'      => $shared['gradients'],
		];
	}
}
