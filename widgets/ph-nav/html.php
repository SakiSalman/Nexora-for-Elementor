<?php
/**
 * Build the nav HTML from a WordPress menu and the widget mega content.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/data.php';

if ( ! function_exists( 'nexora_ph_nav_h' ) ) {
	/**
	 * Escape text.
	 *
	 * @param string $text Text.
	 */
	function nexora_ph_nav_h( $text ): string {
		return function_exists( 'esc_html' ) ? esc_html( $text ) : htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}
}

if ( ! function_exists( 'nexora_ph_nav_attr_url' ) ) {
	/**
	 * href plus new-tab attributes.
	 *
	 * @param string $url Link.
	 */
	function nexora_ph_nav_attr_url( $url ): string {
		$link = nexora_ph_nav_link( $url );
		$href = function_exists( 'esc_url' ) ? esc_url( $link['url'] ) : htmlspecialchars( $link['url'], ENT_QUOTES, 'UTF-8' );
		$html = ' href="' . $href . '"';
		if ( $link['external'] ) {
			$html .= ' target="_blank" rel="noopener"';
		}
		return $html;
	}
}

if ( ! function_exists( 'nexora_ph_nav_chevron' ) ) {
	/**
	 * The existing menu chevron.
	 */
	function nexora_ph_nav_chevron(): string {
		return '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9l6 6 6-6"></path></svg>';
	}
}

if ( ! function_exists( 'nexora_ph_nav_arrow' ) ) {
	/**
	 * The existing button arrow.
	 */
	function nexora_ph_nav_arrow(): string {
		return '<svg class="btn-arr" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"></path><path d="M8 7h9v9"></path></svg>';
	}
}

if ( ! function_exists( 'nexora_ph_nav_logo_src' ) ) {
	/**
	 * Logo image URL. A cleared image stays empty.
	 *
	 * @param mixed $settings Widget settings.
	 */
	function nexora_ph_nav_logo_src( $settings ): string {
		$default = 'assets/logo-color.png';
		if ( ! is_array( $settings ) || ! array_key_exists( 'logo_image', $settings ) ) {
			return $default;
		}
		$image = $settings['logo_image'];
		if ( ! is_array( $image ) ) {
			return '';
		}
		$url = trim( (string) ( $image['url'] ?? '' ) );
		if ( '' === $url || false !== strpos( $url, 'logo-color.png' ) ) {
			return '' === $url ? '' : $default;
		}
		return $url;
	}
}

