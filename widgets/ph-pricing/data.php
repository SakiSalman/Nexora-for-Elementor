<?php
/**
 * Pricing plan data. Defaults match the current section. Empty saved values stay empty.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_pricing_defaults' ) ) {
	/**
	 * Section copy used when a control has never been saved.
	 *
	 * @return array<string, string>
	 */
	function nexora_ph_pricing_defaults(): array {
		return [
			'eyebrow'            => 'Pricing',
			'heading'            => 'Plan & ',
			'heading_tag'        => 'h2',
			'highlight'          => 'pricing',
			'spots_note'         => "Only 5 spots left\nfor this month",
			'spots_label'        => 'Only 5 spots left for this month',
			'after_heading'      => 'What happens after 90 days?',
			'after_tag'          => 'h3',
			'after_description'  => 'The system is yours. Run it internally with your team, or keep Prospects Hive involved for ongoing optimisation, testing and execution support.',
			'after_chips'        => "Full system handover\nSOPs + GTM playbook\nTeam training\nOptional monthly support",
			'after_button_text'  => 'Ask About Ongoing Support',
			'after_button_url'   => '#contact',
		];
	}
}

if ( ! function_exists( 'nexora_ph_pricing_default_plans' ) ) {
	/**
	 * The three plans the section opens with.
	 *
	 * @return array<int, array<string, string>>
	 */
	function nexora_ph_pricing_default_plans(): array {
		return [
			[
				'plan_template'          => 'standard',
				'plan_name'              => 'GTM Foundation',
				'plan_badge'             => 'Core build',
				'plan_badge_style'       => 'outline',
				'plan_description'       => 'For teams that need the core outbound infrastructure built properly.',
				'plan_price'             => '$2,499',
				'plan_period'            => '/ month',
				'plan_term'              => '3-month GTM build',
				'plan_total'             => '$7,497 total over 3 months',
				'plan_upfront'           => '$6,999',
				'plan_savings'           => 'Save $498',
				'plan_fit'               => 'Best fit when you need',
				'plan_included'          => "One core ICP and priority segments\n1 to 2 focused outbound GTM plays\nClay data, enrichment and verification workflows\nCold email + LinkedIn activation\nCRM pipeline, routing and reporting\nMessaging, SOPs and team handover",
				'plan_day_title'         => 'At day 90',
				'plan_day_body'          => 'You own the targeting model, workflows, campaigns, CRM setup, dashboard, documentation and GTM playbook.',
				'plan_button_text'       => 'Build My GTM System',
				'plan_button_url'        => '#contact',
				'plan_build_heading'     => 'What we build in 90 days',
				'plan_build_description' => 'One production-ready outbound system, launched, tested, optimised and handed over.',
				'plan_phase'             => 'Strategy → Build → Launch → Handover',
				'plan_m1_label'          => 'Month 1',
				'plan_m1_title'          => 'Strategy + infrastructure',
				'plan_m1_tasks'          => "ICP + buying triggers\nOffer and messaging\nData architecture\nDomains, inboxes and tools\nCRM stages + tracking",
				'plan_m1_output'         => 'GTM blueprint + working infrastructure',
				'plan_m2_label'          => 'Month 2',
				'plan_m2_title'          => 'Launch + learn',
				'plan_m2_tasks'          => "Build prospect lists\nLaunch 1 to 2 GTM plays\nEmail + LinkedIn activation\nReply routing\nReview signals + objections",
				'plan_m2_output'         => 'Live GTM plays + real buyer feedback',
				'plan_m3_label'          => 'Month 3',
				'plan_m3_title'          => 'Optimise + handover',
				'plan_m3_tasks'          => "Refine targeting + copy\nImprove workflows\nDocument winning process\nBuild dashboards\nTrain team + handover",
				'plan_m3_output'         => 'Tested system + SOPs + team handover',
				'plan_outcomes_heading'  => 'The GTM system we leave behind',
				'plan_outcomes'          => "Market | ICP, segments, triggers\nData | Sourcing, enrichment, verification\nMessaging | Offers, angles, personalisation\nActivation | Email, LinkedIn, GTM plays\nCRM | Routing, stages, follow-up\nIntelligence | Reporting, testing, learnings",
				'plan_custom_badge'      => '',
				'plan_custom_heading'    => '',
				'plan_custom_heading_tag'=> 'h3',
				'plan_custom_description'=> '',
				'plan_custom_tags'       => '',
				'plan_custom_price'      => '',
				'plan_custom_note'       => '',
				'plan_custom_button_text'=> '',
				'plan_custom_button_url' => '',
			],
			[
				'plan_template'          => 'standard',
				'plan_name'              => 'GTM Growth Engine',
				'plan_badge'             => 'Most popular',
				'plan_badge_style'       => 'highlight',
				'plan_description'       => 'For teams ready to run multiple markets and channels as one connected revenue engine.',
				'plan_price'             => '$3,999',
				'plan_period'            => '/ month',
				'plan_term'              => '3-month GTM build',
				'plan_total'             => '$11,997 total over 3 months',
				'plan_upfront'           => '$10,999',
				'plan_savings'           => 'Save $998',
				'plan_fit'               => 'Everything in Foundation, plus',
				'plan_included'          => "Up to 3 ICPs, markets or offers\n3 to 5 GTM plays running in parallel\nSignal tracking: hiring, funding, tech and intent\nMultichannel: email, LinkedIn and ABM for top accounts\nAI research agents and reply qualification\nRevOps dashboards, attribution and weekly reviews",
				'plan_day_title'         => 'At day 90',
				'plan_day_body'          => 'You own a multi-play GTM engine with live signals, AI workflows, attribution and a trained team ready to scale it.',
				'plan_button_text'       => 'Build My Growth Engine',
				'plan_button_url'        => 'https://tidycal.com/prospectshive/discovery-call',
				'plan_build_heading'     => 'What we build in 90 days',
				'plan_build_description' => 'A multi-play revenue engine: more markets, more signals, more channels, one system.',
				'plan_phase'             => 'Strategy → Signals → Scale → Handover',
				'plan_m1_label'          => 'Month 1',
				'plan_m1_title'          => 'Strategy + signal system',
				'plan_m1_tasks'          => "ICPs, segments + account tiers\nOffers and messaging per market\nSignal tracking + data sources\nDomains, inboxes and LinkedIn\nCRM stages, routing + scoring",
				'plan_m1_output'         => 'Multi-market blueprint + signal-ready infrastructure',
				'plan_m2_label'          => 'Month 2',
				'plan_m2_title'          => 'Launch + scale plays',
				'plan_m2_tasks'          => "Launch 3 to 5 GTM plays\nEmail, LinkedIn + ABM sequences\nAI research + reply agents\nMeeting booking + handoff\nWeekly performance reviews",
				'plan_m2_output'         => 'Live multichannel plays + qualified meetings',
				'plan_m3_label'          => 'Month 3',
				'plan_m3_title'          => 'Optimise + handover',
				'plan_m3_tasks'          => "Double down on winning plays\nAttribution + RevOps dashboards\nAutomate reporting\nDocument playbooks + SOPs\nTrain team + handover",
				'plan_m3_output'         => 'Proven engine + dashboards + team handover',
				'plan_outcomes_heading'  => 'What Growth adds on top of Foundation',
				'plan_outcomes'          => "Multi-market | Up to 3 ICPs or offers\nSignals | Hiring, funding, tech, intent\nABM | Top-account plays\nAI agents | Research + reply qualification\nAttribution | Pipeline by play and channel\nWeekly reviews | Test, learn, reallocate",
				'plan_custom_badge'      => '',
				'plan_custom_heading'    => '',
				'plan_custom_heading_tag'=> 'h3',
				'plan_custom_description'=> '',
				'plan_custom_tags'       => '',
				'plan_custom_price'      => '',
				'plan_custom_note'       => '',
				'plan_custom_button_text'=> '',
				'plan_custom_button_url' => '',
			],
			[
				'plan_template'          => 'custom',
				'plan_name'              => 'Custom GTM',
				'plan_badge'             => '',
				'plan_badge_style'       => 'outline',
				'plan_description'       => '',
				'plan_price'             => '',
				'plan_period'            => '/ month',
				'plan_term'              => '',
				'plan_total'             => '',
				'plan_upfront'           => '',
				'plan_savings'           => '',
				'plan_fit'               => '',
				'plan_included'          => '',
				'plan_day_title'         => 'At day 90',
				'plan_day_body'          => '',
				'plan_button_text'       => '',
				'plan_button_url'        => '',
				'plan_build_heading'     => 'What we build in 90 days',
				'plan_build_description' => '',
				'plan_phase'             => '',
				'plan_m1_label'          => 'Month 1',
				'plan_m1_title'          => '',
				'plan_m1_tasks'          => '',
				'plan_m1_output'         => '',
				'plan_m2_label'          => 'Month 2',
				'plan_m2_title'          => '',
				'plan_m2_tasks'          => '',
				'plan_m2_output'         => '',
				'plan_m3_label'          => 'Month 3',
				'plan_m3_title'          => '',
				'plan_m3_tasks'          => '',
				'plan_m3_output'         => '',
				'plan_outcomes_heading'  => '',
				'plan_outcomes'          => '',
				'plan_custom_badge'      => 'Custom GTM',
				'plan_custom_heading'    => 'Need something built around your business?',
				'plan_custom_heading_tag'=> 'h3',
				'plan_custom_description'=> 'Every company is different. Tell us about your markets, team and goals, and we will shape a GTM system and price around what you actually need.',
				'plan_custom_tags'       => "Multiple markets or regions\nEnterprise ABM\nCRM rebuild or migration\nCustom AI workflows\nOngoing managed outbound",
				'plan_custom_price'      => 'Custom',
				'plan_custom_note'       => 'Scoped on a free discovery call',
				'plan_custom_button_text'=> 'Discuss My Project',
				'plan_custom_button_url' => 'https://tidycal.com/prospectshive/discovery-call',
			],
		];
	}
}

