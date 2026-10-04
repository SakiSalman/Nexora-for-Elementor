<?php
/**
 * Prospects Hive partners style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_partners' ) ) {
	/**
	 * Return partners-only colors plus the shared PH entries the partners render.
	 *
	 * The widget uses these shared rules: base text and links, the tag, muted
	 * copy, and the card with its border and hover border.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_partners(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'accent',
				'link',
				'link-blue',
				'tag-border',
				'card-border',
				'card-hover-border',
			],
			[ 'tag', 'card' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'band-mid',
						'label'   => 'Band middle tint (45%)',
						'default' => '#FFFBF8',
						'group'   => 'fill',
					],
					[
						'token'   => 'band-border-top',
						'label'   => 'Band top border',
						'default' => '#F0E8DF',
						'group'   => 'surface',
					],
					[
						'token'   => 'band-border-bottom',
						'label'   => 'Band bottom border',
						'default' => '#E6EEF5',
						'group'   => 'surface',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					[
						'name'           => 'band',
						'label'          => 'Partners band background',
						'group'          => 'fill',
						'selector'       => '{{WRAPPER}} .nexora-ph .partners-band',
						'default'        => [
							'background'     => 'gradient',
							'color'          => '#FFF6F0',
							'color_b'        => '#F3F7FC',
							'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'  => 'linear',
							'gradient_angle' => [ 'unit' => 'deg', 'size' => 120 ],
						],
						'signature'      => 'linear-gradient(var(--ph-partners-band-angle,120deg),var(--ph-partners-band,#FFF6F0) var(--ph-partners-band-start-stop,0%),var(--ph-partners-band-mid,#FFFBF8) 45%,var(--ph-partners-band-end,#F3F7FC) var(--ph-partners-band-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-partners-band' ) ),
						'notes'          => 'The Band middle tint solid remains the fixed middle stop (45%) so the two-stop picker controls the outer colors.',
					],
				]
			),
		];
	}
}
