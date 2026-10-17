<?php
/**
 * Default content for CE Outlook — the design copy from the Cold Email export.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_outlook_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_outlook_defaults(): array {
		return array(
			'heading_tag' => 'h2',
			'eyebrow'     => '90-Day Outlook',
			'heading'     => 'What your cold email campaign can',
			'highlight'   => 'build in 90 days',
			'description' => 'Each phase builds on real response data, so targeting gets sharper, replies get better, and you only scale what works.',
			'chart_label' => 'Qualified interest over 90 days',
			'legend_text' => 'Pipeline momentum',
			'phases'      => array(
				array(
					'outlook_bar_label'   => 'Weeks 1-2',
					'outlook_bar_height'  => 28,
					'outlook_title_tag'   => 'h3',
					'outlook_title'       => 'A stronger starting point',
					'outlook_description' => 'You start with a defined ICP and cleaner prospect data. Safer sending accounts and clearer campaign angles reduce guesswork before launch.',
					'outlook_phase_on'    => false,
				),
				array(
					'outlook_bar_label'   => 'Weeks 3-4',
					'outlook_bar_height'  => 48,
					'outlook_title_tag'   => 'h3',
					'outlook_title'       => 'Clear early performance signals',
					'outlook_description' => 'Early replies show which segments respond and which messages miss. You also see whether deliverability is strong enough for the next round.',
					'outlook_phase_on'    => false,
				),
				array(
					'outlook_bar_label'   => 'Month 2',
					'outlook_bar_height'  => 72,
					'outlook_title_tag'   => 'h3',
					'outlook_title'       => 'Better replies, better fit',
					'outlook_description' => 'Targeting gets sharper as weak segments drop out. Better-performing messages receive more focus, helping your team earn more useful replies.',
					'outlook_phase_on'    => false,
				),
				array(
					'outlook_bar_label'   => 'Month 3',
					'outlook_bar_height'  => 96,
					'outlook_title_tag'   => 'h3',
					'outlook_title'       => 'Scale around proven results',
					'outlook_description' => 'You scale the parts of your cold email campaign that keep producing qualified interest. Stable inbox health and lead quality guide the next move.',
					'outlook_phase_on'    => true,
				),
			),
		);
	}
}
