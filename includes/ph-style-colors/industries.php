<?php
/**
 * Prospects Hive industries style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_industries' ) ) {
	/**
	 * Return industries-only colors plus the shared PH entries the section renders.
	 *
	 * Shared entries are cherry-picked: only the shared catalog rules this widget
	 * renders. Gradients with more than two stops keep the inner stops as solid
	 * controls (`-mid`); the picker owns the outer stops and the angle.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_industries(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'card-border', 'surface', 'link-blue', 'muted', 'accent', 'link', 'on-dark', 'more-on-dark', 'tag-border', 'grad-warm', 'grad-rose', 'grad-sky' ],
			[ 'tag', 'ivory_orange', 'ivory_blue', 'ivory_base', 'grad_text' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Explore link (default)',
				'surface' => 'Section background',
				'on-dark' => 'Tile title (hover)',
				'more-on-dark' => 'Explore link (hover)',
				'accent' => 'Accent / highlight',
				'link' => 'Link / accent',
				'link-blue' => 'Industry icon',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'card-border' => 'Card border',
				'tag-border' => 'Eyebrow tag border',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'grad_text' => 'Heading highlight gradient',
				'ivory_orange' => 'Ivory section glow — orange',
				'ivory_blue' => 'Ivory section glow — blue',
				'ivory_base' => 'Ivory section background',
			],
			]
		);

		// Hover text paints belong under Colors — Text (not Accent).
		foreach ( $shared['solids'] as $i => $solid ) {
			if ( ! is_array( $solid ) ) {
				continue;
			}
			$token = isset( $solid['token'] ) ? (string) $solid['token'] : '';
			if ( in_array( $token, [ 'on-dark', 'more-on-dark' ], true ) ) {
				$shared['solids'][ $i ]['group'] = 'text';
			}
		}

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'tile-hover-border',
						'label'   => 'Tile hover border',
						'default' => '#F7B596',
						'group'   => 'surface',
					],
					[
						'token'   => 'icon-active-mid',
						'label'   => 'Active industry icon — middle stop',
						'default' => '#F15E22',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'ind_tile',
						'label'    => 'Industry tile background',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ind-tile, {{WRAPPER}} .nexora-ph .ind-grid:hover .ind-tile.on:not(:hover), {{WRAPPER}} .nexora-ph .ind-tile.on, {{WRAPPER}} .nexora-ph .ind-grid:hover .ind-tile:hover',
						'prefix'   => 'ph-industries-tile',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#FFFCFA', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'ind_tile_glow',
						'label'    => 'Tile corner glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ind-tile::after',
						'prefix'   => 'ph-industries-tile-glow',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(241,94,34,.22)', 0 ],
							[ 'rgba(241,94,34,0)', 70 ],
						],
						'size'     => 'circle',
						'origin'   => '50% 50%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'ind_icon',
						'label'    => 'Industry icon tile',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ind-ic, {{WRAPPER}} .nexora-ph .ind-grid:hover .ind-tile.on:not(:hover) .ind-ic',
						'prefix'   => 'ph-industries-icon',
						'type'     => 'linear',
						'stops'    => [
							[ '#F0F7FC', 0 ],
							[ '#D8EAF6', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'ind_icon_active',
						'label'    => 'Active industry icon tile',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ind-tile:hover .ind-ic, {{WRAPPER}} .nexora-ph .ind-tile.on .ind-ic, {{WRAPPER}} .nexora-ph .ind-grid:hover .ind-tile:hover .ind-ic',
						'prefix'   => 'ph-industries-icon-active',
						'type'     => 'linear',
						'stops'    => [
							[ '#FF8350', 0 ],
							[ '#F15E22', 60 ],
							[ '#D94E14', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'ind_wipe_orange',
						'label'    => 'Tile wipe glow — orange (hover)',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ind-tile::before',
						'prefix'   => 'ph-industries-wipe-orange',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(241,94,34,.38)', 0 ],
							[ 'rgba(241,94,34,0)', 70 ],
						],
						'size'     => '320px 200px',
						'origin'   => '100% 0%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'ind_wipe_blue',
						'label'    => 'Tile wipe glow — blue (hover)',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ind-tile::before',
						'prefix'   => 'ph-industries-wipe-blue',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(35,116,172,.45)', 0 ],
							[ 'rgba(35,116,172,0)', 70 ],
						],
						'size'     => '320px 220px',
						'origin'   => '0% 100%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'ind_wipe_base',
						'label'    => 'Tile wipe (hover)',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ind-tile::before',
						'prefix'   => 'ph-industries-wipe-base',
						'type'     => 'linear',
						'stops'    => [
							[ '#16283C', 0 ],
							[ '#0B1620', 70 ],
						],
						'angle'    => 160,
					] ),
				]
			),
		];
	}
}
