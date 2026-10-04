<?php
/**
 * Prospects Hive growth style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_growth' ) ) {
	/**
	 * Return growth-only colors plus the shared PH entries the section renders.
	 *
	 * Shared entries are cherry-picked: only the shared catalog rules this widget
	 * renders. Gradients with more than two stops keep the inner stops as solid
	 * controls (`-mid`); the picker owns the outer stops and the angle.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_growth(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[ 'on-accent', 'surface', 'accent', 'ink', 'tag-border', 'muted', 'link', 'chip-blue', 'link-blue', 'blue-accent', 'lead', 'grad-warm', 'grad-rose', 'grad-sky' ],
			[ 'tag', 'ivory_orange', 'ivory_blue', 'ivory_base', 'grad_text' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'live-dot',
						'label'   => 'Pipeline results dot',
						'default' => '#22C55E',
						'group'   => 'accent',
					],
					[
						'token'   => 'step-border',
						'label'   => 'Step card border',
						'default' => '#EEF1F4',
						'group'   => 'surface',
					],
					[
						'token'   => 'step-active-border',
						'label'   => 'Active step card border',
						'default' => '#F7B596',
						'group'   => 'surface',
					],
					[
						'token'   => 'steps-line-mid',
						'label'   => 'Steps connector line — middle stop',
						'default' => '#FF9D72',
						'group'   => 'fill',
					],
					[
						'token'   => 'no-active-mid',
						'label'   => 'Active step number badge — middle stop',
						'default' => '#F15E22',
						'group'   => 'fill',
					],
					[
						'token'   => 'bar-2-mid',
						'label'   => 'Funnel tier 2 — middle stop',
						'default' => '#3A8BC4',
						'group'   => 'fill',
					],
					[
						'token'   => 'bar-3-mid',
						'label'   => 'Funnel tier 3 — middle stop',
						'default' => '#FF7A3D',
						'group'   => 'fill',
					],
					[
						'token'   => 'bar-4-mid',
						'label'   => 'Funnel tier 4 — middle stop',
						'default' => '#8C7DC0',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_steps_line',
						'label'    => 'Steps connector line',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-steps::before',
						'prefix'   => 'ph-growth-steps-line',
						'type'     => 'linear',
						'stops'    => [
							[ '#F15E22', 0 ],
							[ '#FF9D72', 50 ],
							[ '#5FB0E8', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_step_card',
						'label'    => 'Step card',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-step',
						'prefix'   => 'ph-growth-step-card',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#FBFCFE', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_step_no',
						'label'    => 'Step number badge',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-no',
						'prefix'   => 'ph-growth-step-no',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFF3EC', 0 ],
							[ '#FFE0CF', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_you_card',
						'label'    => 'Your-role card',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-you',
						'prefix'   => 'ph-growth-you-card',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFF1E8', 0 ],
							[ '#FFFFFF', 75 ],
						],
						'angle'    => 135,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_no_active',
						'label'    => 'Active step number badge',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-you .gs-no, {{WRAPPER}} .nexora-ph .gs-step.on .gs-no',
						'prefix'   => 'ph-growth-no-active',
						'type'     => 'linear',
						'stops'    => [
							[ '#FF8350', 0 ],
							[ '#F15E22', 60 ],
							[ '#D94E14', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_panel_blue',
						'label'    => 'Results panel — blue glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-right',
						'prefix'   => 'ph-growth-panel-blue',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(95,176,232,.16)', 0 ],
							[ 'rgba(95,176,232,0)', 70 ],
						],
						'size'     => '420px 260px',
						'origin'   => '50% 0%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_panel_orange',
						'label'    => 'Results panel — orange glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-right',
						'prefix'   => 'ph-growth-panel-orange',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(241,94,34,.12)', 0 ],
							[ 'rgba(241,94,34,0)', 70 ],
						],
						'size'     => '380px 240px',
						'origin'   => '50% 100%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_panel_base',
						'label'    => 'Results panel — base',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-right',
						'prefix'   => 'ph-growth-panel-base',
						'type'     => 'linear',
						'stops'    => [
							[ 'rgba(255,255,255,.94)', 0 ],
							[ 'rgba(247,250,253,.8)', 100 ],
						],
						'angle'    => 160,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_bar_1',
						'label'    => 'Funnel tier 1',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-b1',
						'prefix'   => 'ph-growth-bar-1',
						'type'     => 'linear',
						'stops'    => [
							[ '#F4F9FD', 0 ],
							[ '#D6E8F6', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_bar_1_text',
						'label'    => 'Funnel tier 1 value text',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-b1 .gs-v',
						'prefix'   => 'ph-growth-bar-1-text',
						'type'     => 'linear',
						'stops'    => [
							[ '#1A5A87', 0 ],
							[ '#2374AC', 100 ],
						],
						'angle'    => 90,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_bar_2',
						'label'    => 'Funnel tier 2',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-b2',
						'prefix'   => 'ph-growth-bar-2',
						'type'     => 'linear',
						'stops'    => [
							[ '#6DB8EA', 0 ],
							[ '#3A8BC4', 45 ],
							[ '#2374AC', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_bar_3',
						'label'    => 'Funnel tier 3',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-b3',
						'prefix'   => 'ph-growth-bar-3',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFA478', 0 ],
							[ '#FF7A3D', 45 ],
							[ '#F15E22', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_bar_4',
						'label'    => 'Funnel tier 4',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-b4',
						'prefix'   => 'ph-growth-bar-4',
						'type'     => 'linear',
						'stops'    => [
							[ '#3E8FCB', 0 ],
							[ '#8C7DC0', 48 ],
							[ '#F4895A', 100 ],
						],
						'angle'    => 120,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'gs_step_active',
						'label'    => 'Active step card',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .gs-step.on',
						'prefix'   => 'ph-growth-step-active',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFF1E8', 0 ],
							[ '#FFFFFF', 70 ],
						],
						'angle'    => 135,
					] ),
				]
			),
		];
	}
}
