<?php
/**
 * Default content for CE Services — the design copy from the Cold Email export.
 *
 * Defaults live in a function (not file-scope variables) so require_once stays
 * safe: every render and the controls get the same array without scope traps.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_services_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_ce_services_defaults(): array {
		return array(
			'eyebrow'     => 'Services',
			'heading'     => 'Cold email marketing services built',
			'highlight'   => 'for B2B pipeline',
			'heading_tag' => 'h2',
			'description' => 'Prospects Hive combines lead research, email copy, deliverability, cold email sequences, reply handling, and CRM tracking to help B2B companies create qualified sales opportunities.',
			'cards'       => array(
				array(
					'_id'                   => 'svc1',
					'services_card_layout'  => 'wide',
					'services_icon'         => 'de0ce30a25',
					'services_icon_tint'    => 'dark',
					'services_badge'        => 'Start here',
					'services_title'        => 'Lead Research & Prospect Targeting',
					'services_title_tag'    => 'h3',
					'services_description'  => 'We research accounts and contacts that match your ICP, verify every email, and group prospects into clear segments, so each campaign reaches buyers with a real reason to reply.',
					'services_chips'        => "ICP & account research\nVerified contacts\nSegmented lists",
					'services_checklist'    => '',
				),
				array(
					'_id'                   => 'svc2',
					'services_card_layout'  => 'standard',
					'services_icon'         => '33167c9565',
					'services_icon_tint'    => 'orange',
					'services_badge'        => '',
					'services_title'        => 'Cold Email Copy & Personalization',
					'services_title_tag'    => 'h3',
					'services_description'  => 'Copy written around each prospect’s role, company, and context, so every email has a clear reason to exist.',
					'services_chips'        => '',
					'services_checklist'    => '',
				),
				array(
					'_id'                   => 'svc3',
					'services_card_layout'  => 'standard',
					'services_icon'         => '092b71c242',
					'services_icon_tint'    => 'blue',
					'services_badge'        => '',
					'services_title'        => 'Email Deliverability Setup',
					'services_title_tag'    => 'h3',
					'services_description'  => 'Domains, mailboxes, authentication, and warm-up set up before launch to protect your sender reputation.',
					'services_chips'        => '',
					'services_checklist'    => '',
				),
				array(
					'_id'                   => 'svc4',
					'services_card_layout'  => 'checklist',
					'services_icon'         => '7190abeb62',
					'services_icon_tint'    => 'orange',
					'services_badge'        => '',
					'services_title'        => 'Cold Email Sequences & Automation',
					'services_title_tag'    => 'h3',
					'services_description'  => 'Structured cold email sequences control when each follow-up is sent and what it says. Email automation keeps outreach running without manual sending.',
					'services_chips'        => '',
					'services_checklist'    => "Cold email campaign setup\nMulti-touch follow-up sequences\nEmail automation workflows\nSafe sending schedules",
				),
				array(
					'_id'                   => 'svc5',
					'services_card_layout'  => 'standard',
					'services_icon'         => 'eec0a62124',
					'services_icon_tint'    => 'orange',
					'services_badge'        => '',
					'services_title'        => 'Reply Qualification & Meeting Handoff',
					'services_title_tag'    => 'h3',
					'services_description'  => 'Every reply is reviewed, sorted, and followed up fast, so qualified prospects land on your sales calendar.',
					'services_chips'        => '',
					'services_checklist'    => '',
				),
				array(
					'_id'                   => 'svc6',
					'services_card_layout'  => 'standard',
					'services_icon'         => 'e322230590',
					'services_icon_tint'    => 'blue',
					'services_badge'        => '',
					'services_title'        => 'CRM & Pipeline Tracking',
					'services_title_tag'    => 'h3',
					'services_description'  => 'Replies, meetings, and deal activity sync to your CRM, so you can see what cold email adds to pipeline.',
					'services_chips'        => '',
					'services_checklist'    => '',
				),
			),
		);
	}
}
