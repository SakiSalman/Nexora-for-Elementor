<?php
/**
 * Prospects Hive pricing style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_pricing' ) ) {
	/**
	 * Return pricing-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag, lead and
	 * muted copy, chips, check badges, the card, the primary button, the ivory
	 * section wash, the dark "mid" panel, and gradient text.
	 *
	 * The plan frame, plan card, month cards, highlight badge and plan tabs
	 * previously carried inline gradients in `data.php` and `markup.html`; they
	 * now use the `pr-*` classes in `nexora-ph-pricing.css`.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_pricing(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'lead',
				'on-accent',
				'on-dark',
				'lead-on-dark',
				'muted-on-dark',
				'tag-on-dark',
				'accent',
				'link',
				'link-blue',
				'btn-mid',
				'btn-white-border',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'card-border',
				'card-hover-border',
				'tag-border',
				'chip-blue',
				'chip-orange',
				'chip-orange-text',
			],
			[ 'btn_primary', 'tag', 'card', 'grad_text', 'check', 'ivory_orange', 'ivory_blue', 'ivory_base', 'mid_orange', 'mid_blue', 'mid_base' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Plan description / captions',
				'lead' => 'Lead / supporting text',
				'surface' => 'Section background',
				'on-accent' => 'Text on buttons / accent fills',
				'on-dark' => 'Custom plan title on dark',
				'lead-on-dark' => 'Custom plan description on dark',
				'muted-on-dark' => 'Custom plan muted text on dark',
				'tag-on-dark' => 'Tag text on dark',
				'accent' => 'Accent / highlight',
				'link' => 'Link / icon stroke',
				'link-blue' => 'Blue link / chip text',
				'btn-mid' => 'Primary button middle stop',
				'btn-white-border' => 'Secondary button border',
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
				'btn_primary' => 'Primary button',
				'grad_text' => 'Heading highlight gradient',
				'check' => 'Checkmark badge',
				'mid_orange' => 'Dark plan glow — orange',
				'mid_blue' => 'Dark plan glow — blue',
				'mid_base' => 'Dark plan background',
				'ivory_orange' => 'Ivory section glow — orange',
				'ivory_blue' => 'Ivory section glow — blue',
				'ivory_base' => 'Ivory section background',
			],
			]
		);

		$tab_selector     = '{{WRAPPER}} .nexora-ph .tab.on';
		$month_selector   = '{{WRAPPER}} .nexora-ph .pr-month-';
		$chip_hover_label = 'After-90 chip hover';

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'list-text',
						'label'   => 'Plan feature list text',
						'default' => '#1F2D3A',
						'group'   => 'text',
					],
					[
						'token'   => 'task-text',
						'label'   => 'Month task text',
						'default' => '#2B3843',
						'group'   => 'text',
					],
					[
						'token'   => 'price-divider',
						'label'   => 'Price block divider',
						'default' => '#F0E3D8',
						'group'   => 'surface',
					],
					[
						'token'   => 'day-bg',
						'label'   => 'Day-one note background',
						'default' => '#F5F9FC',
						'group'   => 'surface',
					],
					[
						'token'   => 'day-border',
						'label'   => 'Day-one note border',
						'default' => '#E1ECF4',
						'group'   => 'surface',
					],
					[
						'token'   => 'outcome-warm-bg',
						'label'   => 'Outcome tile (warm) background',
						'default' => '#FFFAF7',
						'group'   => 'surface',
					],
					[
						'token'   => 'outcome-warm-border',
						'label'   => 'Outcome tile (warm) border',
						'default' => '#F6D7C7',
						'group'   => 'surface',
					],
					[
						'token'   => 'outcome-cool-bg',
						'label'   => 'Outcome tile (cool) background',
						'default' => '#F7FBFE',
						'group'   => 'surface',
					],
					[
						'token'   => 'outcome-cool-border',
						'label'   => 'Outcome tile (cool) border',
						'default' => '#D6E7F4',
						'group'   => 'surface',
					],
					[
						'token'   => 'cu-border',
						'label'   => 'Custom plan card border',
						'default' => '#EFE3D8',
						'group'   => 'surface',
					],
					[
						'token'   => 'cu-right-border',
						'label'   => 'Custom plan price panel border',
						'default' => '#F6D7C7',
						'group'   => 'surface',
					],
					[
						'token'   => 'up-pill-bg',
						'label'   => 'Upfront pill background',
						'default' => '#F0FAF3',
						'group'   => 'tag',
					],
					[
						'token'   => 'up-pill-border',
						'label'   => 'Upfront pill border',
						'default' => '#CDEBD6',
						'group'   => 'tag',
					],
					[
						'token'   => 'up-pill-badge',
						'label'   => 'Upfront savings badge',
						'default' => '#22C55E',
						'group'   => 'tag',
					],
					[
						'token'   => 'tab-on-mid',
						'label'   => 'Active plan tab — middle stop',
						'default' => '#F15E22',
						'group'   => 'button',
					],
					[
						'token'   => 'chip-hover-mid',
						'label'   => $chip_hover_label . ' — middle stop',
						'default' => '#F15E22',
						'group'   => 'tag',
					],
					[
						'token'   => 'frame-mid',
						'label'   => 'Plan frame — middle stop',
						'default' => '#FF9D72',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_solid_mid_gradient( 'tab_on', 'Active plan tab', 'button', $tab_selector, 'ph-pricing-tab-on', '#FF8350', '#DC5016', 180, '#F15E22', 60 ),
					nexora_ph_style_colors_solid_mid_gradient( 'chip_hover', $chip_hover_label, 'tag', '{{WRAPPER}} .nexora-ph .af-chip', 'ph-pricing-chip-hover', '#FF8A4C', '#DC5016', 145, '#F15E22', 60 ),
					nexora_ph_style_colors_radial_gradient( 'cu_orange', 'Custom plan card glow — orange', 'fill', '{{WRAPPER}} .nexora-ph .cu-card', 'ph-pricing-cu-orange', '520px 300px', '100% 0%', 'rgba(241,94,34,.12)', 'rgba(241,94,34,0)', 65 ),
					nexora_ph_style_colors_radial_gradient( 'cu_blue', 'Custom plan card glow — blue', 'fill', '{{WRAPPER}} .nexora-ph .cu-card', 'ph-pricing-cu-blue', '520px 300px', '0% 100%', 'rgba(35,116,172,.10)', 'rgba(35,116,172,0)', 65 ),
					nexora_ph_style_colors_linear_gradient( 'cu_right', 'Custom plan price panel background', 'fill', '{{WRAPPER}} .nexora-ph .cu-right', 'ph-pricing-cu-right', '#FFF3EC', '#FFFFFF', 170, 0, 60 ),
					nexora_ph_style_colors_solid_mid_gradient( 'frame', 'Plan frame', 'fill', '{{WRAPPER}} .nexora-ph .pr-frame', 'ph-pricing-frame', '#F15E22', '#5FB0E8', 150, '#FF9D72', 45 ),
					nexora_ph_style_colors_linear_gradient( 'plan', 'Plan card background', 'fill', '{{WRAPPER}} .nexora-ph .pr-plan', 'ph-pricing-plan', '#FFF3EC', '#FFFFFF', 170, 0, 42 ),
					nexora_ph_style_colors_linear_gradient( 'month_1', 'Month 1 card background', 'fill', $month_selector . '1', 'ph-pricing-month-1', '#FFF4ED', '#FFFFFF', 180, 0, 45 ),
					nexora_ph_style_colors_linear_gradient( 'month_2', 'Month 2 card background', 'fill', $month_selector . '2', 'ph-pricing-month-2', '#EEF5FB', '#FFFFFF', 180, 0, 45 ),
					nexora_ph_style_colors_linear_gradient( 'month_3', 'Month 3 card background', 'fill', $month_selector . '3', 'ph-pricing-month-3', '#F2F4F6', '#FFFFFF', 180, 0, 45 ),
					nexora_ph_style_colors_linear_gradient( 'badge_highlight', 'Highlighted plan badge', 'tag', '{{WRAPPER}} .nexora-ph .chip.o.pr-badge-highlight', 'ph-pricing-badge', '#FF8A4C', '#F15E22', 145 ),
				]
			),
		];
	}
}