if ( ! function_exists( 'nexora_ph_nav_html' ) ) {
	/**
	 * Full nav section.
	 *
	 * @param mixed $settings Widget settings.
	 */
	function nexora_ph_nav_html( $settings ): string {
		if ( ! is_array( $settings ) ) {
			$settings = [];
		}
		$rows = [];
		if ( isset( $settings['_items'] ) && is_array( $settings['_items'] ) ) {
			$rows = $settings['_items'];
		} else {
			$rows = nexora_ph_nav_menu_items( (int) nexora_ph_nav_text( $settings, 'nav_menu', '0' ) );
		}
		$groups = nexora_ph_nav_groups_from_items( $rows, $settings );

		$logo_src   = nexora_ph_nav_logo_src( $settings );
		$logo_alt   = nexora_ph_nav_text( $settings, 'logo_alt', 'Prospects Hive' );
		$logo_label = nexora_ph_nav_text( $settings, 'logo_label', 'Prospects Hive home' );
		$logo_url   = nexora_ph_nav_text( $settings, 'logo_url', '#' );
		$btn_text   = nexora_ph_nav_text( $settings, 'button_text', 'Book A Call' );
		$btn_url    = nexora_ph_nav_text( $settings, 'button_url', 'https://tidycal.com/prospectshive/discovery-call' );

		$logo_img = '';
		if ( '' !== $logo_src ) {
			$src      = function_exists( 'esc_url' ) ? esc_url( $logo_src ) : htmlspecialchars( $logo_src, ENT_QUOTES, 'UTF-8' );
			$logo_img = '<img src="' . $src . '" alt="' . nexora_ph_nav_h( $logo_alt ) . '" style="height: 26px; width: auto; display: block">';
		}

		$desktop = '';
		$phone   = '';
		$panels  = '';
		$scrim   = false;

		foreach ( $groups as $index => $group ) {
			$label   = nexora_ph_nav_h( $group['label'] );
			$panel   = 'nav-panel-' . $index;
			$current = ! empty( $group['current'] );
			$mark    = $current ? ' on' : '';
			$style   = $current ? ' style="color: var(--ph-ink, #0B1620); font-weight: 600"' : '';
			if ( 'link' === $group['type'] ) {
				$desktop .= '<a class="navlink' . $mark . '" data-nav-link' . nexora_ph_nav_attr_url( $group['url'] ) . $style . '>' . $label . '</a>';
				$phone   .= '<a class="m-link' . $mark . '" data-nav-link' . nexora_ph_nav_attr_url( $group['url'] ) . $style . '>' . $label . ' <span aria-hidden="true">→</span></a>';
				continue;
			}

			$desktop .= '<button type="button" class="navlink navbtn' . $mark . '" data-nav-open="' . $index . '" aria-haspopup="true" aria-expanded="false" aria-controls="' . $panel . '"' . $style . '>' . $label . ' ' . nexora_ph_nav_chevron() . '</button>';
			$phone   .= '<button type="button" class="m-link' . $mark . '" data-nav-phone="' . $index . '" aria-expanded="false"' . $style . '>' . $label . ' ' . nexora_ph_nav_chevron() . '</button>';
			$phone   .= '<div class="m-sub" data-nav-sub="' . $index . '" hidden>' . nexora_ph_nav_phone_sub( $group ) . '</div>';

			if ( 'mega' === $group['type'] ) {
				$scrim    = true;
				$panels  .= '<div class="mm" id="' . $panel . '" data-nav-panel="' . $index . '" role="menu" aria-label="' . $label . '" hidden>' . nexora_ph_nav_mega( $group ) . '</div>';
			} else {
				$panels .= '<div class="dd" id="' . $panel . '" data-nav-panel="' . $index . '" role="menu" aria-label="' . $label . '" hidden>' . nexora_ph_nav_dropdown( $group ) . '</div>';
			}
		}

		$scrim_html = $scrim ? '<div class="mm-scrim" data-nav-scrim hidden></div>' : '';
		$button     = '<a class="btn btn-dark sheen nav-cta"' . nexora_ph_nav_attr_url( $btn_url ) . ' data-nav-link style="height: 46px; padding: 0 22px; font-size: 15px">' . nexora_ph_nav_h( $btn_text ) . ' ' . nexora_ph_nav_arrow() . '</a>';
		$phone_btn  = '<a class="btn btn-primary sheen"' . nexora_ph_nav_attr_url( $btn_url ) . ' data-nav-link style="height: 52px; margin-top: 8px; font-size: 16px">' . nexora_ph_nav_h( $btn_text ) . ' ' . nexora_ph_nav_arrow() . '</a>';

		return '<header class="nav">'
			. '<nav aria-label="Main" class="wrap" style="height: 64px; display: flex; align-items: center; justify-content: space-between; gap: 24px">'
			. '<a' . nexora_ph_nav_attr_url( $logo_url ) . ' style="display: flex; align-items: center; text-decoration: none" aria-label="' . nexora_ph_nav_h( $logo_label ) . '">' . $logo_img . '</a>'
			. '<div class="hide-md" style="display: flex; gap: 6px; align-items: center">' . $desktop . '</div>'
			. $button
			. '<button class="menu-btn" type="button" aria-label="Open menu" aria-expanded="false" data-nav-menu><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h10"></path></svg></button>'
			. '</nav>'
			. $scrim_html
			. $panels
			. '<div class="m-menu" role="menu" data-nav-phone-menu hidden>' . $phone . $phone_btn . '</div>'
			. '</header>';
	}
}

