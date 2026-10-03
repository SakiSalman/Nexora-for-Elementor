<?php
/**
 * Insights blog post cards — latest published posts.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_insights_read_time' ) ) {
	/**
	 * Estimated minutes from content length (~200 wpm).
	 *
	 * @param string $content Post content.
	 */
	function nexora_ph_insights_read_time( $content ): string {
		$text  = wp_strip_all_tags( (string) $content );
		$words = str_word_count( $text );
		$mins  = max( 1, (int) ceil( $words / 200 ) );
		return sprintf(
			/* translators: %d: estimated minutes */
			_n( '%d min read', '%d min read', $mins, 'nexora-elementor' ),
			$mins
		);
	}
}

if ( ! function_exists( 'nexora_ph_insights_posts' ) ) {
	/**
	 * Latest published posts for the grid.
	 *
	 * @param int $count How many posts.
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_insights_posts( $count = 3 ): array {
		$count = max( 1, min( 12, (int) $count ) );
		$query = new WP_Query(
			[
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => $count,
				'orderby'             => 'date',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			]
		);

		$out = [];
		foreach ( $query->posts as $post ) {
			if ( ! $post instanceof WP_Post ) {
				continue;
			}
			$cats = get_the_category( $post->ID );
			$chip = '';
			if ( is_array( $cats ) && isset( $cats[0] ) && $cats[0] instanceof WP_Term ) {
				$chip = $cats[0]->name;
			}

			$image = get_the_post_thumbnail_url( $post->ID, 'large' );
			if ( ! is_string( $image ) ) {
				$image = '';
			}

			$excerpt = get_the_excerpt( $post );
			if ( '' === trim( (string) $excerpt ) ) {
				$excerpt = wp_trim_words( wp_strip_all_tags( $post->post_content ), 28, '…' );
			}

			$out[] = [
				'title'    => get_the_title( $post ),
				'excerpt'  => $excerpt,
				'url'      => get_permalink( $post ),
				'image'    => $image,
				'alt'      => '' !== $image ? get_the_title( $post ) : '',
				'chip'     => $chip,
				'readTime' => nexora_ph_insights_read_time( $post->post_content ),
			];
		}
		wp_reset_postdata();

		return $out;
	}
}

if ( ! function_exists( 'nexora_ph_insights_cards_html' ) ) {
	/**
	 * Build article card markup for the grid.
	 *
	 * @param array<int, array<string, mixed>> $posts Posts payload.
	 */
	function nexora_ph_insights_cards_html( array $posts ): string {
		if ( ! $posts ) {
			return '';
		}

		$html = '';
		foreach ( $posts as $post ) {
			$title   = isset( $post['title'] ) ? (string) $post['title'] : '';
			$excerpt = isset( $post['excerpt'] ) ? (string) $post['excerpt'] : '';
			$url     = isset( $post['url'] ) ? (string) $post['url'] : '';
			$image   = isset( $post['image'] ) ? (string) $post['image'] : '';
			$alt     = isset( $post['alt'] ) ? (string) $post['alt'] : $title;
			$chip    = isset( $post['chip'] ) ? (string) $post['chip'] : '';
			$read    = isset( $post['readTime'] ) ? (string) $post['readTime'] : '';

			$meta = '<div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap">';
			if ( '' !== $chip ) {
				$meta .= '<span class="chip o">' . esc_html( $chip ) . '</span>';
			}
			if ( '' !== $read ) {
				$meta .= '<span class="muted" style="font-size: 13.5px">' . esc_html( $read ) . '</span>';
			}
			$meta .= '</div>';

			$img = '<div class="ins-img">';
			if ( '' !== $image ) {
				$img .= '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
			}
			$img .= '</div>';

			$html .= '<article class="card ins-card reveal" style="padding: 0; overflow: hidden; display: flex; flex-direction: column">'
				. $img
				. '<div style="padding: 26px 26px 28px; display: flex; flex-direction: column; gap: 12px; flex-grow: 1">'
				. $meta
				. '<h3 style="font-size: 20px; font-weight: 600; line-height: 1.35">' . esc_html( $title ) . '</h3>'
				. '<p class="muted" style="font-size: 15.5px; line-height: 1.65">' . esc_html( $excerpt ) . '</p>'
				. '<a class="more" href="' . esc_url( $url ) . '" style="margin-top: auto; color: #C2410C">'
				. esc_html__( 'Read article', 'nexora-elementor' )
				. ' <span aria-hidden="true">→</span></a>'
				. '</div>'
				. '</article>';
		}

		return $html;
	}
}
