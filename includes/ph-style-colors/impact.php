<?php
/**
 * Prospects Hive impact style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_impact' ) ) {
	/**
	 * Return impact-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the blue tag, the
	 * glass tab rail and label chip, muted copy, the check badge, and gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_impact(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'on-accent',
				'on-dark',
				'accent',
				'link',
				'link-blue',
				'blue-accent',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'tag-border',
				'tag-blue-border',
			],
			[ 'tag', 'tag_blue', 'check', 'grad_text' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Description / captions',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'on-dark' => 'Stat / title on dark',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'blue-accent' => 'Blue accent / tag dot',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'tag-border' => 'Eyebrow tag border',
				'tag-blue-border' => 'Blue eyebrow tag border',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'tag_blue' => 'Blue eyebrow tag',
				'grad_text' => 'Heading highlight gradient',
				'check' => 'Checkmark badge',
			],
			]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'tab-border',
						'label'   => 'Tab border',
						'default' => '#E6DDD3',
						'group'   => 'surface',
					],
					[
						'token'   => 'tab-bg',
						'label'   => 'Inactive tab background',
						'default' => '#FFFFFF',
						'group'   => 'surface',
					],
					[
						'token'   => 'tab-text',
						'label'   => 'Inactive tab text',
						'default' => '#0B1620',
						'group'   => 'surface',
					],
					[
						'token'   => 'tab-on-mid',
						'label'   => 'Active tab — middle stop',
						'default' => '#F15E22',
						'group'   => 'button',
					],
					[
						'token'   => 'tab-on-text',
						'label'   => 'Active tab text',
						'default' => '#FFFFFF',
						'group'   => 'button',
					],
					[
						'token'   => 'frame-mid',
						'label'   => 'Panel frame middle stop (45%)',
						'default' => '#FF9D72',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_solid_mid_gradient( 'tab_on', 'Active tab background', 'button', '{{WRAPPER}} .nexora-ph .tab.on', 'ph-impact-tab-on', '#FF8350', '#DC5016', 180, '#F15E22', 60 ),
					[
						'name'           => 'frame',
						'label'          => 'Panel frame border',
						'group'          => 'fill',
						'selector'       => '{{WRAPPER}} .nexora-ph .imp-frame',
						'default'        => [
							'background'     => 'gradient',
							'color'          => '#F15E22',
							'color_b'        => '#5FB0E8',
							'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'  => 'linear',
							'gradient_angle' => [ 'unit' => 'deg', 'size' => 145 ],
						],
						'signature'      => 'linear-gradient(var(--ph-impact-frame-angle,145deg),var(--ph-impact-frame,#F15E22) var(--ph-impact-frame-start-stop,0%),var(--ph-impact-frame-mid,#FF9D72) 45%,var(--ph-impact-frame-end,#5FB0E8) var(--ph-impact-frame-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-impact-frame' ) ),
						'notes'          => 'The Panel frame middle stop solid stays the fixed middle stop (45%) so the two-stop picker controls the outer colors.',
					],
					nexora_ph_style_colors_linear_gradient( 'panel', 'Panel background', 'fill', '{{WRAPPER}} .nexora-ph .imp-panel', 'ph-impact-panel', '#FFF4ED', '#FFFFFF', 165, 0, 55 ),
				]
			),
		];
	}
}
