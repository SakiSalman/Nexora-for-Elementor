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
			[ 'btn_primary', 'grad_text', 'mid_orange', 'mid_blue', 'mid_base' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'lead' => 'Supporting sentence',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'on-dark' => 'Heading on dark band',
				'lead-on-dark' => 'Supporting text on dark band',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'btn-mid' => 'Primary button middle stop',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
			],
			'gradients' => [
				'btn_primary' => 'Primary button',
				'grad_text' => 'Heading highlight gradient',
				'mid_orange' => 'CTA band glow — orange',
				'mid_blue' => 'CTA band glow — blue',
				'mid_base' => 'CTA band background',
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