if ( ! function_exists( 'nexora_ph_pricing_lines' ) ) {
	/**
	 * Split a textarea into non-empty lines.
	 *
	 * @param mixed $text Saved text.
	 * @return array<int, string>
	 */
	function nexora_ph_pricing_lines( $text ): array {
		$text = str_replace( [ "\r\n", "\r" ], "\n", (string) $text );
		$out  = [];
		foreach ( explode( "\n", $text ) as $line ) {
			$line = trim( $line );
			if ( '' === $line ) {
				continue;
			}
			$out[] = $line;
		}
		return $out;
	}
}

if ( ! function_exists( 'nexora_ph_pricing_tag' ) ) {
	/**
	 * Keep a heading tag inside the allowed set.
	 *
	 * @param mixed  $tag      Saved tag.
	 * @param string $fallback Original tag.
	 */
	function nexora_ph_pricing_tag( $tag, $fallback ): string {
		$tag      = strtolower( trim( (string) $tag ) );
		$fallback = strtolower( trim( (string) $fallback ) );
		$allowed  = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'div', 'span', 'p' ];
		if ( ! in_array( $fallback, $allowed, true ) ) {
			$fallback = 'h2';
		}
		return in_array( $tag, $allowed, true ) ? $tag : $fallback;
	}
}

