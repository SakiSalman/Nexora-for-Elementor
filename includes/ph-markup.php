<?php
/**
 * Shared Prospects Hive markup renderer.
 *
 * Integration changes, and only these:
 * 1. Wrap the original tree in .nexora-ph.
 * 2. Rewrite assets/ to the copied image directory.
 * 3. Suffix in-widget ids with the Elementor element id and keep data-ph-anchor.
 * 4. Dynamic sections stay in a template until nexora-ph-runtime.js mounts them.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_render_section' ) ) {
	/**
	 * Print one Prospects Hive section.
	 *
	 * @param string $slug    Widget slug.
	 * @param string $uid     Element id suffix.
	 * @param bool   $dynamic Whether the section uses the template runtime.
	 */
	function nexora_ph_render_section( $slug, $uid, $dynamic ): void {
		$slug = preg_replace( '/[^a-z0-9_-]/', '', (string) $slug );
		$uid  = sanitize_html_class( (string) $uid );

		if ( '' === $slug || '' === $uid ) {
			return;
		}

		$file = NEXORA_ELE_PATH . 'widgets/ph-' . $slug . '/markup.html';
		if ( ! is_readable( $file ) ) {
			return;
		}

		$html = file_get_contents( $file );
		if ( ! is_string( $html ) || '' === $html ) {
			return;
		}

		$base = NEXORA_ELE_URL . 'assets/images/prospects/';
		$html = str_replace( 'assets/', $base, $html );

		$ids = [];
		if ( preg_match_all( '/\sid=(["\'])([^"\']+)\1/', $html, $matches ) ) {
			$ids = array_unique( $matches[2] );
		}

		usort(
			$ids,
			static function ( $a, $b ) {
				return strlen( $b ) <=> strlen( $a );
			}
		);

		foreach ( $ids as $id ) {
			$new = sanitize_html_class( $id . '-' . $uid );
			if ( '' === $new ) {
				continue;
			}

			$html = preg_replace(
				'/\sid=(["\'])' . preg_quote( $id, '/' ) . '\1/',
				' id="' . esc_attr( $new ) . '" data-ph-anchor="' . esc_attr( $id ) . '"',
				$html
			);
			$html = str_replace( 'url(#' . $id . ')', 'url(#' . $new . ')', $html );
			$html = str_replace( "url('#" . $id . "')", "url('#" . $new . "')", $html );
			$html = str_replace( 'url("#' . $id . '")', 'url("#' . $new . '")', $html );
			$html = preg_replace(
				'/(\shref=)(["\'])#' . preg_quote( $id, '/' ) . '\2/',
				'$1$2#' . esc_attr( $new ) . '$2',
				$html
			);
			$html = preg_replace(
				'/(\s(?:for|aria-controls|aria-labelledby|aria-describedby)=)(["\'])' . preg_quote( $id, '/' ) . '\2/',
				'$1$2' . esc_attr( $new ) . '$2',
				$html
			);
		}

		// details name groups are document-wide. Suffix them so two copies do not close each other.
		$html = preg_replace(
			'/(<details\b[^>]*\s)name=(["\'])faq\2/',
			'$1name=$2faq-' . $uid . '$2',
			$html
		);

		$dynamic = (bool) $dynamic;
		echo '<div class="nexora-ph nexora-ph-' . esc_attr( $slug ) . '" data-nexora-ph-' . esc_attr( $slug );
		if ( $dynamic ) {
			echo ' data-ph-base="' . esc_url( $base ) . '"';
		}
		echo '>';

		if ( $dynamic ) {
			echo '<template class="nexora-ph-tpl">';
		}

		echo $html;

		if ( $dynamic ) {
			echo '</template>';
		}

		echo '</div>';
	}
}
