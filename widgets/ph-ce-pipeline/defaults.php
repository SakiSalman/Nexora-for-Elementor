<?php
/**
 * Default content for CE Pipeline — the design copy from the Cold Email export.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_pipeline_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_pipeline_defaults(): array {
		return array(
			'eyebrow'         => 'B2B Pipeline',
			'heading_tag'     => 'h2',
			'heading'         => 'Cold email system that creates',
			'highlight'       => 'opportunities for B2B business',
			'lead'            => 'Five parts work together. When one is weak, the whole pipeline leaks. We build all five.',
			'tablist_label'   => 'Pipeline stages',
			'stages'          => array(
				array(
					'pipeline_stage_label'  => 'Targeting',
					'pipeline_stage_sub'    => 'Right buyers',
					'pipeline_stage_icon'   => '4c823d4f7b',
					'pipeline_stage_title'  => 'Reach buyers that match your ICP',
					'pipeline_stage_body'   => 'Tighter account criteria help your outreach reach buyers who fit your offer, face the right problem, and have a stronger reason to respond.',
					'pipeline_stage_points' => "Filter accounts by ICP fit before they enter a list\nUse company data and buyer signals to remove low-fit prospects\nMatch decision-makers to the problem you solve\nOne clear buyer group per campaign",
				),
				array(
					'pipeline_stage_label'  => 'Deliverability',
					'pipeline_stage_sub'    => 'Inbox, not spam',
					'pipeline_stage_icon'   => '65b3d804f3',
					'pipeline_stage_title'  => 'Land in the inbox, not the spam folder',
					'pipeline_stage_body'   => 'Strong deliverability keeps every campaign visible. We protect your domains and sender reputation so more emails reach real inboxes.',
					'pipeline_stage_points' => "Separate sending domains protect your main domain\nSPF, DKIM and DMARC set up before launch\nGradual warm-up and safe daily limits\nOngoing bounce and inbox placement checks",
				),
				array(
					'pipeline_stage_label'  => 'Relevance',
					'pipeline_stage_sub'    => 'A reason to reply',
					'pipeline_stage_icon'   => 'd4db34a0d0',
					'pipeline_stage_title'  => 'Give every prospect a reason to reply',
					'pipeline_stage_body'   => 'Relevant emails get replies. We write around the prospect’s role, company, and timing instead of one generic template.',
					'pipeline_stage_points' => "Messaging built around role-specific pains\nFirst lines based on real company signals\nClear, low-friction calls to action\nAngles tested and refined with reply data",
				),
				array(
					'pipeline_stage_label'  => 'Qualification',
					'pipeline_stage_sub'    => 'Replies to meetings',
					'pipeline_stage_icon'   => '2e54581cff',
					'pipeline_stage_title'  => 'Turn replies into real sales conversations',
					'pipeline_stage_body'   => 'Not every reply is a lead. We separate interest from objections and move qualified prospects to your team fast.',
					'pipeline_stage_points' => "Replies reviewed and tagged quickly\nInterest separated from objections\nQualified leads routed into your CRM\nMeetings booked onto your calendar",
				),
				array(
					'pipeline_stage_label'  => 'Scale',
					'pipeline_stage_sub'    => 'Grow what works',
					'pipeline_stage_icon'   => 'e7153d2b23',
					'pipeline_stage_title'  => 'Scale what works, safely',
					'pipeline_stage_body'   => 'Once campaigns prove themselves, we grow volume around the segments and messages that keep producing qualified interest.',
					'pipeline_stage_points' => "Add inboxes without hurting reputation\nExpand the segments and angles that win\nKeep lead quality stable as volume grows\nMonthly reviews guide the next step",
				),
			),
		);
	}
}
