<?php
/**
 * Shared helpers for the Cold Email (ce-*) phase 2 widgets.
 *
 * Loaded by each ce-* render.php before template.php. PH widgets never load this file.
 * Conventions (plan section 4.1): missing key = default, cleared value stays cleared,
 * escape on output, [[word]] marks a <mark> highlight after escaping.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_ce_setting' ) ) {
	/**
	 * One setting; a missing key falls back to the default, a present key wins as-is.
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @param string               $id      Control id.
	 * @param mixed                $default Default when the key is absent.
	 * @return mixed
	 */
	function nexora_ph_ce_setting( $settings, $id, $default = '' ) {
		return ( is_array( $settings ) && array_key_exists( $id, $settings ) ) ? $settings[ $id ] : $default;
	}
}

if ( ! function_exists( 'nexora_ph_ce_text' ) ) {
	/**
	 * Escaped text setting.
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @param string               $id      Control id.
	 * @param string               $default Default when the key is absent.
	 */
	function nexora_ph_ce_text( $settings, $id, $default = '' ): string {
		return esc_html( (string) nexora_ph_ce_setting( $settings, $id, $default ) );
	}
}

if ( ! function_exists( 'nexora_ph_ce_attr' ) ) {
	/**
	 * Escaped attribute setting (alt, aria-label, placeholder, id-ish values).
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @param string               $id      Control id.
	 * @param string               $default Default when the key is absent.
	 */
	function nexora_ph_ce_attr( $settings, $id, $default = '' ): string {
		return esc_attr( (string) nexora_ph_ce_setting( $settings, $id, $default ) );
	}
}

if ( ! function_exists( 'nexora_ph_ce_mark' ) ) {
	/**
	 * Escaped rich text where [[word]] becomes <mark>wor</mark>. Text is escaped
	 * first, so only the marker-generated marks render as HTML.
	 *
	 * @param mixed $text Raw setting value.
	 */
	function nexora_ph_ce_mark( $text ): string {
		$out = esc_html( (string) $text );
		$out = preg_replace( '/\[\[(.+?)\]\]/s', '<mark>$1</mark>', $out );

		return is_string( $out ) ? $out : '';
	}
}

if ( ! function_exists( 'nexora_ph_ce_lines' ) ) {
	/**
	 * Textarea → array of trimmed non-empty lines (one item per line convention).
	 *
	 * @param mixed $value Raw textarea value.
	 * @return string[]
	 */
	function nexora_ph_ce_lines( $value ): array {
		$value = str_replace( array( "\r\n", "\r" ), "\n", (string) $value );
		$out   = array();
		foreach ( explode( "\n", $value ) as $line ) {
			$line = trim( $line );
			if ( '' !== $line ) {
				$out[] = $line;
			}
		}

		return $out;
	}
}

