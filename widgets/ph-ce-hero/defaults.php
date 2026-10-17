<?php
/**
 * Default content for CE Hero — the design copy from the Cold Email export.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_hero_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_hero_defaults(): array {
		return array(
			'eyebrow'     => 'Cold Email Marketing',
			'heading_tag' => 'h1',
			'heading'     => 'Cold Email Marketing Services That',
			'highlight'   => 'Book Sales Meetings',
			'description' => 'Not generating enough qualified leads from cold email? We build a better system. We help B2B companies with targeted lead lists, personalized cold email outreach, strong email deliverability, and CRM-connected workflows.',
			'button_text' => 'Book A Call',
			'button_url'  => 'https://tidycal.com/prospectshive/discovery-call',

			// Internal anchor link — the design renders it without target/rel.
			'secondary_text' => 'Ask A Quote',
			'secondary_url'  => '#contact',

			'campaign_title'    => 'Outbound campaign: VP Sales, SaaS',
			'campaign_subtitle' => '4-step sequence, 1,108 prospects',
			'live_text'         => 'Live',
			'meter_label'       => 'Inbox placement',
			'meter_value'       => '96%',

			// The design's sequence-step states → li class + status pill class.
			// The step marker itself is derived in template.php: Sent rows show the
			// ✓ glyph, the other states show the 1-based step number.
			'states'      => array(
				'sent'    => array(
					'li' => ' class="done"',
					'st' => 'ce-st',
				),
				'running' => array(
					'li' => ' class="now"',
					'st' => 'ce-st b',
				),
				'queued'  => array(
					'li' => '',
					'st' => 'ce-st b',
				),
			),

			// Sequence steps (the design's four `ce-steps` rows).
			'steps'       => array(
				array(
					'_id'              => 'step1',
					'hero_step_state'  => 'sent',
					'hero_step_title'  => 'Day 1: Personalized email',
					'hero_step_note'   => 'Signal-based first line',
					'hero_step_status' => 'Sent',
				),
				array(
					'_id'              => 'step2',
					'hero_step_state'  => 'sent',
					'hero_step_title'  => 'Day 3: Follow-up',
					'hero_step_note'   => 'New angle, same thread',
					'hero_step_status' => 'Sent',
				),
				array(
					'_id'              => 'step3',
					'hero_step_state'  => 'running',
					'hero_step_title'  => 'Day 6: Value email',
					'hero_step_note'   => 'Short case study, clear proof',
					'hero_step_status' => 'Running',
				),
				array(
					'_id'              => 'step4',
					'hero_step_state'  => 'queued',
					'hero_step_title'  => 'Day 9: Final email',
					'hero_step_note'   => 'Short, clear next step',
					'hero_step_status' => 'Queued',
				),
			),

			// Result numbers (the design's three `ce-kpis` cells).
			'results'     => array(
				array(
					'_id'               => 'res1',
					'hero_result_value' => '14%',
					'hero_result_label' => 'Reply rate',
				),
				array(
					'_id'               => 'res2',
					'hero_result_value' => '312',
					'hero_result_label' => 'Replies',
				),
				array(
					'_id'               => 'res3',
					'hero_result_value' => '98',
					'hero_result_label' => 'Meetings',
				),
			),

			// The two floating badges keep each design row's shell (different float
			// animation, position and icon); template.php cycles them by row position.
			'badge_shells' => array(
				array(
					'open' => '<div class="glass float2 ce-float" style="left: -8%; bottom: -14%">',
					'icon' => '<span class="icon" style="width: 32px; height: 32px; border-radius: 10px"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#0B1620" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path><path d="M8 9h8M8 13h4"></path></svg></span>',
				),
				array(
					'open' => '<div class="glass float ce-float" style="right: -7%; top: -7%">',
					'icon' => '<span class="icon-b" style="width: 32px; height: 32px; border-radius: 10px"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1A5A87" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18M9 16l2 2 4-4"></path></svg></span>',
				),
			),

			'badges'      => array(
				array(
					'_id'             => 'badge1',
					'hero_badge_title' => 'Positive reply',
					'hero_badge_sub'   => '"Sure, Tuesday works"',
				),
				array(
					'_id'             => 'badge2',
					'hero_badge_title' => 'Meeting booked',
					'hero_badge_sub'   => 'Synced to your CRM',
				),
			),

			'stat_number' => '200+',
			'stat_label'  => 'startups supported',
			'stat_desc'   => 'B2B teams from around the world trust us to build their pipeline.',

			// The design prints the unique logo set this many times; the duplicate
			// passes carry aria-hidden="true" and alt="" exactly as designed.
			'prints'      => 2,

			// Client logos (the design's five unique marquee chips).
			'logos'       => array(
				array(
					'_id'             => 'logo1',
					'hero_logo_image' => array(
						'url' => 'assets/logoc1.png',
						'id'  => '',
					),
					'hero_logo_alt'   => 'Createful Spaces',
				),
				array(
					'_id'             => 'logo2',
					'hero_logo_image' => array(
						'url' => 'assets/logoc2.png',
						'id'  => '',
					),
					'hero_logo_alt'   => 'Aerodei',
				),
				array(
					'_id'             => 'logo3',
					'hero_logo_image' => array(
						'url' => 'assets/logoc3.png',
						'id'  => '',
					),
					'hero_logo_alt'   => 'Simply Downsized',
				),
				array(
					'_id'             => 'logo4',
					'hero_logo_image' => array(
						'url' => 'assets/logoc4.png',
						'id'  => '',
					),
					'hero_logo_alt'   => 'Earnworthy',
				),
				array(
					'_id'             => 'logo5',
					'hero_logo_image' => array(
						'url' => 'assets/logoc5.png',
						'id'  => '',
					),
					'hero_logo_alt'   => 'Relativity',
				),
			),

			'cert_text'   => 'Certified partner of',
			'cert_bold'   => 'Instantly, HeyReach, GetSales, lemlist & Apollo',

			// Partner badges (the design's five certification images).
			'partners'    => array(
				array(
					'_id'                => 'pbadge1',
					'hero_partner_image' => array(
						'url' => 'assets/badge-instantly.webp',
						'id'  => '',
					),
					'hero_partner_alt'   => 'Instantly certified partner',
				),
				array(
					'_id'                => 'pbadge2',
					'hero_partner_image' => array(
						'url' => 'assets/badge-getsales.webp',
						'id'  => '',
					),
					'hero_partner_alt'   => 'GetSales certified partner',
				),
				array(
					'_id'                => 'pbadge3',
					'hero_partner_image' => array(
						'url' => 'assets/badge-heyreach.webp',
						'id'  => '',
					),
					'hero_partner_alt'   => 'HeyReach certified partner',
				),
				array(
					'_id'                => 'pbadge4',
					'hero_partner_image' => array(
						'url' => 'assets/badge-lemlist.webp',
						'id'  => '',
					),
					'hero_partner_alt'   => 'lemlist certified partner',
				),
				array(
					'_id'                => 'pbadge5',
					'hero_partner_image' => array(
						'url' => 'assets/badge-apollo.webp',
						'id'  => '',
					),
					'hero_partner_alt'   => 'Apollo.io certified partner',
				),
			),
		);
	}
}
