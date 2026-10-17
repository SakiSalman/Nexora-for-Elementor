<?php
/**
 * Cold Email pipeline stage data. Defaults are the five design stages,
 * copied verbatim from the design export's PL array.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_pipeline_default_stages' ) ) {
	/**
	 * The five pipeline stages from the design export.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_ce_pipeline_default_stages(): array {
		$json = '[{"title": "Reach buyers that match your ICP", "body": "Tighter account criteria help your outreach reach buyers who fit your offer, face the right problem, and have a stronger reason to respond.", "points": ["Filter accounts by ICP fit before they enter a list", "Use company data and buyer signals to remove low-fit prospects", "Match decision-makers to the problem you solve", "One clear buyer group per campaign"]}, {"title": "Land in the inbox, not the spam folder", "body": "Strong deliverability keeps every campaign visible. We protect your domains and sender reputation so more emails reach real inboxes.", "points": ["Separate sending domains protect your main domain", "SPF, DKIM and DMARC set up before launch", "Gradual warm-up and safe daily limits", "Ongoing bounce and inbox placement checks"]}, {"title": "Give every prospect a reason to reply", "body": "Relevant emails get replies. We write around the prospect’s role, company, and timing instead of one generic template.", "points": ["Messaging built around role-specific pains", "First lines based on real company signals", "Clear, low-friction calls to action", "Angles tested and refined with reply data"]}, {"title": "Turn replies into real sales conversations", "body": "Not every reply is a lead. We separate interest from objections and move qualified prospects to your team fast.", "points": ["Replies reviewed and tagged quickly", "Interest separated from objections", "Qualified leads routed into your CRM", "Meetings booked onto your calendar"]}, {"title": "Scale what works, safely", "body": "Once campaigns prove themselves, we grow volume around the segments and messages that keep producing qualified interest.", "points": ["Add inboxes without hurting reputation", "Expand the segments and angles that win", "Keep lead quality stable as volume grows", "Monthly reviews guide the next step"]}]';
		$data = json_decode( $json, true );

		return is_array( $data ) ? $data : [];
	}
}

if ( ! function_exists( 'nexora_ph_ce_pipeline_json' ) ) {
	/**
	 * JSON safe to place inside a script tag.
	 *
	 * @param array<int, array<string, mixed>> $stages Stages.
	 */
	function nexora_ph_ce_pipeline_json( $stages ): string {
		$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
		$json  = function_exists( 'wp_json_encode' )
			? wp_json_encode( $stages, $flags )
			: json_encode( $stages, $flags );

		return is_string( $json ) ? $json : '[]';
	}
}