if ( ! function_exists( 'nexora_ph_pricing_setting' ) ) {
	/**
	 * Read one saved string. A missing key uses the default. An empty string stays empty.
	 *
	 * @param mixed  $settings Settings array.
	 * @param string $key      Setting id.
	 * @param string $default  Unsaved value.
	 */
	function nexora_ph_pricing_setting( $settings, $key, $default ): string {
		if ( ! is_array( $settings ) || ! array_key_exists( $key, $settings ) ) {
			return $default;
		}
		$value = $settings[ $key ];
		if ( is_array( $value ) ) {
			$value = $value['url'] ?? '';
		}
		if ( ! is_scalar( $value ) ) {
			return $default;
		}
		return (string) $value;
	}
}

if ( ! function_exists( 'nexora_ph_pricing_url' ) ) {
	/**
	 * Keep a link, including in-page hashes.
	 *
	 * @param mixed $url Saved link.
	 */
	function nexora_ph_pricing_url( $url ): string {
		if ( is_array( $url ) ) {
			$url = $url['url'] ?? '';
		}
		$url = trim( (string) $url );
		if ( '' === $url ) {
			return '';
		}
		if ( function_exists( 'esc_url_raw' ) ) {
			return (string) esc_url_raw( $url );
		}
		if ( '#' === $url[0] || preg_match( '#^https?://#i', $url ) || preg_match( '#^(mailto|tel):#i', $url ) ) {
			return $url;
		}
		return '';
	}
}

