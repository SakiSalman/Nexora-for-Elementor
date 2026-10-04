<?php
/**
 * Prospects Hive nav style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_nav' ) ) {
	/**
	 * Return nav-only colors plus the shared PH entries the nav renders.
	 *
	 * The nav uses these shared rules: base text and links, muted menu copy, the
	 * primary and dark buttons, white text on the dark call-to-action card, and
	 * the accent/blue/ink tints behind its glass shadows.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_nav(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'on-accent',
				'on-dark',
				'lead-on-dark',
				'accent',
				'link',
				'link-blue',
				'blue-accent',
				'btn-mid',
			],
			[ 'btn_primary', 'btn_dark', 'btn_dark_hover' ]
		);

		// Every nav gradient sets its variables on the header so panels inherit them.
		$root = '{{WRAPPER}} .nexora-ph .nav';

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'text',
						'label'   => 'Menu link text',
						'default' => '#2B3843',
						'group'   => 'text',
					],
					[
						'token'   => 'label',
						'label'   => 'Menu section label',
						'default' => '#8FA0AF',
						'group'   => 'text',
					],
					[
						'token'   => 'item-border',
						'label'   => 'Menu item hover / featured border',
						'default' => '#FBD3BF',
						'group'   => 'surface',
					],
					[
						'token'   => 'other-border',
						'label'   => 'Other-service pill border',
						'default' => '#CFE2F1',
						'group'   => 'surface',
					],
					[
						'token'   => 'others-border',
						'label'   => 'Other services panel border',
						'default' => '#D6E7F4',
						'group'   => 'surface',
					],
					[
						'token'   => 'bar-mid',
						'label'   => 'Nav bar middle tint (45%, 70% opacity)',
						'default' => '#FFF4EC',
						'group'   => 'fill',
					],
					[
						'token'   => 'bar-solid-mid',
						'label'   => 'Scrolled nav bar middle tint (45%, 86% opacity)',
						'default' => '#FFF6F0',
						'group'   => 'fill',
					],
					[
						'token'   => 'mega-mid',
						'label'   => 'Mega panel middle tint (45%, 90% opacity)',
						'default' => '#FFF6F0',
						'group'   => 'fill',
					],
					[
						'token'   => 'menu-mid',
						'label'   => 'Dropdown / phone menu middle tint (55%, 94% opacity)',
						'default' => '#FFF4EC',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_glass_gradient( 'bar', 'Nav bar glass', 'fill', $root, 'ph-nav-bar', 'rgba(255,255,255,.82)', 'rgba(232,242,251,.72)', 120, '#FFF4EC', 70, 45 ),
					nexora_ph_style_colors_glass_gradient( 'bar_solid', 'Nav bar glass (scrolled)', 'fill', $root, 'ph-nav-bar-solid', 'rgba(255,255,255,.9)', 'rgba(236,244,251,.88)', 120, '#FFF6F0', 86, 45 ),
					nexora_ph_style_colors_glass_gradient( 'mega', 'Mega panel glass', 'fill', $root, 'ph-nav-mega', 'rgba(255,255,255,.92)', 'rgba(236,244,251,.92)', 135, '#FFF6F0', 90, 45 ),
					nexora_ph_style_colors_glass_gradient( 'menu', 'Dropdown / phone menu glass', 'fill', $root, 'ph-nav-menu', 'rgba(255,255,255,.96)', 'rgba(234,243,251,.96)', 160, '#FFF4EC', 94, 55 ),
					nexora_ph_style_colors_linear_gradient( 'feat', 'Featured menu card', 'fill', $root, 'ph-nav-feat', '#FFF1E8', '#FFFFFF', 135, 0, 70 ),
					nexora_ph_style_colors_linear_gradient( 'ic', 'Menu icon tile', 'fill', $root, 'ph-nav-ic', '#FFF3EC', '#FFE0CF', 145 ),
					[
						'name'           => 'feat_icon',
						'label'          => 'Featured menu icon tile',
						'group'          => 'fill',
						'selector'       => $root,
						'default'        => [
							'background'     => 'gradient',
							'color'          => '#FF8350',
							'color_b'        => '#D94E14',
							'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
							'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
							'gradient_type'  => 'linear',
							'gradient_angle' => [ 'unit' => 'deg', 'size' => 145 ],
						],
						'signature'      => 'linear-gradient(var(--ph-nav-feat-icon-angle,145deg),var(--ph-nav-feat-icon,#FF8350) var(--ph-nav-feat-icon-start-stop,0%),var(--ph-btn-mid,#F15E22) 60%,var(--ph-nav-feat-icon-end,#D94E14) var(--ph-nav-feat-icon-end-stop,100%))',
						'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-nav-feat-icon' ) ),
						'notes'          => 'Three-stop CSS-var bridge keeps --ph-btn-mid (#F15E22) at 60%; the gradient control owns the outer stops.',
					],
					nexora_ph_style_colors_linear_gradient( 'others', 'Other services panel', 'fill', $root, 'ph-nav-others', 'rgba(240,247,252,.95)', 'rgba(255,255,255,.9)', 135 ),
					nexora_ph_style_colors_linear_gradient( 'oic', 'Other-service icon tile', 'fill', $root, 'ph-nav-oic', '#E4F1FA', '#C7DFF1', 145 ),
					nexora_ph_style_colors_linear_gradient( 'arrow', 'Call-to-action card arrow', 'fill', $root, 'ph-nav-arrow', '#FF8350', '#F15E22', 145 ),
					nexora_ph_style_colors_radial_gradient( 'cta_orange', 'Call-to-action card wash — orange', 'fill', $root, 'ph-nav-cta-orange', '300px 180px', '100% 0%', 'rgba(241,94,34,.55)', 'rgba(241,94,34,0)', 70 ),
					nexora_ph_style_colors_radial_gradient( 'cta_blue', 'Call-to-action card wash — blue', 'fill', $root, 'ph-nav-cta-blue', '300px 200px', '0% 100%', 'rgba(35,116,172,.5)', 'rgba(35,116,172,0)', 70 ),
					nexora_ph_style_colors_linear_gradient( 'cta_base', 'Call-to-action card base', 'fill', $root, 'ph-nav-cta-base', '#16283C', '#0B1620', 160 ),
					nexora_ph_style_colors_linear_gradient( 'menu_btn', 'Phone menu button', 'button', $root, 'ph-nav-menu-btn', '#FFFFFF', '#F4F7FA', 180 ),
				]
			),
		];
	}
}
