<?php
/**
 * Case studies slide data. Defaults match the current section. Empty saved values stay empty.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_cases_default_image_url' ) ) {
	/**
	 * Packaged default image URL for a case asset.
	 *
	 * @param string $file File name under assets/images/prospects/.
	 */
	function nexora_ph_cases_default_image_url( $file ): string {
		return NEXORA_ELE_URL . 'assets/images/prospects/' . ltrim( (string) $file, '/' );
	}
}

if ( ! function_exists( 'nexora_ph_cases_default_slides' ) ) {
	/**
	 * The three slides the section opens with.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_cases_default_slides(): array {
		$button = 'View full case study';
		$link   = '#';

		return [
			[
				'case_title'       => 'Building a dual-track deal pipeline for a boutique M&A firm',
				'case_summary'     => 'We built a fully managed LinkedIn outbound system that engaged 18,260 prospects across two tracks at once: deal flow and capital partners.',
				'case_client'      => 'Boutique investment management firm',
				'case_industry'    => 'M&A advisory (lower middle market)',
				'case_services'    => 'LinkedIn outreach & CRM optimisation',
				'case_image'       => [
					'url' => nexora_ph_cases_default_image_url( 'case1.webp' ),
					'id'  => '',
				],
				'case_alt'         => 'Dual-track deal pipeline diagram',
				'case_button_text' => $button,
				'case_button_url'  => $link,
				'case_stats'       => "18,260 | Prospects engaged\n45 | Meetings booked\n19.6% | Reply rate",
			],
			[
				'case_title'       => 'Eliminating manual verification calls for an insurance factoring firm',
				'case_summary'     => 'A fully automated AI voice system now handles every pre-funding verification call and updates the CRM in real time, with zero staff hours needed.',
				'case_client'      => 'National Claims Funding',
				'case_industry'    => 'Insurance receivables factoring',
				'case_services'    => 'AI voice automation & CRM integration',
				'case_image'       => [
					'url' => nexora_ph_cases_default_image_url( 'case2.webp' ),
					'id'  => '',
				],
				'case_alt'         => 'AI voice verification workflow diagram',
				'case_button_text' => $button,
				'case_button_url'  => $link,
				'case_stats'       => "100% | Calls automated\n0 min | Staff time per call\n24/7 | Call capacity",
			],
			[
				'case_title'       => 'Booking 25 qualified meetings to support a $1M investment round',
				'case_summary'     => 'A conversation-first outbound system that turned high-intent replies into qualified investor meetings, inside a fixed fundraising window.',
				'case_client'      => 'High-growth startup seeking investment',
				'case_industry'    => '[Industry]',
				'case_services'    => 'Signal-based outbound & reply qualification',
				'case_image'       => [
					'url' => nexora_ph_cases_default_image_url( 'case3.webp' ),
					'id'  => '',
				],
				'case_alt'         => 'Investor meeting pipeline diagram',
				'case_button_text' => $button,
				'case_button_url'  => $link,
				'case_stats'       => "25 | Qualified meetings\n\$1M | Round secured\n43.2% | Avg. open rate",
			],
		];
	}
}

if ( ! function_exists( 'nexora_ph_cases_lines' ) ) {
	/**
	 * Split a textarea into non-empty lines.
	 *
	 * @param mixed $text Saved text.
	 * @return array<int, string>
	 */
	function nexora_ph_cases_lines( $text ): array {
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

if ( ! function_exists( 'nexora_ph_cases_item_text' ) ) {
	/**
	 * Read one repeater field.
	 *
	 * @param mixed  $item Repeater row.
	 * @param string $key  Field id.
	 */
	function nexora_ph_cases_item_text( $item, $key ): string {
		if ( ! is_array( $item ) || ! array_key_exists( $key, $item ) || ! is_scalar( $item[ $key ] ) ) {
			return '';
		}
		return (string) $item[ $key ];
	}
}

if ( ! function_exists( 'nexora_ph_cases_url' ) ) {
	/**
	 * Keep a link, including in-page hashes.
	 *
	 * @param mixed $url Saved link.
	 */
	function nexora_ph_cases_url( $url ): string {
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

if ( ! function_exists( 'nexora_ph_cases_link_attrs' ) ) {
	/**
	 * Open http(s) links in a new tab.
	 *
	 * @param string $url Link.
	 * @return array{url: string, target: string|false, rel: string|false}
	 */
	function nexora_ph_cases_link_attrs( $url ): array {
		$url      = nexora_ph_cases_url( $url );
		$external = (bool) preg_match( '#^https?://#i', $url );
		return [
			'url'    => $url,
			'target' => $external ? '_blank' : false,
			'rel'    => $external ? 'noopener' : false,
		];
	}
}

if ( ! function_exists( 'nexora_ph_cases_image' ) ) {
	/**
	 * Resolve MEDIA to a public path. Defaults stay as assets/caseN.webp for rewrite.
	 *
	 * @param mixed  $image   MEDIA value.
	 * @param string $default Default file name (e.g. case1.webp) used only when matching packaged URL.
	 * @return array{url: string, hasImg: bool}
	 */
	function nexora_ph_cases_image( $image, $default = 'case1.webp' ): array {
		$default_file = ltrim( (string) $default, '/' );
		$default_url  = nexora_ph_cases_default_image_url( $default_file );
		$url          = '';
		if ( is_array( $image ) && isset( $image['url'] ) ) {
			$url = (string) $image['url'];
		} elseif ( is_string( $image ) ) {
			$url = $image;
		}
		$url = trim( $url );
		if ( '' === $url ) {
			return [
				'url'    => '',
				'hasImg' => false,
			];
		}
		if ( $url === $default_url || $url === 'assets/' . $default_file ) {
			return [
				'url'    => 'assets/' . $default_file,
				'hasImg' => true,
			];
		}
		// Match any packaged case default URL shape.
		foreach ( [ 'case1.webp', 'case2.webp', 'case3.webp' ] as $file ) {
			if ( $url === nexora_ph_cases_default_image_url( $file ) || $url === 'assets/' . $file ) {
				return [
					'url'    => 'assets/' . $file,
					'hasImg' => true,
				];
			}
		}
		return [
			'url'    => $url,
			'hasImg' => true,
		];
	}
}

if ( ! function_exists( 'nexora_ph_cases_stats' ) ) {
	/**
	 * Split "value | label" lines.
	 *
	 * @param mixed $text Saved text.
	 * @return array<int, array{v: string, l: string}>
	 */
	function nexora_ph_cases_stats( $text ): array {
		$rows = [];
		foreach ( nexora_ph_cases_lines( $text ) as $line ) {
			$parts = explode( '|', $line, 2 );
			$rows[] = [
				'v' => trim( $parts[0] ),
				'l' => isset( $parts[1] ) ? trim( $parts[1] ) : '',
			];
		}
		return $rows;
	}
}

if ( ! function_exists( 'nexora_ph_cases_slide_payload' ) ) {
	/**
	 * One repeater row ready for the template.
	 *
	 * @param mixed $item Repeater row.
	 * @return array<string, mixed>
	 */
	function nexora_ph_cases_slide_payload( $item ): array {
		$image = nexora_ph_cases_image( is_array( $item ) ? ( $item['case_image'] ?? null ) : null );
		$link  = nexora_ph_cases_link_attrs( is_array( $item ) ? ( $item['case_button_url'] ?? '' ) : '' );

		return [
			'title'        => nexora_ph_cases_item_text( $item, 'case_title' ),
			'summary'      => nexora_ph_cases_item_text( $item, 'case_summary' ),
			'client'       => nexora_ph_cases_item_text( $item, 'case_client' ),
			'industry'     => nexora_ph_cases_item_text( $item, 'case_industry' ),
			'services'     => nexora_ph_cases_item_text( $item, 'case_services' ),
			'img'          => $image['url'],
			'alt'          => nexora_ph_cases_item_text( $item, 'case_alt' ),
			'hasImg'       => $image['hasImg'],
			'buttonText'   => nexora_ph_cases_item_text( $item, 'case_button_text' ),
			'buttonUrl'    => $link['url'],
			'buttonTarget' => $link['target'],
			'buttonRel'    => $link['rel'],
			'stats'        => nexora_ph_cases_stats( nexora_ph_cases_item_text( $item, 'case_stats' ) ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_cases_payload' ) ) {
	/**
	 * Slides payload for the cases template.
	 *
	 * @param mixed $settings Elementor settings.
	 * @return array{cases: array<int, array<string, mixed>>}
	 */
	function nexora_ph_cases_payload( $settings ): array {
		$slides = ( is_array( $settings ) && array_key_exists( 'cases_slides', $settings ) && is_array( $settings['cases_slides'] ) && $settings['cases_slides'] )
			? array_values( $settings['cases_slides'] )
			: nexora_ph_cases_default_slides();

		if ( ! $slides ) {
			$slides = nexora_ph_cases_default_slides();
		}

		return [
			'cases' => array_map( 'nexora_ph_cases_slide_payload', $slides ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_cases_json' ) ) {
	/**
	 * JSON safe to place inside a script tag.
	 *
	 * @param mixed $payload Payload.
	 */
	function nexora_ph_cases_json( $payload ): string {
		$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
		$json  = function_exists( 'wp_json_encode' )
			? wp_json_encode( $payload, JSON_UNESCAPED_UNICODE )
			: json_encode( $payload, $flags );
		return is_string( $json ) ? $json : '{"cases":[]}';
	}
}
