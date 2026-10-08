<?php
/**
 * Nav menu data. The bar comes from a WordPress menu. Mega content comes from the widget.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/icons.php';

if ( ! function_exists( 'nexora_ph_nav_text' ) ) {
	/**
	 * Read a string field. A missing key uses the default. An empty string stays empty.
	 *
	 * @param mixed  $source  Settings or repeater row.
	 * @param string $key     Field id.
	 * @param string $default Unsaved value.
	 */
	function nexora_ph_nav_text( $source, $key, $default = '' ): string {
		if ( ! is_array( $source ) || ! array_key_exists( $key, $source ) ) {
			return $default;
		}
		$value = $source[ $key ];
		if ( is_array( $value ) ) {
			$value = $value['url'] ?? '';
		}
		if ( ! is_scalar( $value ) ) {
			return $default;
		}
		return (string) $value;
	}
}

if ( ! function_exists( 'nexora_ph_nav_link' ) ) {
	/**
	 * Normalize a link. http(s) opens in a new tab.
	 *
	 * @param mixed $url Saved link.
	 * @return array{url: string, external: bool}
	 */
	function nexora_ph_nav_link( $url ): array {
		if ( is_array( $url ) ) {
			$url = $url['url'] ?? '';
		}
		$url = trim( (string) $url );
		if ( '' === $url ) {
			$url = '#';
		} elseif ( function_exists( 'esc_url_raw' ) ) {
			$clean = (string) esc_url_raw( $url );
			$url   = '' === $clean ? '#' : $clean;
		} elseif ( '#' !== $url[0] && ! preg_match( '#^https?://#i', $url ) && ! preg_match( '#^(mailto|tel):#i', $url ) && ! preg_match( '#^/#', $url ) ) {
			$url = '#';
		}
		return [
			'url'      => $url,
			'external' => (bool) preg_match( '#^https?://#i', $url ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_nav_kses_svg' ) ) {
	/**
	 * Keep only the SVG tags used by the nav icons.
	 *
	 * @param string $svg Markup.
	 */
	function nexora_ph_nav_kses_svg( $svg ): string {
		$svg     = (string) $svg;
		$allowed = [
			'svg'     => [
				'width'           => true,
				'height'          => true,
				'viewbox'         => true,
				'fill'            => true,
				'stroke'          => true,
				'stroke-width'    => true,
				'stroke-linecap'  => true,
				'stroke-linejoin' => true,
				'aria-hidden'     => true,
				'xmlns'           => true,
			],
			'path'    => [ 'd' => true ],
			'rect'    => [
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'rx'     => true,
			],
			'circle'  => [
				'cx' => true,
				'cy' => true,
				'r'  => true,
			],
			'ellipse' => [
				'cx' => true,
				'cy' => true,
				'rx' => true,
				'ry' => true,
			],
		];
		if ( function_exists( 'wp_kses' ) ) {
			return (string) wp_kses( $svg, $allowed );
		}
		if ( 0 !== strpos( ltrim( $svg ), '<svg' ) || false !== stripos( $svg, '<script' ) ) {
			return '';
		}
		return $svg;
	}
}

if ( ! function_exists( 'nexora_ph_nav_icon_html' ) ) {
	/**
	 * Picked icon, else the row's original SVG, else the shared fallback.
	 *
	 * @param mixed  $icon Icon control value.
	 * @param string $seed Original SVG.
	 */
	function nexora_ph_nav_icon_html( $icon, $seed ): string {
		if ( is_array( $icon ) && ! empty( $icon['value'] ) && class_exists( '\Elementor\Icons_Manager' ) ) {
			ob_start();
			\Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] );
			$html = ob_get_clean();
			if ( is_string( $html ) && '' !== trim( $html ) ) {
				return $html;
			}
		}
		$safe = nexora_ph_nav_kses_svg( $seed );
		if ( '' !== $safe ) {
			return $safe;
		}
		$icons = nexora_ph_nav_seed_icons();
		return nexora_ph_nav_kses_svg( $icons[9] ?? '' );
	}
}

if ( ! function_exists( 'nexora_ph_nav_mega_winner' ) ) {
	/**
	 * The one menu item that stays checked.
	 *
	 * Newly checked items win. Otherwise the last checked item in menu order stays checked.
	 *
	 * @param array<int, int> $ordered_ids         Item ids in menu order.
	 * @param array<int, int> $checked_ids         Items checked in this save.
	 * @param array<int, int> $previously_checked  Items checked before this save.
	 */
	function nexora_ph_nav_mega_winner( array $ordered_ids, array $checked_ids, array $previously_checked ): int {
		$checked = [];
		foreach ( $ordered_ids as $id ) {
			$id = (int) $id;
			if ( in_array( $id, $checked_ids, true ) ) {
				$checked[] = $id;
			}
		}
		$newly = [];
		foreach ( $checked as $id ) {
			if ( ! in_array( $id, $previously_checked, true ) ) {
				$newly[] = $id;
			}
		}
		if ( $newly ) {
			return (int) $newly[ count( $newly ) - 1 ];
		}
		if ( $checked ) {
			return (int) $checked[ count( $checked ) - 1 ];
		}
		return 0;
	}
}

if ( ! function_exists( 'nexora_ph_nav_default_cards' ) ) {
	/**
	 * Seeded service cards.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_nav_default_cards(): array {
		$icons = nexora_ph_nav_seed_icons();
		$cards = [
			[ 'Cold Email Outreach', 'Secure meetings with decision-makers and build a stable pipeline.' ],
			[ 'LinkedIn Lead Generation', 'Relationship-first LinkedIn outreach that starts real sales conversations.' ],
			[ 'Email Marketing', 'Nurture campaigns that break through the noise and book calls.' ],
			[ 'Account-Based Marketing (ABM)', 'Personal, research-led campaigns that win high-value accounts.' ],
			[ 'CRM Setup & Management', 'Clean pipelines, routing and reporting your sales team can trust.' ],
			[ 'B2B Content Marketing', 'Content that builds authority and warms up accounts before outreach.' ],
			[ 'AI Workflow Automation', 'Automate research, follow-ups and reporting to save hours every week.' ],
			[ 'Outbound Marketing', 'Email, LinkedIn and follow-ups run as one coordinated campaign.' ],
			[ 'B2B Lead Generation', 'Hand-picked, verified lists of sales-ready prospects.' ],
		];
		$rows = [];
		foreach ( $cards as $index => $card ) {
			$rows[] = [
				'card_label'       => $card[0],
				'card_description' => $card[1],
				'card_url'         => '#services',
				'card_icon'        => [
					'value'   => '',
					'library' => '',
				],
				'card_seed_svg'    => $icons[ $index ] ?? '',
			];
		}
		return $rows;
	}
}

if ( ! function_exists( 'nexora_ph_nav_default_others' ) ) {
	/**
	 * Seeded other-service links.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_nav_default_others(): array {
		$icons  = nexora_ph_nav_seed_icons();
		$labels = [ 'Attio CRM', 'HubSpot CRM', 'Investment Management' ];
		$rows   = [];
		foreach ( $labels as $index => $label ) {
			$rows[] = [
				'other_label'    => $label,
				'other_url'      => '#services',
				'other_icon'     => [
					'value'   => '',
					'library' => '',
				],
				'other_seed_svg' => $icons[ 9 + $index ] ?? '',
			];
		}
		return $rows;
	}
}

if ( ! function_exists( 'nexora_ph_nav_item_value' ) ) {
	/**
	 * Read one field from a menu item array or object.
	 *
	 * @param mixed  $item    Menu item.
	 * @param string $key     Field name.
	 * @param mixed  $default Missing value.
	 * @return mixed
	 */
	function nexora_ph_nav_item_value( $item, $key, $default = '' ) {
		if ( is_object( $item ) && isset( $item->$key ) ) {
			return $item->$key;
		}
		if ( is_array( $item ) && array_key_exists( $key, $item ) ) {
			return $item[ $key ];
		}
		return $default;
	}
}

if ( ! function_exists( 'nexora_ph_nav_item_id' ) ) {
	/**
	 * Menu item id.
	 *
	 * @param mixed $item Menu item.
	 */
	function nexora_ph_nav_item_id( $item ): int {
		$id = nexora_ph_nav_item_value( $item, 'ID', null );
		if ( null === $id ) {
			$id = nexora_ph_nav_item_value( $item, 'id', 0 );
		}
		return (int) $id;
	}
}

if ( ! function_exists( 'nexora_ph_nav_item_parent' ) ) {
	/**
	 * Parent item id. Zero is a top-level item.
	 *
	 * @param mixed $item Menu item.
	 */
	function nexora_ph_nav_item_parent( $item ): int {
		$parent = nexora_ph_nav_item_value( $item, 'menu_item_parent', null );
		if ( null === $parent ) {
			$parent = nexora_ph_nav_item_value( $item, 'parent', 0 );
		}
		return (int) $parent;
	}
}

if ( ! function_exists( 'nexora_ph_nav_is_hash_link' ) ) {
	/**
	 * A link that is only a hash.
	 *
	 * @param string $url URL.
	 */
	function nexora_ph_nav_is_hash_link( $url ): bool {
		$url = trim( (string) $url );
		return '' !== $url && '#' === $url[0];
	}
}

if ( ! function_exists( 'nexora_ph_nav_item_is_mega' ) ) {
	/**
	 * Whether this item is checked as the mega menu.
	 *
	 * @param mixed $item Menu item.
	 */
	function nexora_ph_nav_item_is_mega( $item ): bool {
		if ( is_array( $item ) && array_key_exists( 'mega', $item ) ) {
			return (bool) $item['mega'];
		}
		if ( is_object( $item ) && isset( $item->mega ) ) {
			return (bool) $item->mega;
		}
		$id = nexora_ph_nav_item_id( $item );
		if ( $id > 0 && function_exists( 'get_post_meta' ) ) {
			return 'yes' === get_post_meta( $id, '_nexora_ph_mega', true );
		}
		return false;
	}
}

if ( ! function_exists( 'nexora_ph_nav_item_current' ) ) {
	/**
	 * WordPress current classes. A hash link stays plain.
	 *
	 * @param mixed $item Menu item.
	 */
	function nexora_ph_nav_item_current( $item ): bool {
		$url = (string) nexora_ph_nav_item_value( $item, 'url', '' );
		if ( nexora_ph_nav_is_hash_link( $url ) ) {
			return false;
		}
		$classes = nexora_ph_nav_item_value( $item, 'classes', [] );
		if ( ! is_array( $classes ) ) {
			$classes = preg_split( '/\s+/', (string) $classes ) ?: [];
		}
		$wanted = [ 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ];
		foreach ( $classes as $class ) {
			if ( in_array( (string) $class, $wanted, true ) ) {
				return true;
			}
		}
		return false;
	}
}

if ( ! function_exists( 'nexora_ph_nav_mega_content' ) ) {
	/**
	 * Mega panel content from the widget. Missing repeaters use the seeded rows.
	 *
	 * @param mixed $settings Widget settings.
	 * @return array<string, mixed>
	 */
	function nexora_ph_nav_mega_content( $settings ): array {
		if ( ! is_array( $settings ) ) {
			$settings = [];
		}
		$card_rows  = ( array_key_exists( 'mega_cards', $settings ) && is_array( $settings['mega_cards'] ) ) ? $settings['mega_cards'] : nexora_ph_nav_default_cards();
		$other_rows = ( array_key_exists( 'mega_others', $settings ) && is_array( $settings['mega_others'] ) ) ? $settings['mega_others'] : nexora_ph_nav_default_others();
		$cards      = [];
		foreach ( $card_rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$cards[] = [
				'label'       => nexora_ph_nav_text( $row, 'card_label' ),
				'url'         => nexora_ph_nav_text( $row, 'card_url' ),
				'description' => nexora_ph_nav_text( $row, 'card_description' ),
				'icon'        => nexora_ph_nav_icon_html( $row['card_icon'] ?? null, nexora_ph_nav_text( $row, 'card_seed_svg' ) ),
			];
		}
		$others = [];
		foreach ( $other_rows as $row ) {
			if ( ! is_array( $row ) ) {
				continue;
			}
			$others[] = [
				'label' => nexora_ph_nav_text( $row, 'other_label' ),
				'url'   => nexora_ph_nav_text( $row, 'other_url' ),
				'icon'  => nexora_ph_nav_icon_html( $row['other_icon'] ?? null, nexora_ph_nav_text( $row, 'other_seed_svg' ) ),
			];
		}
		return [
			'view_text'    => nexora_ph_nav_text( $settings, 'mega_view_text', 'View all services' ),
			'view_url'     => nexora_ph_nav_text( $settings, 'mega_view_url', '#services' ),
			'others_label' => nexora_ph_nav_text( $settings, 'mega_others_label', 'Other services' ),
			'cta_title'    => nexora_ph_nav_text( $settings, 'mega_cta_title', 'Book a Call' ),
			'cta_text'     => nexora_ph_nav_text( $settings, 'mega_cta_text', "See how Prospects Hive's outbound system can fill your pipeline." ),
			'cta_url'      => nexora_ph_nav_text( $settings, 'mega_cta_url', 'https://tidycal.com/prospectshive/discovery-call' ),
			'cards'        => $cards,
			'others'       => $others,
		];
	}
}

if ( ! function_exists( 'nexora_ph_nav_groups_from_items' ) ) {
	/**
	 * Turn menu items into bar groups. The last checked top-level item is the mega menu.
	 *
	 * @param mixed $items    Menu items.
	 * @param mixed $settings Widget settings.
	 * @return array<int, array<string, mixed>>
	 */
	function nexora_ph_nav_groups_from_items( $items, $settings ): array {
		if ( ! is_array( $items ) ) {
			return [];
		}
		$children = [];
		$tops     = [];
		foreach ( $items as $item ) {
			if ( ! is_array( $item ) && ! is_object( $item ) ) {
				continue;
			}
			$parent = nexora_ph_nav_item_parent( $item );
			if ( 0 === $parent ) {
				$tops[] = $item;
				continue;
			}
			$children[ $parent ][] = $item;
		}
		$winner = 0;
		foreach ( $tops as $item ) {
			if ( nexora_ph_nav_item_is_mega( $item ) ) {
				$winner = nexora_ph_nav_item_id( $item );
			}
		}
		$mega   = nexora_ph_nav_mega_content( $settings );
		$groups = [];
		foreach ( $tops as $item ) {
			$id      = nexora_ph_nav_item_id( $item );
			$kids    = $children[ $id ] ?? [];
			$is_mega = $winner > 0 && $id === $winner;
			$dropdown = [];
			if ( $is_mega ) {
				$type = 'mega';
			} elseif ( $kids ) {
				$type = 'dropdown';
				foreach ( $kids as $kid ) {
					$dropdown[] = [
						'label'       => (string) nexora_ph_nav_item_value( $kid, 'title', '' ),
						'url'         => (string) nexora_ph_nav_item_value( $kid, 'url', '' ),
						'description' => trim( (string) nexora_ph_nav_item_value( $kid, 'description', '' ) ),
					];
				}
			} else {
				$type = 'link';
			}
			$groups[] = [
				'label'        => (string) nexora_ph_nav_item_value( $item, 'title', '' ),
				'type'         => $type,
				'url'          => (string) nexora_ph_nav_item_value( $item, 'url', '' ),
				'current'      => nexora_ph_nav_item_current( $item ),
				'view_text'    => $is_mega ? $mega['view_text'] : '',
				'view_url'     => $is_mega ? $mega['view_url'] : '',
				'others_label' => $is_mega ? $mega['others_label'] : '',
				'cta_title'    => $is_mega ? $mega['cta_title'] : '',
				'cta_text'     => $is_mega ? $mega['cta_text'] : '',
				'cta_url'      => $is_mega ? $mega['cta_url'] : '',
				'dropdown'     => $dropdown,
				'cards'        => $is_mega ? $mega['cards'] : [],
				'others'       => $is_mega ? $mega['others'] : [],
			];
		}
		return $groups;
	}
}

if ( ! function_exists( 'nexora_ph_nav_menu_items' ) ) {
	/**
	 * Items for a menu. A missing or deleted menu returns none.
	 *
	 * @param int $menu_id Menu term id.
	 * @return array<int, mixed>
	 */
	function nexora_ph_nav_menu_items( $menu_id ): array {
		$menu_id = (int) $menu_id;
		if ( $menu_id <= 0 || ! function_exists( 'wp_get_nav_menu_object' ) || ! function_exists( 'wp_get_nav_menu_items' ) ) {
			return [];
		}
		$menu = wp_get_nav_menu_object( $menu_id );
		if ( ! $menu ) {
			return [];
		}
		$items = wp_get_nav_menu_items( $menu_id );
		if ( ! is_array( $items ) ) {
			return [];
		}

		/*
		 * wp_get_nav_menu_items() only decorates the raw items. WordPress attaches
		 * current-menu-item (and the ancestor/parent variants) inside wp_nav_menu(),
		 * so a menu read straight from that call never reports an active item and the
		 * nav's `.on` state never renders. Run the same core pass wp_nav_menu() uses.
		 */
		if (
			function_exists( '_wp_menu_item_classes_by_context' )
			&& isset( $GLOBALS['wp_query'] )
			&& $GLOBALS['wp_query'] instanceof WP_Query
		) {
			_wp_menu_item_classes_by_context( $items );
		}

		return $items;
	}
}
