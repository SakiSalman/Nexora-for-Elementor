<?php
/**
 * Default content for CE Signal — the design copy from the Cold Email export.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_signal_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_signal_defaults(): array {
		return array(
			'heading_tag'     => 'h2',
			'eyebrow'         => 'Signal-Based Personalization',
			'heading'         => 'The most relevant message,',
			'highlight'       => 'to prospects in pain',
			'description'     => 'Automate complex data extraction to personalize outreach at an unprecedented scale, driving meetings instead of bounces.',
			'account_name'    => 'Acme Corp',
			'account_label'   => 'Target account',
			'account_icon'    => 'a5f1cbccc8',
			'signals_label'   => 'Extracted signals',
			'signals_rows'    => array(
				array( '_id' => 'c9a4f0b2', 'signal_text' => 'Hiring sales director' ),
				array( '_id' => 'e2b7d1c5', 'signal_text' => 'Has 16 sales reps' ),
				array( '_id' => 'a8c2f6d9', 'signal_text' => 'Has $10M target' ),
				array( '_id' => 'f5d3b8e1', 'signal_text' => 'Has Google as client' ),
			),
			'subject_label'   => 'Subj:',
			'subject'         => 'hit quota',
			'paragraphs_rows' => array(
				array(
					'_id'                    => 'b2e9c4a7',
					'signal_paragraph_text'  => 'Hi Manon,',
				),
				array(
					'_id'                    => 'd7a1f8c3',
					'signal_paragraph_text'  => 'Noticed you are currently [[hiring a sales director]] to lead your [[sales team of 16]].',
				),
				array(
					'_id'                    => 'c9b4e2f6',
					'signal_paragraph_text'  => 'Scaling to hit that [[target of $10M]] is tough without the right outbound systems in place, even with tier-1 references like [[Google]].',
				),
				array(
					'_id'                    => 'a3f8d5b1',
					'signal_paragraph_text'  => 'Would you be open to a brief chat on how we automate signal-based pipeline generation?',
				),
			),
			'signature'       => "Best,\nAlex",
			'stats_rows'      => array(
				array(
					'_id'               => 'e8c5a3d7',
					'signal_stat_icon'  => 'fb04fde6c8',
					'signal_stat_value' => '1,108',
					'signal_stat_label' => 'Contacted',
				),
				array(
					'_id'               => 'b6d9f4c2',
					'signal_stat_icon'  => '8f9db77c3f',
					'signal_stat_value' => '98',
					'signal_stat_label' => 'Meetings booked',
				),
			),
			'button_text'     => 'Book A Call',
			'button_url'      => 'https://tidycal.com/prospectshive/discovery-call',
		);
	}
}