if ( ! function_exists( 'nexora_ph_nav_mega' ) ) {
	/**
	 * Services panel for one mega group.
	 *
	 * @param array<string, mixed> $group Group.
	 */
	function nexora_ph_nav_mega( $group ): string {
		$view = '';
		if ( '' !== $group['view_text'] ) {
			$view = '<div class="mm-top" style="justify-content: flex-end"><a class="mm-all" data-nav-link' . nexora_ph_nav_attr_url( $group['view_url'] ) . '>' . nexora_ph_nav_h( $group['view_text'] ) . ' <span aria-hidden="true">→</span></a></div>';
		}
		$cards = '';
		foreach ( $group['cards'] as $index => $card ) {
			$class  = 0 === $index ? 'mm-item mm-feat' : 'mm-item';
			$cards .= '<a class="' . $class . '" role="menuitem" data-nav-link' . nexora_ph_nav_attr_url( $card['url'] ) . '><span class="mm-ic">' . $card['icon'] . '</span><span class="mm-tx"><strong>' . nexora_ph_nav_h( $card['label'] ) . '</strong><span>' . nexora_ph_nav_h( $card['description'] ) . '</span></span></a>';
		}
		$grid = '' !== $cards ? '<div class="mm-grid">' . $cards . '</div>' : '';
		$main = ( '' !== $view || '' !== $grid ) ? '<div class="mm-main">' . $view . $grid . '</div>' : '';

		$others = '';
		if ( $group['others'] ) {
			$links = '';
			foreach ( $group['others'] as $other ) {
				$links .= '<a class="mm-other" role="menuitem" data-nav-link' . nexora_ph_nav_attr_url( $other['url'] ) . '><span class="mm-oic">' . $other['icon'] . '</span>' . nexora_ph_nav_h( $other['label'] ) . '</a>';
			}
			$heading = '' !== $group['others_label'] ? '<span class="mm-label">' . nexora_ph_nav_h( $group['others_label'] ) . '</span>' : '';
			$others  = '<div class="mm-others">' . $heading . '<div class="mm-orow">' . $links . '</div></div>';
		}
		$cta = '';
		if ( '' !== $group['cta_title'] ) {
			$cta = '<a class="mm-cta" data-nav-link' . nexora_ph_nav_attr_url( $group['cta_url'] ) . '><span><strong>' . nexora_ph_nav_h( $group['cta_title'] ) . '</strong><span>' . nexora_ph_nav_h( $group['cta_text'] ) . '</span></span><span class="mm-arrow" aria-hidden="true">' . nexora_ph_nav_arrow() . '</span></a>';
		}
		$foot = ( '' !== $others || '' !== $cta ) ? '<div class="mm-foot">' . $others . $cta . '</div>' : '';
		return $main . $foot;
	}
}

if ( ! function_exists( 'nexora_ph_nav_dropdown' ) ) {
	/**
	 * Small dropdown panel.
	 *
	 * @param array<string, mixed> $group Group.
	 */
	function nexora_ph_nav_dropdown( $group ): string {
		$html = '';
		foreach ( $group['dropdown'] as $item ) {
			$desc  = '' !== $item['description'] ? '<span>' . nexora_ph_nav_h( $item['description'] ) . '</span>' : '';
			$html .= '<a role="menuitem" data-nav-link' . nexora_ph_nav_attr_url( $item['url'] ) . '><strong>' . nexora_ph_nav_h( $item['label'] ) . '</strong>' . $desc . '</a>';
		}
		return $html;
	}
}

if ( ! function_exists( 'nexora_ph_nav_phone_sub' ) ) {
	/**
	 * Phone submenu for a dropdown or mega group.
	 *
	 * @param array<string, mixed> $group Group.
	 */
	function nexora_ph_nav_phone_sub( $group ): string {
		if ( 'dropdown' === $group['type'] ) {
			$html = '';
			foreach ( $group['dropdown'] as $item ) {
				$desc  = '' !== $item['description'] ? '<span class="dd-note">' . nexora_ph_nav_h( $item['description'] ) . '</span>' : '';
				$html .= '<a data-nav-link' . nexora_ph_nav_attr_url( $item['url'] ) . '><span><strong>' . nexora_ph_nav_h( $item['label'] ) . '</strong>' . $desc . '</span></a>';
			}
			return $html;
		}
		$html = '';
		foreach ( $group['cards'] as $card ) {
			$html .= '<a data-nav-link' . nexora_ph_nav_attr_url( $card['url'] ) . '><span class="mm-sic">' . $card['icon'] . '</span>' . nexora_ph_nav_h( $card['label'] ) . '</a>';
		}
		if ( $group['others'] ) {
			if ( '' !== $group['others_label'] ) {
				$html .= '<span class="mm-label">' . nexora_ph_nav_h( $group['others_label'] ) . '</span>';
			}
			foreach ( $group['others'] as $other ) {
				$html .= '<a data-nav-link' . nexora_ph_nav_attr_url( $other['url'] ) . '><span class="mm-oic">' . $other['icon'] . '</span>' . nexora_ph_nav_h( $other['label'] ) . '</a>';
			}
		}
		return $html;
	}
}
