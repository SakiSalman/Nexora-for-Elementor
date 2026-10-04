<?php
/**
 * Prospects Hive video style color inventory.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/_shared-tokens.php';

if ( ! function_exists( 'nexora_ph_style_colors_inventory_video' ) ) {
	/**
	 * Return video-only colors plus the shared PH entries the section renders.
	 *
	 * The widget uses these shared rules: base text and links, the tag, lead and
	 * muted copy, gradient text, the dark "mid" video box, the orange icon tile,
	 * the play button fill and the empty cover fill.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_inventory_video(): array {
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
				'grad-warm',
				'grad-rose',
				'grad-sky',
				'tag-border',
			],
			[ 'tag', 'grad_text', 'icon_o', 'mid_orange', 'mid_blue', 'mid_base', 'play_btn', 'media_empty_glow', 'media_empty_base' ]
		);

		$frame   = '{{WRAPPER}} .nexora-ph .vid-frame';
		$pill    = '{{WRAPPER}} .nexora-ph .benefit-pill';
		$divider = '{{WRAPPER}} .nexora-ph .benefit-item + .benefit-item::before';

		$pill_fill              = nexora_ph_style_colors_linear_gradient( 'pill_fill', 'Benefit pill fill', 'fill', $pill, 'ph-video-pill-fill', '#FFFFFF', '#FFFFFF', 120 );
		$pill_fill['signature'] = 'linear-gradient(var(--ph-video-pill-fill-angle,120deg),var(--ph-video-pill-fill,#FFFFFF) var(--ph-video-pill-fill-start-stop,0%),var(--ph-video-pill-fill-a,#FFF7F1) 38%,var(--ph-video-pill-fill-b,#FFEFE5) 62%,var(--ph-video-pill-fill-end,#FFFFFF) var(--ph-video-pill-fill-end-stop,100%))';
		$pill_fill['notes']     = 'The picker owns the outer stops. The two warm tints at 38% and 62% stay fixed solids.';

		$pill_ring              = nexora_ph_style_colors_linear_gradient( 'pill_ring', 'Benefit pill border', 'fill', $pill, 'ph-video-pill-ring', 'rgba(241,94,34,.5)', 'rgba(241,94,34,.45)', 115 );
		$pill_ring['signature'] = 'linear-gradient(var(--ph-video-pill-ring-angle,115deg),var(--ph-video-pill-ring,rgba(241,94,34,.5)) var(--ph-video-pill-ring-start-stop,0%),color-mix(in srgb,var(--ph-video-pill-ring-a,#FFC4AA) 55%,transparent) 30%,rgba(255,255,255,.95) 55%,color-mix(in srgb,var(--ph-video-pill-ring-b,#FFB08C) 60%,transparent) 80%,var(--ph-video-pill-ring-end,rgba(241,94,34,.45)) var(--ph-video-pill-ring-end-stop,100%))';
		$pill_ring['notes']     = 'The picker owns the outer orange stops. The peach tints at 30% and 80% stay fixed solids blended at 55% and 60%; the white highlight at 55% stays fixed.';

		return [
			'include_shared' => false,
			'solids'         => array_merge(
				$shared['solids'],
				[
					[
						'token'   => 'bar-text',
						'label'   => 'Player bar text',
						'default' => '#DCE4EA',
						'group'   => 'text',
					],
					[
						'token'   => 'frame-mid',
						'label'   => 'Video frame middle tint (40% opacity)',
						'default' => '#FF9D72',
						'group'   => 'fill',
					],
					[
						'token'   => 'pill-fill-a',
						'label'   => 'Benefit pill fill tint (38%)',
						'default' => '#FFF7F1',
						'group'   => 'fill',
					],
					[
						'token'   => 'pill-fill-b',
						'label'   => 'Benefit pill fill tint (62%)',
						'default' => '#FFEFE5',
						'group'   => 'fill',
					],
					[
						'token'   => 'pill-ring-a',
						'label'   => 'Benefit pill border tint (30%, 55% opacity)',
						'default' => '#FFC4AA',
						'group'   => 'fill',
					],
					[
						'token'   => 'pill-ring-b',
						'label'   => 'Benefit pill border tint (80%, 60% opacity)',
						'default' => '#FFB08C',
						'group'   => 'fill',
					],
					[
						'token'   => 'divider-mid',
						'label'   => 'Benefit divider (50%, 35% opacity)',
						'default' => '#F15E22',
						'group'   => 'fill',
					],
				]
			),
			'gradients'      => array_merge(
				$shared['gradients'],
				[
					nexora_ph_style_colors_glass_gradient( 'frame', 'Video frame', 'fill', $frame, 'ph-video-frame', 'rgba(241,94,34,.7)', 'rgba(35,116,172,.7)', 135, '#FF9D72', 40, 50 ),
					nexora_ph_style_colors_linear_gradient( 'vbar', 'Player progress bar', 'button', '{{WRAPPER}} .nexora-ph .vbar > span', 'ph-video-vbar', '#FF8A4C', '#F15E22', 90 ),
					$pill_fill,
					$pill_ring,
					nexora_ph_style_colors_glass_gradient( 'divider', 'Benefit divider', 'fill', $divider, 'ph-video-divider', 'rgba(241,94,34,0)', 'rgba(241,94,34,0)', 180, '#F15E22', 35, 50 ),
				]
			),
		];
	}
}
