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
			[ 'tag', 'card', 'grad_text', 'hv_orange', 'hv_blue', 'hv_dark' ],
			[
				'solids'    => [
					'ink'               => 'Heading / body text',
					'muted'             => 'Card description / captions',
					'lead'              => 'Lead / supporting text',
					'surface'           => 'Section background',
					'on-dark'           => 'Card title (hover)',
					'lead-on-dark'      => 'Card description (hover)',
					'accent'            => 'Accent / highlight',
					'link'              => 'Learn more link / icon stroke',
					'link-blue'         => 'Blue link / chip text',
					'grad-warm'         => 'Heading gradient — warm stop',
					'grad-rose'         => 'Heading gradient — rose stop',
					'grad-sky'          => 'Heading gradient — sky stop',
					'card-border'       => 'Card border',
					'card-hover-border' => 'Card border (hover)',
					'tag-border'        => 'Eyebrow tag border',
				],
				'gradients' => [
					'tag'       => 'Eyebrow tag',
					'card'      => 'Card background',
					'grad_text' => 'Heading highlight gradient',
					'hv_orange' => 'Card panel glow — orange (hover)',
					'hv_blue'   => 'Card panel glow — blue (hover)',
					'hv_dark'   => 'Card panel (hover)',
				],
			]
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
					nexora_ph_style_colors_linear_gradient( 'icon_top', 'Floating icon tile', 'fill', '{{WRAPPER}} .nexora-ph', 'ph-why-icon-top', '#FFFFFF', '#FFF1E9', 145 ),
				]
			),
		];
	}
}
