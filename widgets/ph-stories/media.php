<?php
/**
 * Success Stories slide media (image or Self / YouTube / Vimeo with poster).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_stories_item_url' ) ) {
	/**
	 * MEDIA or string URL from a repeater item.
	 *
	 * @param mixed  $item Repeater row.
	 * @param string $key  Field id.
	 */
	function nexora_ph_stories_item_url( $item, $key ): string {
		if ( ! is_array( $item ) || ! array_key_exists( $key, $item ) ) {
			return '';
		}
		$raw = $item[ $key ];
		if ( is_array( $raw ) ) {
			if ( ! empty( $raw['url'] ) ) {
				$raw = (string) $raw['url'];
			} elseif ( ! empty( $raw['relative'] ) ) {
				return 'assets/' . ltrim( (string) $raw['relative'], '/' );
			} else {
				$raw = '';
			}
		}
		$raw = trim( (string) $raw );
		if ( '' === $raw ) {
			return '';
		}
		if ( 0 === strpos( $raw, 'assets/' ) ) {
			return $raw;
		}
		return function_exists( 'esc_url_raw' ) ? (string) esc_url_raw( $raw ) : $raw;
	}
}

if ( ! function_exists( 'nexora_ph_stories_item_text' ) ) {
	/**
	 * @param mixed  $item Repeater row.
	 * @param string $key  Field id.
	 */
	function nexora_ph_stories_item_text( $item, $key ): string {
		if ( ! is_array( $item ) || ! array_key_exists( $key, $item ) || ! is_scalar( $item[ $key ] ) ) {
			return '';
		}
		return (string) $item[ $key ];
	}
}

if ( ! function_exists( 'nexora_ph_stories_playable' ) ) {
	/**
	 * Playable media for one slide, or null for image-only.
	 *
	 * @param array<string, mixed> $item Repeater row.
	 * @return array{source:string,src:string,poster:string,alt:string,label:string}|null
	 */
	function nexora_ph_stories_playable( array $item ): ?array {
		$source = nexora_ph_stories_item_text( $item, 'video_source' );
		if ( ! in_array( $source, [ 'self', 'youtube', 'vimeo' ], true ) ) {
			$source = 'self';
		}

		$poster = nexora_ph_stories_item_url( $item, 'image' );
		$default_poster = NEXORA_ELE_URL . 'assets/images/prospects/testimonial.webp';
		if ( $poster === $default_poster || $poster === 'assets/testimonial.webp' ) {
			$poster = 'assets/testimonial.webp';
		}

		$alt = trim( nexora_ph_stories_item_text( $item, 'alt' ) );
		if ( '' === $alt ) {
			$alt = 'Client video testimonial';
		}

		$label = trim( nexora_ph_stories_item_text( $item, 'aria' ) );
		if ( '' === $label ) {
			$label = 'Play client video';
		}

		$src = '';
		if ( 'self' === $source ) {
			$src = nexora_ph_stories_item_url( $item, 'video_file' );
		} else {
			$src = trim( nexora_ph_stories_item_text( $item, 'video_url' ) );
			$src = function_exists( 'esc_url_raw' ) ? (string) esc_url_raw( $src ) : $src;
		}

		if ( '' === $src ) {
			return null;
		}

		return [
			'source' => $source,
			'src'    => $src,
			'poster' => $poster,
			'alt'    => $alt,
			'label'  => $label,
		];
	}
}

