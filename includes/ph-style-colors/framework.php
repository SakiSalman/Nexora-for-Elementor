<?php
/**
 * Prospects Hive framework style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_framework' ) ) {
	/**
	 * Return framework-only colors plus the shared PH entries the section renders.
	 *
	 * Shared entries are cherry-picked: only the shared catalog rules this widget
	 * renders. Gradients with more than two stops keep the inner stops as solid
	 * controls (`-mid`); the picker owns the outer stops and the angle.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_framework(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[ 'on-dark', 'lead-on-dark', 'tag-on-dark', 'chip-on-dark', 'ink', 'accent', 'chip-orange', 'chip-orange-text', 'muted-on-dark', 'blue-accent', 'surface', 'link-blue', 'link', 'tag-border', 'lead', 'grad-warm', 'grad-rose', 'grad-sky' ],
			[ 'tag', 'grad_text', 'grid_horizontal', 'grid_vertical' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'panel-base',
						'label'   => 'Panel base',
						'default' => '#132436',
						'group'   => 'surface',
					],
					[
						'token'   => 'chip-bg',
						'label'   => 'Chip and node background',
						'default' => '#1E3248',
						'group'   => 'surface',
					],
					[
						'token'   => 'signal-blue',
						'label'   => 'Signal blue',
						'default' => '#5FB0E8',
						'group'   => 'accent',
					],
					[
						'token'   => 'sky-blue',
						'label'   => 'Sky blue',
						'default' => '#7CC2F0',
						'group'   => 'accent',
					],
					[
						'token'   => 'quiet-text',
						'label'   => 'Quiet text',
						'default' => '#8FA0AF',
						'group'   => 'text',
					],
					[
						'token'   => 'hub-deep',
						'label'   => 'Hub gradient end',
						'default' => '#D9480F',
						'group'   => 'accent',
					],
					[
						'token'   => 'hub-warm',
						'label'   => 'Hub gradient start',
						'default' => '#FF8A4C',
						'group'   => 'accent',
					],
					[
						'token'   => 'packet-warm',
						'label'   => 'Data packet (warm)',
						'default' => '#FFB08C',
						'group'   => 'accent',
					],
					[
						'token'   => 'sec-base-mid',
						'label'   => 'Section wash — base — middle stop 1',
						'default' => '#0F1D2C',
						'group'   => 'fill',
					],
					[
						'token'   => 'sec-base-mid-2',
						'label'   => 'Section wash — base — middle stop 2',
						'default' => '#0B1620',
						'group'   => 'fill',
					],
					[
						'token'   => 'tab-active-mid',
						'label'   => 'Active tab — middle stop',
						'default' => '#F15E22',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_sec_orange',
						'label'    => 'Section wash — orange glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-sec',
						'prefix'   => 'ph-framework-sec-orange',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(241,94,34,.30)', 0 ],
							[ 'rgba(241,94,34,0)', 60 ],
						],
						'size'     => '760px 460px',
						'origin'   => '92% 0%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_sec_blue',
						'label'    => 'Section wash — blue glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-sec',
						'prefix'   => 'ph-framework-sec-blue',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(35,116,172,.42)', 0 ],
							[ 'rgba(35,116,172,0)', 62 ],
						],
						'size'     => '820px 520px',
						'origin'   => '0% 100%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_sec_sky',
						'label'    => 'Section wash — sky glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-sec',
						'prefix'   => 'ph-framework-sec-sky',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(95,176,232,.10)', 0 ],
							[ 'rgba(95,176,232,0)', 70 ],
						],
						'size'     => '600px 400px',
						'origin'   => '55% 55%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_sec_base',
						'label'    => 'Section wash — base',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-sec',
						'prefix'   => 'ph-framework-sec-base',
						'type'     => 'linear',
						'stops'    => [
							[ '#1B2F46', 0 ],
							[ '#0F1D2C', 42 ],
							[ '#0B1620', 70 ],
							[ '#22160F', 100 ],
						],
						'angle'    => 155,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_tab_active',
						'label'    => 'Active tab',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .ftab.on',
						'prefix'   => 'ph-framework-tab-active',
						'type'     => 'linear',
						'stops'    => [
							[ '#FF8A4C', 0 ],
							[ '#F15E22', 55 ],
							[ '#D9480F', 100 ],
						],
						'angle'    => 135,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_vis_blue',
						'label'    => 'Visual panel — blue glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-vis',
						'prefix'   => 'ph-framework-vis-blue',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(35,116,172,.30)', 0 ],
							[ 'rgba(35,116,172,0)', 70 ],
						],
						'size'     => '420px 260px',
						'origin'   => '50% 50%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_vis_orange',
						'label'    => 'Visual panel — orange glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-vis',
						'prefix'   => 'ph-framework-vis-orange',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(241,94,34,.18)', 0 ],
							[ 'rgba(241,94,34,0)', 70 ],
						],
						'size'     => '300px 200px',
						'origin'   => '90% 10%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_vis_base',
						'label'    => 'Visual panel — base',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-vis',
						'prefix'   => 'ph-framework-vis-base',
						'type'     => 'linear',
						'stops'    => [
							[ '#132438', 0 ],
							[ '#0A131B', 100 ],
						],
						'angle'    => 160,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_card_light',
						'label'    => 'Light bubble and mover card',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-bubble, {{WRAPPER}} .nexora-ph .fw-mover',
						'prefix'   => 'ph-framework-card-light',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#F4F7FA', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_bars',
						'label'    => 'Chart bars',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-bars span',
						'prefix'   => 'ph-framework-bars',
						'type'     => 'linear',
						'stops'    => [
							[ '#FF8A4C', 0 ],
							[ '#2374AC', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_icon_warm',
						'label'    => 'Warm icon tile',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-ic, {{WRAPPER}} .nexora-ph .fw-nic, {{WRAPPER}} .nexora-ph .fw-mini',
						'prefix'   => 'ph-framework-icon-warm',
						'type'     => 'linear',
						'stops'    => [
							[ '#FF8A4C', 0 ],
							[ '#F15E22', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'fw_icon_cool',
						'label'    => 'Cool icon tile',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .fw-nic',
						'prefix'   => 'ph-framework-icon-cool',
						'type'     => 'linear',
						'stops'    => [
							[ '#7CC2F0', 0 ],
							[ '#2374AC', 100 ],
						],
						'angle'    => 145,
					] ),
				]
			),
		];
	}
}