if ( ! function_exists( 'nexora_ph_pricing_item_text' ) ) {
	/**
	 * Read one repeater field.
	 *
	 * @param mixed  $item Repeater row.
	 * @param string $key  Field id.
	 */
	function nexora_ph_pricing_item_text( $item, $key ): string {
		if ( ! is_array( $item ) || ! array_key_exists( $key, $item ) || ! is_scalar( $item[ $key ] ) ) {
			return '';
		}
		return (string) $item[ $key ];
	}
}

if ( ! function_exists( 'nexora_ph_pricing_outcomes' ) ) {
	/**
	 * Split "Title | text" lines. A line without a bar is a title only.
	 *
	 * @param mixed $text Saved text.
	 * @return array<int, array<string, string>>
	 */
	function nexora_ph_pricing_outcomes( $text ): array {
		$warm = 'border-radius: 14px; padding: 13px 15px; border: 1px solid var(--ph-pricing-outcome-warm-border, #F6D7C7); background: var(--ph-pricing-outcome-warm-bg, #FFFAF7)';
		$cool = 'border-radius: 14px; padding: 13px 15px; border: 1px solid var(--ph-pricing-outcome-cool-border, #D6E7F4); background: var(--ph-pricing-outcome-cool-bg, #F7FBFE)';
		$rows = [];
		foreach ( nexora_ph_pricing_lines( $text ) as $index => $line ) {
			$parts = explode( '|', $line, 2 );
			$rows[] = [
				'title' => trim( $parts[0] ),
				'text'  => isset( $parts[1] ) ? trim( $parts[1] ) : '',
				'style' => ( 0 === (int) floor( $index / 3 ) % 2 ) ? $warm : $cool,
			];
		}
		return $rows;
	}
}

if ( ! function_exists( 'nexora_ph_pricing_months' ) ) {
	/**
	 * Always three month cards. Color follows the card position.
	 *
	 * @param array<string, mixed> $item Repeater row.
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_pricing_months( $item ): array {
		$card_style = 'border: 1px solid var(--ph-card-border, #EFE7DE); border-radius: 18px; padding: 22px; display: flex; flex-direction: column; gap: 10px';
		$cards      = [
			[
				'class' => 'pr-month pr-month-1',
				'style' => $card_style,
			],
			[
				'class' => 'pr-month pr-month-2',
				'style' => $card_style,
			],
			[
				'class' => 'pr-month pr-month-3',
				'style' => $card_style,
			],
		];
		$labels = [
			"font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600; color: var(--ph-link, #C2410C)",
			"font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600; color: var(--ph-link-blue, #1A5A87)",
			"font-family: 'JetBrains Mono', monospace; font-size: 12px; font-weight: 600",
		];
		$months = [];
		foreach ( [ 1, 2, 3 ] as $number ) {
			$index    = $number - 1;
			$months[] = [
				'label'      => nexora_ph_pricing_item_text( $item, 'plan_m' . $number . '_label' ),
				'title'      => nexora_ph_pricing_item_text( $item, 'plan_m' . $number . '_title' ),
				'tasks'      => nexora_ph_pricing_lines( nexora_ph_pricing_item_text( $item, 'plan_m' . $number . '_tasks' ) ),
				'output'     => nexora_ph_pricing_item_text( $item, 'plan_m' . $number . '_output' ),
				'cardClass'  => $cards[ $index ]['class'],
				'cardStyle'  => $cards[ $index ]['style'],
				'labelStyle' => $labels[ $index ],
			];
		}
		return $months;
	}
}

if ( ! function_exists( 'nexora_ph_pricing_link_attrs' ) ) {
	/**
	 * Open http(s) links in a new tab.
	 *
	 * @param string $url Link.
	 * @return array{url: string, target: string|false, rel: string|false}
	 */
	function nexora_ph_pricing_link_attrs( $url ): array {
		$url      = nexora_ph_pricing_url( $url );
		$external = (bool) preg_match( '#^https?://#i', $url );
		return [
			'url'    => $url,
			'target' => $external ? '_blank' : false,
			'rel'    => $external ? 'noopener' : false,
		];
	}
}

