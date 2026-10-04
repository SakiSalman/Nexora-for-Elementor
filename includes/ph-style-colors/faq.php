<?php
/**
 * Prospects Hive faq style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_faq' ) ) {
	/**
	 * Return faq-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag, lead and
	 * muted copy, and gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_faq(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'lead',
				'on-accent',
				'accent',
				'link',
				'link-blue',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'card-border',
				'tag-border',
			],
			[ 'tag', 'grad_text' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'answer-border',
						'label'   => 'Answer divider',
						'default' => '#F6D9C8',
						'group'   => 'surface',
					],
					[
						'token'   => 'open-border',
						'label'   => 'Open question border',
						'default' => '#F7B596',
						'group'   => 'surface',
					],
					[
						'token'   => 'pm-bg',
						'label'   => 'Toggle icon background',
						'default' => '#EEF1F4',
						'group'   => 'surface',
					],
					[
						'token'   => 'open-mid',
						'label'   => 'Open question header — middle stop',
						'default' => '#FFF8F3',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_solid_mid_gradient( 'open', 'Open question header', 'fill', '{{WRAPPER}} .nexora-ph .faq[open] summary', 'ph-faq-open', '#FFF1E8', '#F4F9FD', 135, '#FFF8F3', 60 ),
					nexora_ph_style_colors_linear_gradient( 'pm_open', 'Open toggle icon', 'fill', '{{WRAPPER}} .nexora-ph .faq[open] .pm', 'ph-faq-pm-open', '#FF8350', '#F15E22', 145 ),
				]
			),
		];
	}
}
