<?php
/**
 * Prospects Hive contact style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_contact' ) ) {
	/**
	 * Return contact-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag, check
	 * badges and gradient text. The form panel, fields, radio pills and submit
	 * button have their own controls.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_contact(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'on-accent',
				'accent',
				'link',
				'link-blue',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'tag-border',
				'chip-orange-text',
			],
			[ 'tag', 'grad_text', 'check' ]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'field-border',
						'label'   => 'Form field and pill border',
						'default' => '#E6DDD3',
						'group'   => 'surface',
					],
					[
						'token'   => 'pill-checked',
						'label'   => 'Selected pill background',
						'default' => '#FFF1E9',
						'group'   => 'surface',
					],
					[
						'token'   => 'submit-hover-mid',
						'label'   => 'Submit button hover — middle stop',
						'default' => '#F15E22',
						'group'   => 'button',
					],
					[
						'token'   => 'founder-mid',
						'label'   => 'Founder card — middle stop',
						'default' => '#FFF8F3',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_radial_gradient( 'wash_orange', 'Section wash — orange', 'fill', '{{WRAPPER}} .nexora-ph .ct-sec', 'ph-contact-wash-orange', '800px 480px', '90% 10%', 'rgba(241,94,34,.16)', 'rgba(241,94,34,0)', 60 ),
					nexora_ph_style_colors_radial_gradient( 'wash_blue', 'Section wash — blue', 'fill', '{{WRAPPER}} .nexora-ph .ct-sec', 'ph-contact-wash-blue', '800px 480px', '0% 100%', 'rgba(35,116,172,.16)', 'rgba(35,116,172,0)', 60 ),
					nexora_ph_style_colors_linear_gradient( 'wash_base', 'Section wash — base', 'fill', '{{WRAPPER}} .nexora-ph .ct-sec', 'ph-contact-wash-base', '#FFF6F0', '#F3F7FC', 180 ),
					nexora_ph_style_colors_solid_mid_gradient( 'founder', 'Founder card', 'fill', '{{WRAPPER}} .nexora-ph .founder-card', 'ph-contact-founder', '#FFFFFF', '#F4F8FC', 135, '#FFF8F3', 60 ),
					nexora_ph_style_colors_linear_gradient( 'submit', 'Submit button', 'button', '{{WRAPPER}} .nexora-ph .ct-form-panel input.wpcf7-submit, {{WRAPPER}} .nexora-ph .ct-form-panel .ct-form-submit', 'ph-contact-submit', '#223647', '#0B1620', 180 ),
					nexora_ph_style_colors_solid_mid_gradient( 'submit_hover', 'Submit button hover', 'button', '{{WRAPPER}} .nexora-ph .ct-form-panel input.wpcf7-submit, {{WRAPPER}} .nexora-ph .ct-form-panel .ct-form-submit', 'ph-contact-submit-hover', '#FF8350', '#DC5016', 180, '#F15E22', 55 ),
				]
			),
		];
	}
}
