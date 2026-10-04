<?php
/**
 * Prospects Hive cases style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_cases' ) ) {
	/**
	 * Return cases-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag and blue
	 * tag, the card, the chips, the case button and its hover fill, the dark
	 * "mid" panel, and gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_cases(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'on-accent',
				'on-dark',
				'lead-on-dark',
				'muted-on-dark',
				'tag-on-dark',
				'accent',
				'link',
				'link-blue',
				'blue-accent',
				'btn-mid',
				'btn-case-surface',
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
			],
			[ 'tag', 'tag_blue', 'card', 'grad_text', 'btn_case', 'mid_orange', 'mid_blue', 'mid_base' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'arrow-border',
						'label'   => 'Arrow button border',
						'default' => '#E6DDD3',
						'group'   => 'button',
					],
					[
						'token'   => 'dot',
						'label'   => 'Slide dot',
						'default' => '#E2D8CD',
						'group'   => 'surface',
					],
					[
						'token'   => 'row-border',
						'label'   => 'Detail row divider',
						'default' => '#F0E8DF',
						'group'   => 'surface',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_linear_gradient( 'dot_on', 'Active slide dot', 'button', '{{WRAPPER}} .nexora-ph .dot.on', 'ph-cases-dot-on', '#F15E22', '#FF9D72', 90 ),
				]
			),
		];
	}
}
