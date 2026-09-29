<?php
/**
 * Default repeater content for ELE GTM Funnel.
 * Must reproduce the original static design exactly.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build a default Elementor ICONS value pointing at a packaged SVG.
 *
 * @param string $slug Icon file slug (without .svg).
 * @return array{value: array{url: string, id: string}, library: string}
 */
if ( ! function_exists( 'nexora_gtm_default_icon' ) ) {
	function nexora_gtm_default_icon( string $slug ): array {
		$slug = sanitize_file_name( $slug );

		if ( defined( 'NEXORA_ELE_URL' ) ) {
			$url = NEXORA_ELE_URL . 'assets/icons/' . $slug . '.svg';
		} else {
			$url = plugins_url(
				'assets/icons/' . $slug . '.svg',
				dirname( __DIR__, 2 ) . '/nexora-for-elementor.php'
			);
		}

		return [
			'value'   => [
				'url' => $url,
				'id'  => '',
			],
			'library' => 'svg',
		];
	}
}

/**
 * Resolve icon HTML from an Elementor ICONS control value (with legacy SVG fallback).
 *
 * @param mixed                  $icon        ICONS control value.
 * @param string                 $legacy_svg  Previous textarea SVG markup.
 * @param callable(string):string $safe_svg   SVG sanitizer.
 */
if ( ! function_exists( 'nexora_gtm_resolve_icon_html' ) ) {
	function nexora_gtm_resolve_icon_html( $icon, string $legacy_svg, callable $safe_svg ): string {
		if ( is_array( $icon ) && ! empty( $icon['value'] ) ) {
			$library = isset( $icon['library'] ) ? (string) $icon['library'] : '';

			// Inline packaged / media-library SVGs so currentColor + sizing work like before.
			if ( 'svg' === $library && is_array( $icon['value'] ) ) {
				$path = '';

				$id = isset( $icon['value']['id'] ) ? absint( $icon['value']['id'] ) : 0;
				if ( $id > 0 && function_exists( 'get_attached_file' ) ) {
					$attached = get_attached_file( $id );
					if ( is_string( $attached ) && is_readable( $attached ) ) {
						$path = $attached;
					}
				}

				if ( '' === $path && ! empty( $icon['value']['url'] ) && is_string( $icon['value']['url'] ) ) {
					$url = $icon['value']['url'];

					if ( defined( 'NEXORA_ELE_URL' ) && defined( 'NEXORA_ELE_PATH' ) ) {
						$base = NEXORA_ELE_URL . 'assets/icons/';
						if ( 0 === strpos( $url, $base ) ) {
							$file = basename( (string) wp_parse_url( $url, PHP_URL_PATH ) );
							$file = sanitize_file_name( $file );
							$candidate = NEXORA_ELE_PATH . 'assets/icons/' . $file;
							if ( is_readable( $candidate ) ) {
								$path = $candidate;
							}
						}
					}
				}

				if ( '' !== $path ) {
					$contents = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local icon asset
					if ( is_string( $contents ) ) {
						$svg = $safe_svg( $contents );
						if ( '' !== $svg ) {
							return $svg;
						}
					}
				}
			}

			if ( class_exists( '\Elementor\Icons_Manager' ) ) {
				ob_start();
				\Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
				$html = ob_get_clean();
				if ( is_string( $html ) && '' !== trim( $html ) ) {
					return $html;
				}
			}
		}

		if ( '' !== $legacy_svg ) {
			return $safe_svg( $legacy_svg );
		}

		return '';
	}
}

/**
 * Default process steps.
 *
 * @return array<int, array<string, mixed>>
 */
