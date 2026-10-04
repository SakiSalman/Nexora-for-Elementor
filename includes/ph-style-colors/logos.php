<?php
/**
 * Prospects Hive logos style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_logos' ) ) {
	/**
	 * Return logos-only colors plus the shared PH entries the logo strip renders.
	 *
	 * The widget uses these shared rules: base text and links, lead and muted
	 * copy, the accent star/shadow color, the blue shadow color, and gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_logos(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'lead',
				'accent',
				'link',
				'link-blue',
				'blue-accent',
				'grad-warm',
				'grad-rose',
				'grad-sky',
			],
			[ 'grad_text' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Description / captions',
				'lead' => 'Lead / supporting text',
				'surface' => 'Section background',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'blue-accent' => 'Blue accent / tag dot',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
			],
			'gradients' => [
				'grad_text' => 'Heading highlight gradient',
			],
			]
		);

		$avatar_selector = '{{WRAPPER}} .nexora-ph .logo-av-';

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'pill-mid',
						'label'   => 'Logo pill — middle tint',
						'default' => '#FFF4EC',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					[
						'name'           => 'pill',
						'label'          => 'Logo pill background',
						'group'          => 'fill',
						'selector'       => '{{WRAPPER}} .nexora-ph .logo-pill',
						'default'        => [
							'background'     => 'gradient',
							'color'          => 'rgba(255,255,255,.86)',
							'color_b'        => 'rgba(232,242,251,.78)',
							'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'  => 'linear',
							'gradient_angle' => [ 'unit' => 'deg', 'size' => 120 ],
						],
						'signature'      => 'linear-gradient(var(--ph-logos-pill-angle,120deg),var(--ph-logos-pill,rgba(255,255,255,.86)) var(--ph-logos-pill-start-stop,0%),color-mix(in srgb,var(--ph-logos-pill-mid,#FFF4EC) 74%,transparent) 45%,var(--ph-logos-pill-end,rgba(232,242,251,.78)) var(--ph-logos-pill-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-logos-pill' ) ),
						'notes'          => 'The Logo pill middle tint solid stays the fixed middle stop (45%, 74% opacity) so the two-stop picker controls the outer colors.',
					],
					nexora_ph_style_colors_linear_gradient( 'avatar_1', 'Avatar 1 fill', 'fill', $avatar_selector . '1', 'ph-logos-av1', '#FF9D72', '#F15E22', 135 ),
					nexora_ph_style_colors_linear_gradient( 'avatar_2', 'Avatar 2 fill', 'fill', $avatar_selector . '2', 'ph-logos-av2', '#7CC2F0', '#2374AC', 135 ),
					nexora_ph_style_colors_linear_gradient( 'avatar_3', 'Avatar 3 fill', 'fill', $avatar_selector . '3', 'ph-logos-av3', '#DCE4EA', '#8FA0AF', 135 ),
					nexora_ph_style_colors_linear_gradient( 'avatar_4', 'Avatar 4 fill', 'fill', $avatar_selector . '4', 'ph-logos-av4', '#FFD7C4', '#F5A77E', 135 ),
				]
			),
		];
	}
}
