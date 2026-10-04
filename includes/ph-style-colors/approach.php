<?php
/**
 * Prospects Hive approach style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_approach' ) ) {
	/**
	 * Return approach-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the blue chip
	 * surface, the tag, the glass panel, the card, the check badge, the dark
	 * "mid" wash, the ivory section wash, and gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_approach(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'lead',
				'on-accent',
				'on-dark',
				'tag-on-dark',
				'accent',
				'link',
				'link-blue',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'card-border',
				'card-hover-border',
				'tag-border',
				'chip-blue',
			],
			[ 'tag', 'card', 'check', 'grad_text', 'mid_orange', 'mid_blue', 'mid_base', 'ivory_orange', 'ivory_blue', 'ivory_base' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Card description / captions',
				'lead' => 'Lead / supporting text',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'on-dark' => 'Dark panel title',
				'tag-on-dark' => 'Tag text on dark panel',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'card-border' => 'Card border',
				'card-hover-border' => 'Card border (hover)',
				'tag-border' => 'Eyebrow tag border',
				'chip-blue' => 'Blue chip surface',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'card' => 'Card background',
				'grad_text' => 'Heading highlight gradient',
				'check' => 'Checkmark badge',
				'mid_orange' => 'Dark panel glow — orange',
				'mid_blue' => 'Dark panel glow — blue',
				'mid_base' => 'Dark panel background',
				'ivory_orange' => 'Ivory section glow — orange',
				'ivory_blue' => 'Ivory section glow — blue',
				'ivory_base' => 'Ivory section background',
			],
			]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'list-on-dark',
						'label'   => 'List text on dark panel',
						'default' => '#DCE4EA',
						'group'   => 'text',
					],
					[
						'token'   => 'cross-bg',
						'label'   => 'Cross badge background',
						'default' => '#EEF1F4',
						'group'   => 'surface',
					],
					[
						'token'   => 'cross-text',
						'label'   => 'Cross badge text',
						'default' => '#6B7680',
						'group'   => 'text',
					],
				]
			),
			'gradients'      => $shared['gradients'],
		];
	}
}