if ( ! function_exists( 'nexora_gtm_funnel_default_steps' ) ) {
	function nexora_gtm_funnel_default_steps(): array {
		return [
			[
				'group_heading'    => 'OUR RESPONSIBILITY',
				'step_number'      => '01',
				'step_title'       => 'Strategic Outreach',
				'step_description' => 'Multi-channel outreach across email, LinkedIn & calls to connect with the right prospects.',
				'badge_style'      => 'accent',
				'icon_frame'       => '',
				'icon_tone'        => 'accent',
				'linked_tier'      => 1,
				'step_icon'        => nexora_gtm_default_icon( 'users' ),
			],
			[
				'group_heading'    => '',
				'step_number'      => '02',
				'step_title'       => 'Engage & Nurture',
				'step_description' => 'Personalized follow-ups and value-driven touchpoints that build trust and keep conversations going.',
				'badge_style'      => 'dark',
				'icon_frame'       => '',
				'icon_tone'        => 'muted',
				'linked_tier'      => 2,
				'step_icon'        => nexora_gtm_default_icon( 'message' ),
			],
			[
				'group_heading'    => '',
				'step_number'      => '03',
				'step_title'       => 'Qualify & Connect',
				'step_description' => 'We qualify interest and book meetings with the right decision-makers.',
				'badge_style'      => 'dark',
				'icon_frame'       => '',
				'icon_tone'        => 'muted',
				'linked_tier'      => 3,
				'step_icon'        => nexora_gtm_default_icon( 'user' ),
			],
			[
				'group_heading'    => 'YOUR ROLE',
				'step_number'      => '04',
				'step_title'       => 'Close & Grow',
				'step_description' => 'You close the deals, while we continue fueling your pipeline for consistent growth.',
				'badge_style'      => 'blue',
				'icon_frame'       => 'yes',
				'icon_tone'        => 'accent',
				'linked_tier'      => 4,
				'step_icon'        => nexora_gtm_default_icon( 'briefcase' ),
			],
		];
	}
}

/**
 * Default funnel tiers.
 *
 * @return array<int, array<string, mixed>>
 */
if ( ! function_exists( 'nexora_gtm_funnel_default_tiers' ) ) {
	function nexora_gtm_funnel_default_tiers(): array {
		return [
			[
				'tier_value'     => '15,000+',
				'tier_label'     => 'Accounts Researched & Targeted',
				'tier_sublabel'  => 'Precision-list building & research',
				'gradient_start' => '#7325e8',
				'gradient_mid'   => '#8432f6',
				'gradient_end'   => '#8e37f8',
				'node_color'     => '#6d26e4',
				'show_node'      => 'yes',
				'node_icon'      => nexora_gtm_default_icon( 'users' ),
			],
			[
				'tier_value'     => '9,000+',
				'tier_label'     => 'Engaged Prospects',
				'tier_sublabel'  => 'Engaged through email and LinkedIn',
				'gradient_start' => '#205ce6',
				'gradient_mid'   => '#3074f4',
				'gradient_end'   => '#3b82f6',
				'node_color'     => '#1f5ce4',
				'show_node'      => 'yes',
				'node_icon'      => nexora_gtm_default_icon( 'mail' ),
			],
			[
				'tier_value'     => '250+',
				'tier_label'     => 'Qualified Meetings Booked',
				'tier_sublabel'  => 'Verified buyers, routed to your CRM',
				'gradient_start' => '#008cd4',
				'gradient_mid'   => '#029be3',
				'gradient_end'   => '#0ea5e9',
				'node_color'     => '#008cd4',
				'show_node'      => 'yes',
				'node_icon'      => nexora_gtm_default_icon( 'calendar' ),
			],
			[
				'tier_value'     => '10-30+',
				'tier_label'     => 'Closed Deals',
				'tier_sublabel'  => 'Deals closed by your own sales team',
				'gradient_start' => '#039380',
				'gradient_mid'   => '#06a68f',
				'gradient_end'   => '#09b79c',
				'node_color'     => '#039380',
				'show_node'      => 'yes',
				'node_icon'      => nexora_gtm_default_icon( 'trophy' ),
			],
		];
	}
}
