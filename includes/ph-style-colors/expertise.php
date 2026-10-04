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
			[ 'tag', 'card', 'icon_o', 'icon_b', 'grad_text', 'ivory_orange', 'ivory_blue', 'ivory_base', 'check', 'hv_orange', 'hv_blue', 'hv_dark', 'hv_icon' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Card description / captions',
				'lead' => 'Lead / supporting text',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'on-dark' => 'Card title (hover)',
				'lead-on-dark' => 'Card description (hover)',
				'chip-on-dark' => 'Chip text (hover)',
				'more-on-dark' => 'Learn more link (hover)',
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
				'chip-blue' => 'Blue chip surface',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'card' => 'Card background',
				'icon_o' => 'Orange icon tile',
				'icon_b' => 'Blue icon tile',
				'grad_text' => 'Heading highlight gradient',
				'hv_orange' => 'Card panel glow — orange (hover)',
				'hv_blue' => 'Card panel glow — blue (hover)',
				'hv_dark' => 'Card panel (hover)',
				'hv_icon' => 'Card icon tile (hover)',
				'check' => 'Checkmark badge',
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
					nexora_ph_style_colors_linear_gradient( 'list', 'Feature list background', 'fill', '{{WRAPPER}} .nexora-ph .exp-list', 'ph-expertise-list', '#FFF6F0', '#FFFFFF', 160 ),
				]
			),
		];
	}
}
