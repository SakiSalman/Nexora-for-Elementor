<?php
/**
 * Cold Email ce-problem style color inventory (phase 3).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_ce_problem' ) ) {
	/**
	 * Curated color controls for the ce-problem widget (plan section 6.2).
	 *
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>, include_shared: bool}
	 */
	function nexora_ph_style_colors_inventory_ce_problem(): array {
		$subset = nexora_ph_style_colors_shared_subset(
			[ 'ink', 'lead', 'muted', 'accent' ],
			[ 'grad_text', 'mid_base' ],
			[
				'solids'    => [
					'ink'    => 'Heading text',
					'lead'   => 'Description text',
					'muted'  => 'Step description text',
					'accent' => 'Accent dots and bars',
				],
				'gradients' => [
					'grad_text' => 'Highlighted words (gradient)',
					'mid_base'  => 'Card background',
				],
			]
		);

		return [
			'solids'         => $subset['solids'],
			'gradients'      => array_merge(
				$subset['gradients'],
				[
					// Timeline progress: the four-stop design gradient painted by .pt-fill;
					// the scroll script only animates its scaleY.
					nexora_ph_style_colors_stops_gradient(
						[
							'name'     => 'timeline',
							'label'    => 'Timeline progress (gradient)',
							'group'    => 'accent',
							'selector' => '{{WRAPPER}} .nexora-ph-ce-problem .pt-fill',
							'prefix'   => 'ph-ce-problem-timeline',
							'type'     => 'linear',
							'angle'    => 180,
							'stops'    => [
								[ '#F15E22', 0 ],
								[ '#FF9D72', 45 ],
								[ '#5FB0E8', 80 ],
								[ '#2374AC', 100 ],
							],
						]
					),
					// Active step number: the gradient text fill of .pt-item.on .pt-num.
					nexora_ph_style_colors_stops_gradient(
						[
							'name'     => 'num',
							'label'    => 'Active step number (gradient)',
							'group'    => 'fill',
							'selector' => '{{WRAPPER}} .nexora-ph-ce-problem .pt-item.on .pt-num',
							'prefix'   => 'ph-ce-problem-num',
							'type'     => 'linear',
							'angle'    => 160,
							'stops'    => [
								[ '#FF9D72', 0 ],
								[ '#F15E22', 50 ],
								[ '#2374AC', 100 ],
							],
						]
					),
				]
			),
			'include_shared' => false,
		];
	}
}
