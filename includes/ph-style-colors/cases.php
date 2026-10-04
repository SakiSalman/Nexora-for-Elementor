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
			[ 'tag', 'tag_blue', 'card', 'grad_text', 'btn_case', 'mid_orange', 'mid_blue', 'mid_base' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Case card description / captions',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'on-dark' => 'Case card title on dark',
				'lead-on-dark' => 'Case card description on dark',
				'muted-on-dark' => 'Case card muted text on dark',
				'tag-on-dark' => 'Case card tag on dark',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'blue-accent' => 'Blue accent / tag dot',
				'btn-mid' => 'Primary button middle stop',
				'btn-case-surface' => 'Case study button surface',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'card-border' => 'Card border',
				'card-hover-border' => 'Card border (hover)',
				'tag-border' => 'Eyebrow tag border',
				'tag-blue-border' => 'Blue eyebrow tag border',
				'chip-blue' => 'Blue chip surface',
				'chip-orange' => 'Orange chip surface',
				'chip-orange-text' => 'Orange chip text',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'tag_blue' => 'Blue eyebrow tag',
				'card' => 'Card background',
				'btn_case' => 'Case study button (hover)',
				'grad_text' => 'Heading highlight gradient',
				'mid_orange' => 'Dark band glow — orange',
				'mid_blue' => 'Dark band glow — blue',
				'mid_base' => 'Dark band background',
			],
			]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'arrow-border',
						'label'   => 'Slider arrow border',
						'default' => '#E6DDD3',
						'group'   => 'button',
					],
					[
						'token'   => 'dot',
						'label'   => 'Slider dot',
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
