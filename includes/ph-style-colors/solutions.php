<?php
/**
 * Prospects Hive solutions style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_solutions' ) ) {
	/**
	 * Return solutions-only colors plus the shared PH entries the section renders.
	 *
	 * Shared entries are cherry-picked: only the shared catalog rules this widget
	 * renders. Gradients with more than two stops keep the inner stops as solid
	 * controls (`-mid`); the picker owns the outer stops and the angle.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_solutions(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[ 'accent', 'blue-accent', 'ink', 'muted', 'surface', 'on-accent', 'lead', 'link', 'link-blue', 'tag-border', 'btn-mid', 'grad-warm', 'grad-rose', 'grad-sky' ],
			[ 'btn_dark', 'tag', 'icon', 'ivory_orange', 'ivory_blue', 'ivory_base', 'grad_text', 'btn_dark_hover' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Description / captions',
				'lead' => 'Lead / supporting text',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'blue-accent' => 'Blue accent / tag dot',
				'btn-mid' => 'Primary button middle stop',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'tag-border' => 'Eyebrow tag border',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'btn_dark' => 'Dark button',
				'btn_dark_hover' => 'Dark button (hover)',
				'icon' => 'Accent icon tile',
				'grad_text' => 'Heading highlight gradient',
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
						'token'   => 'ok-text',
						'label'   => 'Verified count text',
						'default' => '#15803D',
						'group'   => 'accent',
					],
					[
						'token'   => 'ok-badge',
						'label'   => 'Meeting confirmed badge',
						'default' => '#22C55E',
						'group'   => 'accent',
					],
					[
						'token'   => 'meet-border',
						'label'   => 'Meeting pill border',
						'default' => '#D9EFE1',
						'group'   => 'surface',
					],
					[
						'token'   => 'path-line',
						'label'   => 'Connector paths',
						'default' => '#E7DDD3',
						'group'   => 'surface',
					],
					[
						'token'   => 'node-border',
						'label'   => 'Node and bar border',
						'default' => '#EFE3D8',
						'group'   => 'surface',
					],
					[
						'token'   => 'track-bg',
						'label'   => 'Progress track',
						'default' => '#F1E7DE',
						'group'   => 'surface',
					],
					[
						'token'   => 'core-border',
						'label'   => 'Hub core border',
						'default' => '#F6D3BF',
						'group'   => 'surface',
					],
					[
						'token'   => 'box-base-mid',
						'label'   => 'Diagram — base — middle stop',
						'default' => '#FFF7F2',
						'group'   => 'fill',
					],
					[
						'token'   => 'core-mid',
						'label'   => 'Hub core — middle stop',
						'default' => '#FFF4EC',
						'group'   => 'fill',
					],
					[
						'token'   => 'track-fill-mid',
						'label'   => 'Progress fill — middle stop 1',
						'default' => '#5FB0E8',
						'group'   => 'fill',
					],
					[
						'token'   => 'track-fill-mid-2',
						'label'   => 'Progress fill — middle stop 2',
						'default' => '#FF8A4C',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_box_orange',
						'label'    => 'Diagram glow — orange',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-box',
						'prefix'   => 'ph-solutions-box-orange',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(241,94,34,.10)', 0 ],
							[ 'rgba(241,94,34,0)', 70 ],
						],
						'size'     => '60% 50%',
						'origin'   => '50% 45%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_box_blue',
						'label'    => 'Diagram glow — blue',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-box',
						'prefix'   => 'ph-solutions-box-blue',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(35,116,172,.10)', 0 ],
							[ 'rgba(35,116,172,0)', 70 ],
						],
						'size'     => '50% 50%',
						'origin'   => '90% 90%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_box_base',
						'label'    => 'Diagram background',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-box',
						'prefix'   => 'ph-solutions-box-base',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#FFF7F2', 55 ],
							[ '#F2F8FD', 100 ],
						],
						'angle'    => 160,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_grid_dots',
						'label'    => 'Diagram dot grid',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-grid',
						'prefix'   => 'ph-solutions-grid-dots',
						'type'     => 'radial',
						'stops'    => [
							[ '#E6DDD4', '1.1px' ],
							[ 'rgba(230,221,212,0)', '1.1px' ],
						],
						'fixed'    => true,
						'size'     => 'circle',
						'origin'   => '',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_core',
						'label'    => 'Hub core',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-core',
						'prefix'   => 'ph-solutions-core',
						'type'     => 'radial',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#FFF4EC', 60 ],
							[ '#FFE6D8', 100 ],
						],
						'size'     => 'circle',
						'origin'   => '30% 25%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_node_icon_warm',
						'label'    => 'Node icon (warm)',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-ic',
						'prefix'   => 'ph-solutions-node-icon-warm',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFF3EC', 0 ],
							[ '#FFE0CF', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_node_icon_cool',
						'label'    => 'Node icon (cool)',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-n2 .sx-ic, {{WRAPPER}} .nexora-ph .sx-n4 .sx-ic',
						'prefix'   => 'ph-solutions-node-icon-cool',
						'type'     => 'linear',
						'stops'    => [
							[ '#F0F7FC', 0 ],
							[ '#D8EAF6', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'sx_track_fill',
						'label'    => 'Progress fill',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .sx-track i',
						'prefix'   => 'ph-solutions-track-fill',
						'type'     => 'linear',
						'stops'    => [
							[ '#2374AC', 0 ],
							[ '#5FB0E8', 40 ],
							[ '#FF8A4C', 75 ],
							[ '#F15E22', 100 ],
						],
						'angle'    => 90,
					] ),
				]
			),
		];
	}
}
