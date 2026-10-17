<?php
/**
 * Default content for CE Why — the design copy from the Cold Email export.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_why_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_why_defaults(): array {
		return array(
			'heading_tag' => 'h2',
			'eyebrow'     => 'Why Choose Us',
			'heading'     => 'Better cold email starts with',
			'highlight'   => 'better campaign control',
			'row_tag'     => 'h3',
			'rows'        => array(
				array(
					'why_icon'        => '1ab43d91f1',
					'why_title'       => 'High-fit prospect lists',
					'why_tag'         => 'h3',
					'why_description' => 'We build lead lists around ICP criteria, buyer roles, firmographic data, and buying signals, so your team avoids contacts with little chance of becoming customers.',
				),
				array(
					'why_icon'        => '9e176b6b43',
					'why_title'       => 'Context-led personalization',
					'why_tag'         => 'h3',
					'why_description' => 'Each personalized cold email uses real details about the prospect, company, or trigger event. That gives the message a clear reason to exist and a stronger reason to reply.',
				),
				array(
					'why_icon'        => 'aea904a085',
					'why_title'       => 'Tools for every workflow',
					'why_tag'         => 'h3',
					'why_description' => 'Clay, Apollo, Instantly, Lemlist, Attio, and Make.com support research, enrichment, sending, routing, and automation. We use each tool for a defined job in the campaign.',
				),
				array(
					'why_icon'        => '893b566282',
					'why_title'       => 'Safer sending setup',
					'why_tag'         => 'h3',
					'why_description' => 'Domains, email accounts, authentication, email warm-up, and email verification are prepared before launch to reduce bounces, protect sender reputation, and support inbox reach.',
				),
				array(
					'why_icon'        => '549f8e1641',
					'why_title'       => 'Email + LinkedIn follow-up',
					'why_tag'         => 'h3',
					'why_description' => 'Prospects can be followed across cold email and LinkedIn lead generation when one channel is not enough. This gives sales more than one chance to start a conversation.',
				),
				array(
					'why_icon'        => '5408056c57',
					'why_title'       => 'CRM-ready lead handoff',
					'why_tag'         => 'h3',
					'why_description' => 'Qualified replies, follow-up dates, lead status, and meeting activity are recorded in your CRM, so sales knows who replied, what happened, and what action comes next.',
				),
			),
		);
	}
}