if ( ! function_exists( 'nexora_ph_ce_rows' ) ) {
	/**
	 * Repeater rows with defaults filled in. A row missing a key borrows it from
	 * the default row with the same _id, else the same index, else the first default row.
	 *
	 * @param array<string, mixed>   $settings Widget settings.
	 * @param string                 $id      Repeater control id.
	 * @param array<int, array<string, mixed>> $defaults Default rows.
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_ce_rows( $settings, $id, array $defaults ): array {
		$rows = nexora_ph_ce_setting( $settings, $id, null );
		if ( ! is_array( $rows ) ) {
			return $defaults;
		}

		$by_id = array();
		foreach ( $defaults as $default_row ) {
			if ( is_array( $default_row ) && isset( $default_row['_id'] ) ) {
				$by_id[ (string) $default_row['_id'] ] = $default_row;
			}
		}

		$first = isset( $defaults[0] ) && is_array( $defaults[0] ) ? $defaults[0] : array();
		$out   = array();
		foreach ( $rows as $index => $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$fill = $first;
			if ( isset( $row['_id'] ) && isset( $by_id[ (string) $row['_id'] ] ) ) {
				$fill = $by_id[ (string) $row['_id'] ];
			} elseif ( isset( $defaults[ $index ] ) && is_array( $defaults[ $index ] ) ) {
				$fill = $defaults[ $index ];
			}
			foreach ( $fill as $key => $value ) {
				if ( ! array_key_exists( $key, $row ) ) {
					$row[ $key ] = $value;
				}
			}
			$out[] = $row;
		}

		return $out;
	}
}

if ( ! function_exists( 'nexora_ph_ce_media' ) ) {
	/**
	 * MEDIA control → printable URL. Defaults carry the design's relative
	 * assets/… path, which the renderer rewrites to the plugin URL *after*
	 * the template runs — so they must leave this helper unescaped.
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @param string               $id      Control id.
	 * @param string               $default Default when the key is absent.
	 */
	function nexora_ph_ce_media( $settings, $id, $default = '' ): string {
		$value = nexora_ph_ce_setting( $settings, $id, null );
		$url   = $default;
		if ( is_array( $value ) && array_key_exists( 'url', $value ) ) {
			$url = (string) $value['url'];
		}
		$url = (string) $url;

		// esc_url() guesses a scheme for relative paths ("assets/x" ->
		// "http://assets/x"), and the renderer's assets/ rewrite would then
		// prepend the plugin base, doubling it to http://http://… (broken
		// images). Return design-relative defaults raw instead.
		if ( str_starts_with( $url, 'assets/' ) ) {
			return $url;
		}

		return esc_url( $url );
	}
}

if ( ! function_exists( 'nexora_ph_ce_link' ) ) {
	/**
	 * URL control → ready-to-print parts. Defaults keep the design's
	 * target="_blank" rel="noopener".
	 *
	 * @param array<string, mixed> $settings    Widget settings.
	 * @param string               $id         Control id.
	 * @param string               $default_url Default URL when the key is absent.
	 * @return array{url: string, target: string, rel: string}
	 */
	function nexora_ph_ce_link( $settings, $id, $default_url = '' ): array {
		$value = nexora_ph_ce_setting( $settings, $id, null );
		$url   = $default_url;
		$new   = true;
		$nofollow = false;
		if ( is_array( $value ) ) {
			if ( array_key_exists( 'url', $value ) ) {
				$url = (string) $value['url'];
			}
			if ( array_key_exists( 'is_external', $value ) ) {
				$new = filter_var( $value['is_external'], FILTER_VALIDATE_BOOLEAN );
			}
			if ( array_key_exists( 'nofollow', $value ) ) {
				$nofollow = filter_var( $value['nofollow'], FILTER_VALIDATE_BOOLEAN );
			}
		}

		return array(
			'url'    => esc_url( (string) $url ),
			'target' => $new ? ' target="_blank"' : '',
			'rel'    => $new ? ' rel="' . ( $nofollow ? 'noopener nofollow' : 'noopener' ) . '"' : ( $nofollow ? ' rel="nofollow"' : '' ),
		);
	}
}

if ( ! function_exists( 'nexora_ph_ce_heading_tag' ) ) {
	/**
	 * HTML Tag select value, allowlisted via the shared nexora_ele_heading_tag().
	 *
	 * @param array<string, mixed> $settings Widget settings.
	 * @param string               $id      Control id.
	 * @param string               $default Default tag when the key is absent.
	 */
	function nexora_ph_ce_heading_tag( $settings, $id, $default = 'h2' ): string {
		$value = nexora_ph_ce_setting( $settings, $id, $default );

		return function_exists( 'nexora_ele_heading_tag' ) ? nexora_ele_heading_tag( $value, $default ) : $default;
	}
}

if ( ! function_exists( 'nexora_ph_ce_svg' ) ) {
	/**
	 * Design icon by key (see icons.php).
	 *
	 * @param mixed $key Icon key.
	 */
	function nexora_ph_ce_svg( $key ): string {
		if ( ! function_exists( 'nexora_ph_ce_icon' ) ) {
			require_once NEXORA_ELE_PATH . 'widgets/ph-ce-lib/icons.php';
		}

		return nexora_ph_ce_icon( (string) $key );
	}
}
