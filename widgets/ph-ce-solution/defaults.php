<?php
/**
 * Default content for CE solution — the design copy from the Cold Email export.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_solution_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_solution_defaults(): array {
		return array(
			'eyebrow'     => 'The Solution',
			'heading_tag' => 'h2',
			'heading'     => 'Build cold email that reaches and',
			'highlight'   => 'converts B2B buyers',
			'description' => 'Better cold email starts with fit, relevance, and follow-up. We fix each one so more prospects reply, qualify, and reach your sales team.',
			'flow_label'  => 'What the system delivers',
			'flow'        => array(
				array(
					'_id'           => 'solflow1',
					'solution_pill' => 'Right buyers',
				),
				array(
					'_id'           => 'solflow2',
					'solution_pill' => 'Inbox',
				),
				array(
					'_id'           => 'solflow3',
					'solution_pill' => 'Replies',
				),
				array(
					'_id'           => 'solflow4',
					'solution_pill' => 'Meetings',
				),
			),
			'button_text' => 'Book A Call',
			'button_url'  => 'https://tidycal.com/prospectshive/discovery-call',
			'cards'       => array(
				array(
					'_id'                       => 'solcard1',
					'solution_card_icon'        => '5c15cf1a97',
					'solution_card_title'       => 'Target the right buyers',
					'solution_card_title_tag'   => 'h3',
					'solution_card_description' => 'We build targeted lead lists around your ICP, buyer roles, and account fit. Your emails reach prospects more likely to need what you sell and respond with interest.',
					'solution_card_result'      => 'Replies from best-fit buyers',
				),
				array(
					'_id'                       => 'solcard2',
					'solution_card_icon'        => 'b830b08605',
					'solution_card_title'       => 'Improve cold email deliverability',
					'solution_card_title_tag'   => 'h3',
					'solution_card_description' => 'We manage email verification, sending domains, and deliverability checks. Your campaigns reach more inboxes without pushing volume that can hurt sender reputation.',
					'solution_card_result'      => 'More emails reach the inbox',
				),
				array(
					'_id'                       => 'solcard3',
					'solution_card_icon'        => '20614a6dbe',
					'solution_card_title'       => 'Human touch in every outreach',
					'solution_card_title_tag'   => 'h3',
					'solution_card_description' => 'We write personalized cold email around each prospect’s role, company, and context. Every message feels relevant, clear, and gives them a real reason to respond.',
					'solution_card_result'      => 'More real sales conversations',
				),
				array(
					'_id'                       => 'solcard4',
					'solution_card_icon'        => '7286789170',
					'solution_card_title'       => 'Qualified sales meetings',
					'solution_card_title_tag'   => 'h3',
					'solution_card_description' => 'We qualify positive replies, route them into your CRM, and keep follow-up moving. Your sales team gets conversations with prospects who have real buying potential.',
					'solution_card_result'      => 'More meetings on your calendar',
				),
			),
		);
	}
}
