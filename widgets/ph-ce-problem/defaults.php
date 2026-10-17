<?php
/**
 * Default content for CE Problem — the design copy from the Cold Email export.
 *
 * The golden markup.html is the design slice byte for byte (design section CE2 PROBLEM).
 * The funnel chart's bar labels and leak badges are a fixed visualization dataset
 * (their widths and animation delays are design bytes) and stay static in the template;
 * the chart's standalone copy lines are editable controls below.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_problem_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_problem_defaults(): array {
		return array(
			'heading_tag'   => 'h2',
			'eyebrow'       => 'The Problem',
			'heading'       => 'Why your cold email outreach is',
			'highlight'     => 'not creating leads',
			'description'   => 'Cold email issues rarely start with sending volume. They start when the wrong prospects, weak relevance, and poor follow-up keep real opportunities from becoming qualified leads.',
			'chart_label'   => 'Where cold email leaks',
			'leaks_label'   => '4 leaks found',
			'footnote'      => 'Every leak shrinks the pipeline before sales ever sees it.',
			'rows'          => array(
				array(
					'_id'           => 'e2f4a7c1',
					'problem_num'   => '01.',
					'problem_title' => 'Your Lead Lists Miss Buyers',
					'problem_body'  => 'Poor lead lists put your emails in front of people who do not fit your ICP. That lowers reply quality. Your team wastes time on prospects with little reason to buy.',
				),
				array(
					'_id'           => '9b3d5e18',
					'problem_num'   => '02.',
					'problem_title' => 'Your Emails Land in Spam',
					'problem_body'  => 'Weak email deliverability can push messages into spam before prospects see them. That cuts your reach and replies, making each campaign harder to scale.',
				),
				array(
					'_id'           => '4c7a2f60',
					'problem_num'   => '03.',
					'problem_title' => 'Your Email Copy Feels Generic',
					'problem_body'  => 'Generic email copy gives prospects no clear reason to respond. The message feels copied or irrelevant. Even a matched buyer is likely to ignore your outreach.',
				),
				array(
					'_id'           => 'd8e1b956',
					'problem_num'   => '04.',
					'problem_title' => 'Replies Do Not Become Sales',
					'problem_body'  => 'Interested replies can go nowhere without fast follow-up, clear qualification, and CRM routing. Good prospects get missed before they become real sales opportunities.',
				),
			),
		);
	}
}
