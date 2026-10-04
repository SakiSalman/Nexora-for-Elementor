<?php
/**
 * Prospects Hive process style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_process' ) ) {
	/**
	 * Return process-only colors plus the shared PH entries the section renders.
	 *
	 * Shared entries are cherry-picked: only the shared catalog rules this widget
	 * renders. Gradients with more than two stops keep the inner stops as solid
	 * controls (`-mid`); the picker owns the outer stops and the angle.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_process(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'accent', 'surface', 'chip-orange', 'link-blue', 'link', 'tag-border', 'lead', 'muted', 'card-border', 'card-hover-border', 'chip-blue', 'chip-orange-text', 'grad-warm', 'grad-rose', 'grad-sky' ],
			[ 'tag', 'card', 'ivory_orange', 'ivory_blue', 'ivory_base', 'grad_text' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Step description / captions',
				'lead' => 'Lead / supporting text',
				'surface' => 'Section background',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'grad-warm' => 'Heading gradient — warm stop',
				'grad-rose' => 'Heading gradient — rose stop',
				'grad-sky' => 'Heading gradient — sky stop',
				'card-border' => 'Card border',
				'card-hover-border' => 'Card border (hover)',
				'tag-border' => 'Eyebrow tag border',
				'chip-blue' => 'Blue chip surface',
				'chip-orange' => 'Orange chip surface',
				'chip-orange-text' => 'Orange chip text',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'card' => 'Card background',
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
						'token'   => 'dot-ring',
						'label'   => 'Mobile step dot ring',
						'default' => '#E9EDF1',
						'group'   => 'surface',
					],
					[
						'token'   => 'step-hover-border',
						'label'   => 'Step card border (hover)',
						'default' => '#F7B596',
						'group'   => 'surface',
					],
					[
						'token'   => 'progress-mid',
						'label'   => 'Timeline progress — middle stop',
						'default' => '#FF9D72',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tl_track',
						'label'    => 'Timeline track',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tl-line',
						'prefix'   => 'ph-process-track',
						'type'     => 'linear',
						'stops'    => [
							[ 'rgba(11,22,32,.08)', 0 ],
							[ 'rgba(11,22,32,.08)', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tl_progress',
						'label'    => 'Timeline progress fill',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tl-fill',
						'prefix'   => 'ph-process-progress',
						'type'     => 'linear',
						'stops'    => [
							[ '#F15E22', 0 ],
							[ '#FF9D72', 55 ],
							[ '#F15E22', 100 ],
						],
						'angle'    => 180,
					] ),
					nexora_ph_style_colors_stops_gradient( [
						'name'     => 'tl_dot',
						'label'    => 'Step dot fill',
						'group'    => 'fill',
						'selector' => '{{WRAPPER}} .nexora-ph .tl-dot.tl-pop',
						'prefix'   => 'ph-process-dot',
						'type'     => 'linear',
						'stops'    => [
							[ '#FF8A4C', 0 ],
							[ '#F15E22', 100 ],
						],
						'angle'    => 145,
					] ),
				]
			),
		];
	}
}
