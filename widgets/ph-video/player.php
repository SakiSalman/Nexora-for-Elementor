<?php
/**
 * PH Video player helpers.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_video_media_url' ) ) {
	/**
	 * Media control URL.
	 *
	 * @param array<string, mixed> $settings Settings.
	 * @param string               $key      Control key.
	 */
	function nexora_ph_video_media_url( array $settings, string $key ): string {
		if ( empty( $settings[ $key ] ) || ! is_array( $settings[ $key ] ) ) {
			return '';
		}
		$url = isset( $settings[ $key ]['url'] ) ? (string) $settings[ $key ]['url'] : '';
		return esc_url_raw( $url );
	}
}

if ( ! function_exists( 'nexora_ph_video_playable' ) ) {
	/**
	 * Whether settings include a playable source.
	 *
	 * @param array<string, mixed> $settings Settings.
	 * @return array{source:string,src:string,poster:string,label:string}|null
	 */
	function nexora_ph_video_playable( array $settings ): ?array {
		$source = isset( $settings['video_source'] ) ? (string) $settings['video_source'] : 'self';
		if ( ! in_array( $source, [ 'self', 'youtube', 'vimeo' ], true ) ) {
			$source = 'self';
		}

		$poster = nexora_ph_video_media_url( $settings, 'video_poster' );
		$label  = isset( $settings['video_play_label'] ) ? trim( (string) $settings['video_play_label'] ) : '';
		if ( '' === $label ) {
			$label = 'Play video';
		}

		$src = '';
		if ( 'self' === $source ) {
			$src = nexora_ph_video_media_url( $settings, 'video_file' );
		} else {
			$src = isset( $settings['video_url'] ) ? trim( (string) $settings['video_url'] ) : '';
			$src = esc_url_raw( $src );
		}

		if ( '' === $src ) {
			return null;
		}

		return [
			'source' => $source,
			'src'    => $src,
			'poster' => $poster,
			'label'  => $label,
		];
	}
}

if ( ! function_exists( 'nexora_ph_video_player_html' ) ) {
	/**
	 * Build player markup.
	 *
	 * @param array{source:string,src:string,poster:string,label:string} $player Player data.
	 */
	function nexora_ph_video_player_html( array $player ): string {
		$source = $player['source'];
		$src    = $player['src'];
		$poster = $player['poster'];
		$label  = $player['label'];

		$surface = '';
		if ( 'self' === $source ) {
			$surface = '<video class="ph-video-el" preload="metadata" playsinline'
				. ( '' !== $poster ? ' poster="' . esc_attr( $poster ) . '"' : '' )
				. ' src="' . esc_attr( $src ) . '"></video>';
			if ( '' !== $poster ) {
				$surface .= '<div class="ph-video-cover" style="background-image:url(' . esc_url( $poster ) . ')" aria-hidden="true"></div>';
			}
		} elseif ( '' !== $poster ) {
			$surface = '<div class="ph-video-cover" style="background-image:url(' . esc_url( $poster ) . ')" aria-hidden="true"></div>';
		} else {
			$surface = '<div class="ph-video-cover ph-video-cover--empty" aria-hidden="true"></div>';
		}

		$icon = '<svg width="32" height="32" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z" fill="var(--ph-on-accent, #FFFFFF)"></path></svg>';

		return '<div class="ph-video-player" data-ph-video data-source="' . esc_attr( $source ) . '" data-src="' . esc_attr( $src ) . '"'
			. ( '' !== $poster ? ' data-poster="' . esc_attr( $poster ) . '"' : '' )
			. '>'
			. '<div class="ph-video-surface">' . $surface . '</div>'
			. '<button type="button" class="pulse sheen ph-play ph-video-play" aria-label="' . esc_attr( $label ) . '">' . $icon . '</button>'
			. '</div>';
	}
}

if ( ! function_exists( 'nexora_ph_video_inject_player' ) ) {
	/**
	 * Inject player into prepared section HTML without shifting content-map offsets upstream.
	 *
	 * @param string               $html     Markup after content map.
	 * @param array<string, mixed> $settings Settings.
	 */
	function nexora_ph_video_inject_player( string $html, array $settings ): string {
		$player = nexora_ph_video_playable( $settings );
		if ( null === $player ) {
			return $html;
		}

		$open = 'class="mid vid-box"';
		$pos  = strpos( $html, $open );
		if ( false === $pos ) {
			return $html;
		}

		$html = substr_replace( $html, 'class="mid vid-box is-live"', $pos, strlen( $open ) );

		// Insert player immediately after the opening vid-box tag.
		$gt = strpos( $html, '>', $pos );
		if ( false === $gt ) {
			return $html;
		}

		$inner_start = $gt + 1;
		$close       = strpos( $html, '</div>', $inner_start );
		if ( false === $close ) {
			return $html;
		}

		// vid-box contains nested divs; find the matching close by scanning depth from open tag.
		$depth = 1;
		$cursor = $inner_start;
		$len    = strlen( $html );
		while ( $cursor < $len && $depth > 0 ) {
			$next_open  = stripos( $html, '<div', $cursor );
			$next_close = stripos( $html, '</div>', $cursor );
			if ( false === $next_close ) {
				return $html;
			}
			if ( false !== $next_open && $next_open < $next_close ) {
				$depth++;
				$cursor = $next_open + 4;
				continue;
			}
			$depth--;
			if ( 0 === $depth ) {
				$close = $next_close;
				break;
			}
			$cursor = $next_close + 6;
		}

		$inner = substr( $html, $inner_start, $close - $inner_start );
		$live  = '<div class="ph-video-mock" data-ph-video-mock>' . $inner . '</div>' . nexora_ph_video_player_html( $player );

		return substr( $html, 0, $inner_start ) . $live . substr( $html, $close );
	}
}
