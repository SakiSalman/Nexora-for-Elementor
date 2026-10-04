<?php
/**
 * Prospects Hive why style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_why' ) ) {
	/**
	 * Return why-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag, lead and
	 * muted copy, the card with its hover-swap dark panel, and gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_why(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'lead',
				'on-dark',
				'lead-on-dark',
				'accent',
				'link',
				'link-blue',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'card-border',
				'card-hover-border',
				'tag-border',
			],
			[ 'tag', 'card', 'grad_text', 'hv_orange', 'hv_blue', 'hv_dark' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'icon-top-border',
						'label'   => 'Floating icon border',
						'default' => '#F7C9B3',
						'group'   => 'surface',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_linear_gradient( 'icon_top', 'Floating icon', 'fill', '{{WRAPPER}} .nexora-ph .icon-top', 'ph-why-icon-top', '#FFFFFF', '#FFF1E9', 145 ),
				]
			),
		];
	}
}
