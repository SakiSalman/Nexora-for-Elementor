<?php
/**
 * Prospects Hive tech style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_tech' ) ) {
	/**
	 * Return tech-only colors plus the shared PH entries the section renders.
	 *
	 * Shared entries are cherry-picked: only the shared catalog rules this widget
	 * renders. Gradients with more than two stops keep the inner stops as solid
	 * controls (`-mid`); the picker owns the outer stops and the angle.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_tech(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[ 'on-dark', 'muted-on-dark', 'surface', 'card-border', 'ink', 'muted', 'accent', 'blue-accent', 'lead', 'link-blue', 'link', 'tag-border', 'tag-blue-border', 'grad-warm', 'grad-rose', 'grad-sky' ],
			[ 'tag', 'tag_blue', 'grad_text', 'grid_horizontal', 'grid_vertical' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'tool-border',
						'label'   => 'Tool icon border',
						'default' => '#EEF1F4',
						'group'   => 'surface',
					],
					[
						'token'   => 'sec-base-mid',
						'label'   => 'Section wash — base — middle stop',
						'default' => '#FFFFFF',
						'group'   => 'fill',
					],
					[
						'token'   => 'panel-mid',
						'label'   => 'Tool panel glass — middle stop',
						'default' => '#FFF6F0',
						'group'   => 'fill',
					],
					[
						'token'   => 'divider-mid',
						'label'   => 'Divider line — middle stop 1',
						'default' => '#F6C3AA',
						'group'   => 'fill',
					],
					[
						'token'   => 'divider-mid-2',
						'label'   => 'Divider line — middle stop 2',
						'default' => '#BFDDF2',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_node_dark',
						'label'    => 'Dark workflow node',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .wf-node.rev',
						'prefix'   => 'ph-tech-node-dark',
						'type'     => 'linear',
						'stops'    => [
							[ '#223647', 0 ],
							[ '#0B1620', 100 ],
						],
						'angle'    => 160,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_node_light',
						'label'    => 'Light workflow node',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .wf.grad .wf-node.rev',
						'prefix'   => 'ph-tech-node-light',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#E9EEF3', 100 ],
						],
						'angle'    => 145,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_tool_icon',
						'label'    => 'Tool icon',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tool-ic',
						'prefix'   => 'ph-tech-tool-icon',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#F7F9FB', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_sec_orange',
						'label'    => 'Section wash — orange glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tech-sec',
						'prefix'   => 'ph-tech-sec-orange',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(241,94,34,.10)', 0 ],
							[ 'rgba(241,94,34,0)', 60 ],
						],
						'size'     => '900px 480px',
						'origin'   => '0% 0%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_sec_blue',
						'label'    => 'Section wash — blue glow',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tech-sec',
						'prefix'   => 'ph-tech-sec-blue',
						'type'     => 'radial',
						'stops'    => [
							[ 'rgba(35,116,172,.12)', 0 ],
							[ 'rgba(35,116,172,0)', 62 ],
						],
						'size'     => '900px 520px',
						'origin'   => '100% 100%',
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_sec_base',
						'label'    => 'Section wash — base',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tech-sec',
						'prefix'   => 'ph-tech-sec-base',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFBF8', 0 ],
							[ '#FFFFFF', 45 ],
							[ '#F4F8FC', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_panel',
						'label'    => 'Tool panel glass',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tool-panel',
						'prefix'   => 'ph-tech-panel',
						'type'     => 'linear',
						'stops'    => [
							[ 'rgba(255,255,255,.78)', 0 ],
							[ '#FFF6F0', 45, 62 ],
							[ 'rgba(236,244,251,.7)', 100 ],
						],
						'angle'    => 135,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_tool_icon_sec',
						'label'    => 'Tool icon (section)',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tech-sec .tool-ic',
						'prefix'   => 'ph-tech-tool-icon-sec',
						'type'     => 'linear',
						'stops'    => [
							[ '#FFFFFF', 0 ],
							[ '#F8FAFC', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tc_divider',
						'label'    => 'Divider line',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph',
						'prefix'   => 'ph-tech-divider',
						'type'     => 'linear',
						'stops'    => [
							[ 'rgba(246,195,170,0)', 0 ],
							[ '#F6C3AA', 33.33 ],
							[ '#BFDDF2', 66.67 ],
							[ 'rgba(191,221,242,0)', 100 ],
						],
						'angle'    => 90,
					] ),
				]
			),
		];
	}
}
