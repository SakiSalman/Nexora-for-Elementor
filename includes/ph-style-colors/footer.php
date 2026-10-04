<?php
/**
 * Prospects Hive footer style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_footer' ) ) {
	/**
	 * Return footer-only colors plus the shared PH entries the footer renders.
	 *
	 * The footer uses these shared rules: base text and links, white text on dark
	 * sections, the primary button, and the accent orb, borders and shadows.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_footer(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'on-accent',
				'on-dark',
				'accent',
				'link',
				'link-blue',
				'btn-mid',
			],
			[ 'btn_primary' ]
		);

		$root = '{{WRAPPER}} .nexora-ph';

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'base',
						'label'   => 'Footer base',
						'default' => '#0A131B',
						'group'   => 'fill',
					],
					[
						'token'   => 'text',
						'label'   => 'Footer text and links',
						'default' => '#C9D3DC',
						'group'   => 'text',
					],
					[
						'token'   => 'text-soft',
						'label'   => 'Footer column text (alternate layout)',
						'default' => '#A9B6C2',
						'group'   => 'text',
					],
					[
						'token'   => 'text-muted',
						'label'   => 'Footer muted text',
						'default' => '#8FA0AF',
						'group'   => 'text',
					],
					[
						'token'   => 'hover',
						'label'   => 'Footer link hover / email / call to action',
						'default' => '#FFB08C',
						'group'   => 'accent',
					],
					[
						'token'   => 'stars',
						'label'   => 'Review stars',
						'default' => '#F5B83D',
						'group'   => 'accent',
					],
					[
						'token'   => 'soc-text',
						'label'   => 'Social icon',
						'default' => '#DCE4EA',
						'group'   => 'button',
					],
					[
						'token'   => 'soc-border',
						'label'   => 'Social icon border',
						'default' => '#2A3D4D',
						'group'   => 'button',
					],
					[
						'token'   => 'head-border',
						'label'   => 'Column heading border',
						'default' => '#1F3141',
						'group'   => 'surface',
					],
					[
						'token'   => 'bottom-border',
						'label'   => 'Bottom bar border',
						'default' => '#1A2A38',
						'group'   => 'surface',
					],
					[
						'token'   => 'logo-blue',
						'label'   => 'Wordmark gradient — blue (0%)',
						'default' => '#5FB0E8',
						'group'   => 'fill',
					],
					[
						'token'   => 'logo-light',
						'label'   => 'Wordmark gradient — light (30%)',
						'default' => '#FFFFFF',
						'group'   => 'fill',
					],
					[
						'token'   => 'logo-peach',
						'label'   => 'Wordmark gradient — peach (62%)',
						'default' => '#FFB08C',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_radial_gradient( 'wash_orange', 'Footer wash — orange top', 'fill', '{{WRAPPER}} .nexora-ph .footer-sec', 'ph-footer-wash-orange', '700px 360px', '92% 0%', 'rgba(241,94,34,.16)', 'rgba(241,94,34,0)', 60 ),
					nexora_ph_style_colors_radial_gradient( 'wash_blue', 'Footer wash — blue left', 'fill', '{{WRAPPER}} .nexora-ph .footer-sec', 'ph-footer-wash-blue', '700px 400px', '0% 30%', 'rgba(35,116,172,.18)', 'rgba(35,116,172,0)', 62 ),
					nexora_ph_style_colors_linear_gradient( 'hover_fill', 'Social / AI button hover fill', 'button', $root, 'ph-footer-hover-fill', '#FF8A4C', '#F15E22', 145 ),
				]
			),
		];
	}
}
