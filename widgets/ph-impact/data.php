<?php
/**
 * Impact tab data. Defaults match the current section. Empty saved values stay empty.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_impact_default_image_url' ) ) {
	/**
	 * Packaged default image URL for MEDIA controls.
	 */
	function nexora_ph_impact_default_image_url(): string {
		return NEXORA_ELE_URL . 'assets/images/prospects/impact.webp';
	}
}

if ( ! function_exists( 'nexora_ph_impact_default_tabs' ) ) {
	/**
	 * The five tabs the section opens with.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_impact_default_tabs(): array {
		$image = [
			'url' => nexora_ph_impact_default_image_url(),
			'id'  => '',
		];
		$alt   = 'Connected GTM targeting map';

		return [
			[
				'tab_label'  => 'Better Targeting',
				'tab_title'  => 'Find the accounts that matter',
				'tab_body'   => 'A strong GTM system starts with knowing who to target. We help B2B teams focus on high-fit accounts using ICP development, account research and buyer insights.',
				'tab_image'  => $image,
				'tab_alt'    => $alt,
				'tab_points' => "Clear ideal customer profiles and target segments\nPrioritised accounts based on fit and opportunity\nMore focused outreach toward relevant prospects\nBetter alignment between marketing and sales",
			],
			[
				'tab_label'  => 'Relevant Engagement',
				'tab_title'  => 'Reach buyers with messages that land',
				'tab_body'   => 'Outreach works when it feels timely and personal. We shape every touch around the buyer, their role and what is happening in their business.',
				'tab_image'  => $image,
				'tab_alt'    => $alt,
				'tab_points' => "Messaging shaped by role, pains and timing\nOutreach triggered by real buying signals\nEmail and LinkedIn working as one conversation\nMore replies from the right people",
			],
			[
				'tab_label'  => 'Scalable Execution',
				'tab_title'  => 'Grow outreach without growing headcount',
				'tab_body'   => 'Automation and repeatable plays let you do more without adding more people or more manual work.',
				'tab_image'  => $image,
				'tab_alt'    => $alt,
				'tab_points' => "Automated research, enrichment and follow-ups\nRepeatable plays your team can run\nSafe sending across domains and profiles\nMore campaigns without more busywork",
			],
			[
				'tab_label'  => 'Revenue Visibility',
				'tab_title'  => 'See exactly what drives pipeline',
				'tab_body'   => 'When outreach, CRM and reporting are connected, you always know what is working and where your next deals are coming from.',
				'tab_image'  => $image,
				'tab_alt'    => $alt,
				'tab_points' => "Every reply and meeting tracked in your CRM\nStage-by-stage reporting from outreach to revenue\nA clear view of which plays and segments win\nFaster decisions backed by real data",
			],
			[
				'tab_label'  => 'Predictable Growth',
				'tab_title'  => 'Build a pipeline you can plan around',
				'tab_body'   => 'A connected system turns outbound from a guessing game into a steady, measurable source of new business.',
				'tab_image'  => $image,
				'tab_alt'    => $alt,
				'tab_points' => "A steady flow of qualified meetings\nContinuous testing and improvement\nA documented system your team owns\nForecasts built on consistent inputs",
			],
		];
	}
}

if ( ! function_exists( 'nexora_ph_impact_lines' ) ) {
	/**
	 * Split a textarea into non-empty lines.
	 *
	 * @param mixed $text Saved text.
	 * @return array<int, string>
	 */
	function nexora_ph_impact_lines( $text ): array {
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

if ( ! function_exists( 'nexora_ph_impact_item_text' ) ) {
	/**
	 * Read one repeater field.
	 *
	 * @param mixed  $item Repeater row.
	 * @param string $key  Field id.
	 */
	function nexora_ph_impact_item_text( $item, $key ): string {
		if ( ! is_array( $item ) || ! array_key_exists( $key, $item ) || ! is_scalar( $item[ $key ] ) ) {
			return '';
		}
		return (string) $item[ $key ];
	}
}

if ( ! function_exists( 'nexora_ph_impact_image_url' ) ) {
	/**
	 * Resolve a MEDIA control to a public URL. Default stays as assets/impact.webp for rewrite.
	 *
	 * @param mixed $image MEDIA value.
	 */
	function nexora_ph_impact_image_url( $image ): string {
		$default = nexora_ph_impact_default_image_url();
		$url     = '';
		if ( is_array( $image ) && isset( $image['url'] ) ) {
			$url = (string) $image['url'];
		} elseif ( is_string( $image ) ) {
			$url = $image;
		}
		$url = trim( $url );
		if ( '' === $url || $url === $default ) {
			return 'assets/impact.webp';
		}
		return $url;
	}
}

if ( ! function_exists( 'nexora_ph_impact_tab_payload' ) ) {
	/**
	 * One repeater row ready for the template.
	 *
	 * @param mixed $item Repeater row.
	 * @return array<string, mixed>
	 */
	function nexora_ph_impact_tab_payload( $item ): array {
		return [
			'label'  => nexora_ph_impact_item_text( $item, 'tab_label' ),
			'title'  => nexora_ph_impact_item_text( $item, 'tab_title' ),
			'body'   => nexora_ph_impact_item_text( $item, 'tab_body' ),
			'image'  => nexora_ph_impact_image_url( is_array( $item ) ? ( $item['tab_image'] ?? null ) : null ),
			'alt'    => nexora_ph_impact_item_text( $item, 'tab_alt' ),
			'points' => nexora_ph_impact_lines( nexora_ph_impact_item_text( $item, 'tab_points' ) ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_impact_payload' ) ) {
	/**
	 * Tabs payload for the impact template.
	 *
	 * @param mixed $settings Elementor settings.
	 * @return array{tabs: array<int, array<string, mixed>>}
	 */
	function nexora_ph_impact_payload( $settings ): array {
		$tabs = ( is_array( $settings ) && array_key_exists( 'impact_tabs', $settings ) && is_array( $settings['impact_tabs'] ) && $settings['impact_tabs'] )
			? array_values( $settings['impact_tabs'] )
			: nexora_ph_impact_default_tabs();

		if ( ! $tabs ) {
			$tabs = nexora_ph_impact_default_tabs();
		}

		return [
			'tabs' => array_map( 'nexora_ph_impact_tab_payload', $tabs ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_impact_json' ) ) {
	/**
	 * JSON safe to place inside a script tag.
	 *
	 * @param mixed $payload Payload.
	 */
	function nexora_ph_impact_json( $payload ): string {
		$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
		$json  = function_exists( 'wp_json_encode' )
			? wp_json_encode( $payload, JSON_UNESCAPED_UNICODE )
			: json_encode( $payload, $flags );
		return is_string( $json ) ? $json : '{"tabs":[]}';
	}
}
