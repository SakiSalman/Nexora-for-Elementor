<?php
/**
 * Default content for LI Signal — design copy from the LinkedIn export LI4.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_li_signal_defaults' ) ) {
	/**
	 * The design defaults.
	 *
	 * @return array<string, mixed>
	 */
	function nexora_ph_li_signal_defaults(): array {
		return array(
			'heading_tag'       => 'h2',
			'eyebrow'           => 'Signal-Based Outreach',
			'heading'           => 'We reach out when prospects',
			'highlight'         => 'show a real signal',
			'groups_rows'       => array(
				array(
					'_id'            => 'li_sg_social',
					'signal_group'   => 'social',
					'signal_column'  => 'left',
					'signal_title'   => 'Social signals',
					'signal_subtitle'=> 'What your buyers engage with',
					'signal_bullets' => "Engagement with posts on your topics\nEngagement with your founder’s posts\nProspects posting about a topic",
				),
				array(
					'_id'            => 'li_sg_intent',
					'signal_group'   => 'intent',
					'signal_column'  => 'left',
					'signal_title'   => 'Intent signals',
					'signal_subtitle'=> 'Who is already looking at you',
					'signal_bullets' => "Profile views\nCompany page visits",
				),
				array(
					'_id'            => 'li_sg_company',
					'signal_group'   => 'company',
					'signal_column'  => 'right',
					'signal_title'   => 'Company signals',
					'signal_subtitle'=> 'What is changing at their company',
					'signal_bullets' => "Hiring for key roles\nJob switch or new role\nFunding, news and expansion",
				),
			),
			'core_image'        => array(
				'url' => 'assets/icon.png',
				'id'  => '',
			),
			'core_alt'          => 'Prospects Hive',
			'core_label'        => 'Your ICP',
			'nodes_rows'        => array(
				array(
					'_id'          => 'li_nd_0',
					'node_group'   => 'social',
					'node_label'   => 'Post engagement',
					'node_icon'    => '5f8e77c078',
					'node_angle'   => '0',
					'node_delay'   => '0',
				),
				array(
					'_id'          => 'li_nd_1',
					'node_group'   => 'social',
					'node_label'   => 'Founder post',
					'node_icon'    => 'e655d91b96',
					'node_angle'   => '45',
					'node_delay'   => '1',
				),
				array(
					'_id'          => 'li_nd_2',
					'node_group'   => 'social',
					'node_label'   => 'Topic post',
					'node_icon'    => 'a071a2a0ca',
					'node_angle'   => '90',
					'node_delay'   => '2',
				),
				array(
					'_id'          => 'li_nd_3',
					'node_group'   => 'intent',
					'node_label'   => 'Profile view',
					'node_icon'    => '92c388bb23',
					'node_angle'   => '135',
					'node_delay'   => '3',
				),
				array(
					'_id'          => 'li_nd_4',
					'node_group'   => 'intent',
					'node_label'   => 'Page visit',
					'node_icon'    => 'bcac496763',
					'node_angle'   => '180',
					'node_delay'   => '4',
				),
				array(
					'_id'          => 'li_nd_5',
					'node_group'   => 'company',
					'node_label'   => 'Job switch',
					'node_icon'    => '019f941764',
					'node_angle'   => '225',
					'node_delay'   => '5',
				),
				array(
					'_id'          => 'li_nd_6',
					'node_group'   => 'company',
					'node_label'   => 'Hiring',
					'node_icon'    => '065c801ad3',
					'node_angle'   => '270',
					'node_delay'   => '6',
				),
				array(
					'_id'          => 'li_nd_7',
					'node_group'   => 'company',
					'node_label'   => 'Funding',
					'node_icon'    => '54a57182a0',
					'node_angle'   => '315',
					'node_delay'   => '7',
				),
			),
			'outcome_icon'      => '2dba82e8b3',
			'outcome_title'     => 'Then we reach out',
			'outcome_body'      => 'Each note references the exact signal, so it reads like a real conversation, not a pitch.',
		);
	}
}