if ( ! function_exists( 'nexora_ph_pricing_plan_payload' ) ) {
	/**
	 * One repeater row ready for the template.
	 *
	 * @param mixed $item Repeater row.
	 * @return array<string, mixed>
	 */
	function nexora_ph_pricing_plan_payload( $item ): array {
		$template = ( is_array( $item ) && isset( $item['plan_template'] ) && 'custom' === $item['plan_template'] ) ? 'custom' : 'standard';
		$button   = nexora_ph_pricing_link_attrs( is_array( $item ) ? ( $item['plan_button_url'] ?? '' ) : '' );
		$custom   = nexora_ph_pricing_link_attrs( is_array( $item ) ? ( $item['plan_custom_button_url'] ?? '' ) : '' );
		$upfront  = nexora_ph_pricing_item_text( $item, 'plan_upfront' );
		$savings  = nexora_ph_pricing_item_text( $item, 'plan_savings' );
		$style    = nexora_ph_pricing_item_text( $item, 'plan_badge_style' );
		$is_highlight = 'highlight' === $style;

		if ( 'custom' === $template ) {
			$button = $custom;
		}

		return [
			'name'               => nexora_ph_pricing_item_text( $item, 'plan_name' ),
			'template'           => $template,
			'isStandard'         => 'standard' === $template,
			'isCustom'           => 'custom' === $template,
			'badge'              => nexora_ph_pricing_item_text( $item, 'plan_badge' ),
			'badgeClass'         => $is_highlight ? 'chip o pr-badge-highlight' : 'chip o',
			'badgeStyle'         => $is_highlight ? 'white-space: nowrap' : false,
			'description'        => nexora_ph_pricing_item_text( $item, 'plan_description' ),
			'price'              => nexora_ph_pricing_item_text( $item, 'plan_price' ),
			'period'             => nexora_ph_pricing_item_text( $item, 'plan_period' ),
			'term'               => nexora_ph_pricing_item_text( $item, 'plan_term' ),
			'total'              => nexora_ph_pricing_item_text( $item, 'plan_total' ),
			'upfront'            => $upfront,
			'savings'            => $savings,
			'showUpfront'        => '' !== $upfront || '' !== $savings,
			'fit'                => nexora_ph_pricing_item_text( $item, 'plan_fit' ),
			'included'           => nexora_ph_pricing_lines( nexora_ph_pricing_item_text( $item, 'plan_included' ) ),
			'dayTitle'           => nexora_ph_pricing_item_text( $item, 'plan_day_title' ),
			'dayBody'            => nexora_ph_pricing_item_text( $item, 'plan_day_body' ),
			'buttonText'         => 'custom' === $template ? nexora_ph_pricing_item_text( $item, 'plan_custom_button_text' ) : nexora_ph_pricing_item_text( $item, 'plan_button_text' ),
			'buttonUrl'          => $button['url'],
			'buttonTarget'       => $button['target'],
			'buttonRel'          => $button['rel'],
			'buildHeading'       => nexora_ph_pricing_item_text( $item, 'plan_build_heading' ),
			'buildDescription'   => nexora_ph_pricing_item_text( $item, 'plan_build_description' ),
			'phase'              => nexora_ph_pricing_item_text( $item, 'plan_phase' ),
			'months'             => nexora_ph_pricing_months( is_array( $item ) ? $item : [] ),
			'outcomesHeading'    => nexora_ph_pricing_item_text( $item, 'plan_outcomes_heading' ),
			'outcomes'           => nexora_ph_pricing_outcomes( nexora_ph_pricing_item_text( $item, 'plan_outcomes' ) ),
			'customBadge'        => nexora_ph_pricing_item_text( $item, 'plan_custom_badge' ),
			'customHeading'      => nexora_ph_pricing_item_text( $item, 'plan_custom_heading' ),
			'customHeadingTag'   => nexora_ph_pricing_tag( nexora_ph_pricing_item_text( $item, 'plan_custom_heading_tag' ), 'h3' ),
			'customDescription'  => nexora_ph_pricing_item_text( $item, 'plan_custom_description' ),
			'tags'               => nexora_ph_pricing_lines( nexora_ph_pricing_item_text( $item, 'plan_custom_tags' ) ),
			'priceWord'          => nexora_ph_pricing_item_text( $item, 'plan_custom_price' ),
			'note'               => nexora_ph_pricing_item_text( $item, 'plan_custom_note' ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_pricing_payload' ) ) {
	/**
	 * Header, plans, and the after-90 block for the pricing template.
	 *
	 * @param mixed $settings Elementor settings.
	 * @return array<string, mixed>
	 */
	function nexora_ph_pricing_payload( $settings ): array {
		$defaults = nexora_ph_pricing_defaults();
		$note     = nexora_ph_pricing_lines( nexora_ph_pricing_setting( $settings, 'spots_note', $defaults['spots_note'] ) );
		$line1    = $note[0] ?? '';
		$line2    = count( $note ) > 1 ? implode( ' ', array_slice( $note, 1 ) ) : '';
		$plans    = ( is_array( $settings ) && array_key_exists( 'plans', $settings ) && is_array( $settings['plans'] ) )
			? $settings['plans']
			: nexora_ph_pricing_default_plans();
		$after    = nexora_ph_pricing_link_attrs( nexora_ph_pricing_setting( $settings, 'after_button_url', $defaults['after_button_url'] ) );

		return [
			'eyebrow'          => nexora_ph_pricing_setting( $settings, 'eyebrow', $defaults['eyebrow'] ),
			'headingBefore'    => nexora_ph_pricing_setting( $settings, 'heading', $defaults['heading'] ),
			'headingTag'       => nexora_ph_pricing_tag( nexora_ph_pricing_setting( $settings, 'heading_tag', $defaults['heading_tag'] ), 'h2' ),
			'highlight'        => nexora_ph_pricing_setting( $settings, 'highlight', $defaults['highlight'] ),
			'spotsLine1'       => $line1,
			'spotsLine2'       => $line2,
			'spotsLabel'       => nexora_ph_pricing_setting( $settings, 'spots_label', $defaults['spots_label'] ),
			'plans'            => array_map( 'nexora_ph_pricing_plan_payload', array_values( $plans ) ),
			'afterHeading'     => nexora_ph_pricing_setting( $settings, 'after_heading', $defaults['after_heading'] ),
			'afterTag'         => nexora_ph_pricing_tag( nexora_ph_pricing_setting( $settings, 'after_tag', $defaults['after_tag'] ), 'h3' ),
			'afterDescription' => nexora_ph_pricing_setting( $settings, 'after_description', $defaults['after_description'] ),
			'afterChips'       => nexora_ph_pricing_lines( nexora_ph_pricing_setting( $settings, 'after_chips', $defaults['after_chips'] ) ),
			'afterButtonText'  => nexora_ph_pricing_setting( $settings, 'after_button_text', $defaults['after_button_text'] ),
			'afterButtonUrl'   => $after['url'],
			'afterButtonTarget'=> $after['target'],
			'afterButtonRel'   => $after['rel'],
		];
	}
}

if ( ! function_exists( 'nexora_ph_pricing_json' ) ) {
	/**
	 * JSON safe to place inside a script tag.
	 *
	 * @param mixed $payload Payload.
	 */
	function nexora_ph_pricing_json( $payload ): string {
		$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
		$json  = function_exists( 'wp_json_encode' )
			? wp_json_encode( $payload, JSON_UNESCAPED_UNICODE )
			: json_encode( $payload, $flags );
		return is_string( $json ) ? $json : '{}';
	}
}
