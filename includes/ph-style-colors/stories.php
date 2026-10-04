<?php
/**
 * Prospects Hive stories style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_stories' ) ) {
	/**
	 * Return stories-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag and blue
	 * tag, lead and muted copy, the play button fill, the empty poster fill, and
	 * gradient text.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_stories(): array {
		$shared = nexora_ph_style_colors_shared_subset(
			[
				'ink',
				'surface',
				'muted',
				'lead',
				'on-accent',
				'accent',
				'link',
				'link-blue',
				'blue-accent',
				'btn-mid',
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'tag-border',
				'tag-blue-border',
			],
			[ 'tag', 'tag_blue', 'grad_text', 'play_btn', 'media_empty_glow', 'media_empty_base' ],
			[
			'solids'    => [
				'ink' => 'Heading / body text',
				'muted' => 'Quote / captions',
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
				'tag-blue-border' => 'Blue eyebrow tag border',
			],
			'gradients' => [
				'tag' => 'Eyebrow tag',
				'tag_blue' => 'Blue eyebrow tag',
				'grad_text' => 'Heading highlight gradient',
				'play_btn' => 'Play button',
				'media_empty_glow' => 'Empty video poster glow',
				'media_empty_base' => 'Empty video poster background',
			],
			]
		);

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'arrow-border',
						'label'   => 'Slider arrow border',
						'default' => '#E6DDD3',
						'group'   => 'button',
					],
					[
						'token'   => 'dot',
						'label'   => 'Slider dot',
						'default' => '#E3D9CF',
						'group'   => 'surface',
					],
					[
						'token'   => 'tq-warm-border',
						'label'   => 'Warm quote card border',
						'default' => '#F6D7C7',
						'group'   => 'surface',
					],
					[
						'token'   => 'tq-cool-border',
						'label'   => 'Cool quote card border',
						'default' => '#D6E7F4',
						'group'   => 'surface',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_linear_gradient( 'arrow_hover', 'Slider arrow (hover)', 'button', '{{WRAPPER}} .nexora-ph', 'ph-stories-arrow-hover', '#FF8350', '#F15E22', 145 ),
					nexora_ph_style_colors_linear_gradient( 'dot_on', 'Active slide dot', 'button', '{{WRAPPER}} .nexora-ph', 'ph-stories-dot-on', '#FF8A4C', '#F15E22', 90 ),
					nexora_ph_style_colors_linear_gradient( 'tq_warm', 'Warm quote card background', 'fill', '{{WRAPPER}} .nexora-ph', 'ph-stories-tq-warm', '#FFE3D4', '#FFF6F0', 160, 0, 60 ),
					nexora_ph_style_colors_linear_gradient( 'tq_cool', 'Cool quote card background', 'fill', '{{WRAPPER}} .nexora-ph', 'ph-stories-tq-cool', '#DCEBF6', '#F5F9FC', 160, 0, 60 ),
				]
			),
		];
	}
}