if ( ! function_exists( 'nexora_ph_stories_player_html' ) ) {
	/**
	 * Player markup that keeps the stories play button look.
	 *
	 * @param array{source:string,src:string,poster:string,alt:string,label:string} $player Player.
	 */
	function nexora_ph_stories_player_html( array $player ): string {
		$source = $player['source'];
		$src    = $player['src'];
		$poster = $player['poster'];
		$alt    = $player['alt'];
		$label  = $player['label'];

		$surface = '';
		if ( 'self' === $source ) {
			$surface = '<video class="ph-story-video-el" preload="metadata" playsinline'
				. ( '' !== $poster ? ' poster="' . esc_attr( $poster ) . '"' : '' )
				. ' src="' . esc_attr( $src ) . '"></video>';
		}
		if ( '' !== $poster ) {
			$surface .= '<img class="ph-story-poster" src="' . esc_attr( $poster ) . '" alt="' . esc_attr( $alt ) . '" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">';
		} elseif ( 'self' !== $source ) {
			$surface .= '<div class="ph-story-poster ph-story-poster--empty" aria-hidden="true"></div>';
		}

		$icon = '<svg width="28" height="28" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z" fill="#0B1620"></path></svg>';
		$btn  = '<button type="button" class="pulse sheen ph-story-play" aria-label="' . esc_attr( $label ) . '" style="position: absolute; left: 50%; top: 50%; margin: -42px 0 0 -42px; width: 84px; height: 84px; border-radius: 50%; border: none; cursor: pointer; background: linear-gradient(145deg, #FF8A4C, #F15E22 60%, #D9480F); box-shadow: inset 0 2px 0 rgba(255,255,255,.5); display: flex; align-items: center; justify-content: center">' . $icon . '</button>';

		return '<div class="ph-story-player" data-ph-video data-source="' . esc_attr( $source ) . '" data-src="' . esc_attr( $src ) . '"'
			. ( '' !== $poster ? ' data-poster="' . esc_attr( $poster ) . '"' : '' )
			. '>'
			. '<div class="ph-story-surface">' . $surface . '</div>'
			. $btn
			. '</div>';
	}
}

if ( ! function_exists( 'nexora_ph_stories_image_only_html' ) ) {
	/**
	 * Image-only media (no play control).
	 *
	 * @param array<string, mixed> $item Repeater row.
	 */
	function nexora_ph_stories_image_only_html( array $item ): string {
		$poster = nexora_ph_stories_item_url( $item, 'image' );
		$default_poster = NEXORA_ELE_URL . 'assets/images/prospects/testimonial.webp';
		if ( '' === $poster || $poster === $default_poster ) {
			$poster = 'assets/testimonial.webp';
		}
		$alt = trim( nexora_ph_stories_item_text( $item, 'alt' ) );
		if ( '' === $alt ) {
			$alt = 'Client video testimonial';
		}
		return '<img src="' . esc_attr( $poster ) . '" alt="' . esc_attr( $alt ) . '" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover">';
	}
}

if ( ! function_exists( 'nexora_ph_stories_apply_media' ) ) {
	/**
	 * Replace each [data-ph-story-media] inner HTML from repeater settings.
	 *
	 * @param string               $html     Markup after content map.
	 * @param array<string, mixed> $settings Settings.
	 */
	function nexora_ph_stories_apply_media( string $html, array $settings ): string {
		$items = [];
		if ( isset( $settings['stories_r1'] ) && is_array( $settings['stories_r1'] ) && $settings['stories_r1'] ) {
			$items = array_values( $settings['stories_r1'] );
		} else {
			$map = function_exists( 'nexora_ph_content_map' ) ? nexora_ph_content_map( 'stories' ) : null;
			if ( is_array( $map ) && ! empty( $map['repeaters'][0]['defaults'] ) && is_array( $map['repeaters'][0]['defaults'] ) ) {
				$items = array_values( $map['repeaters'][0]['defaults'] );
			}
		}

		$marker = 'data-ph-story-media';
		$offset = 0;
		$index  = 0;
		while ( true ) {
			$pos = strpos( $html, $marker, $offset );
			if ( false === $pos ) {
				break;
			}
			// Find the opening <div ... data-ph-story-media ...>
			$open = strrpos( substr( $html, 0, $pos ), '<div' );
			if ( false === $open ) {
				$offset = $pos + strlen( $marker );
				continue;
			}
			$gt = strpos( $html, '>', $pos );
			if ( false === $gt ) {
				break;
			}
			$inner_start = $gt + 1;

			$depth  = 1;
			$cursor = $inner_start;
			$len    = strlen( $html );
			$close  = false;
			while ( $cursor < $len && $depth > 0 ) {
				$next_open  = stripos( $html, '<div', $cursor );
				$next_close = stripos( $html, '</div>', $cursor );
				if ( false === $next_close ) {
					$close = false;
					break;
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
			if ( false === $close ) {
				$offset = $inner_start;
				continue;
			}

			$item = isset( $items[ $index ] ) && is_array( $items[ $index ] ) ? $items[ $index ] : [];
			$play = nexora_ph_stories_playable( $item );
			$inner = null === $play
				? nexora_ph_stories_image_only_html( $item )
				: nexora_ph_stories_player_html( $play );

			$html   = substr( $html, 0, $inner_start ) . $inner . substr( $html, $close );
			$offset = $inner_start + strlen( $inner ) + 6;
			$index++;
		}

		return $html;
	}
}
