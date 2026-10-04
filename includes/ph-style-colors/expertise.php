<?php
/**
 * Prospects Hive expertise style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_expertise' ) ) {
	/**
	 * Return expertise-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag, lead and
	 * muted copy, the ivory section wash, the hover-swap dark cards with their
	 * icons and chips, check badges, and gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_expertise(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'lead',
				'on-accent',
				'on-dark',
				'lead-on-dark',
				'chip-on-dark',
				'more-on-dark',
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
				'btn-mid',
			],
			[ 'tag', 'card', 'icon_o', 'icon_b', 'grad_text', 'ivory_orange', 'ivory_blue', 'ivory_base', 'check', 'hv_orange', 'hv_blue', 'hv_dark', 'hv_icon' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'list-border',
						'label'   => 'Feature list border',
						'default' => '#F6E1D5',
						'group'   => 'surface',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_linear_gradient( 'list', 'Feature list', 'fill', '{{WRAPPER}} .nexora-ph .exp-list', 'ph-expertise-list', '#FFF6F0', '#FFFFFF', 160 ),
				]
			),
		];
	}
}
