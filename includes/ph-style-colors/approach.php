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
			[ 'tag', 'card', 'check', 'grad_text', 'mid_orange', 'mid_blue', 'mid_base', 'ivory_orange', 'ivory_blue', 'ivory_base' ]
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
