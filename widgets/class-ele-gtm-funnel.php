<?php
/**
 * Nexora GTM Funnel Widget (dynamic)
 *
 * Drag onto a page to render the GTM strategy / funnel section.
 * Content, funnel tiers, typography, colors, and interaction are editable
 * via Elementor controls. Defaults reproduce the original static design.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/includes/class-widget-base.php';
require_once __DIR__ . '/lib/class-funnel-geometry.php';
require_once __DIR__ . '/gtm-funnel/defaults.php';
require_once __DIR__ . '/gtm-funnel/controls-content.php';
require_once __DIR__ . '/gtm-funnel/controls-style.php';

if ( ! class_exists( 'Nexora_GTM_Funnel_Widget', false ) ) {

class Nexora_GTM_Funnel_Widget extends Nexora_Ele_Widget_Base {

	/**
	 * Widget name.
	 */
	public function get_name(): string {
		return 'ele-gtm-funnel';
	}

	/**
	 * Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'ELE GTM Funnel', 'nexora-elementor' );
	}

	/**
	 * Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-favorite';
	}

	/**
	 * Widget categories.
	 */
	public function get_categories(): array {
		return [ 'nexora' ];
	}

	/**
	 * Widget keywords.
	 */
	public function get_keywords(): array {
		return [
			'gtm',
			'funnel',
			'process',
			'nexora',
			'pipeline',
		];
	}

	/**
	 * Help / description for Elementor panel.
	 */
	public function get_custom_help_url(): string {
		return '';
	}

	/**
	 * Widget style dependencies.
	 */
	public function get_style_depends(): array {
		if ( ! $this->is_nexora_widget_enabled() ) {
			return [];
		}

		return [ 'nexora-ele-gtm-funnel' ];
	}

	/**
	 * Widget script dependencies.
	 */
	public function get_script_depends(): array {
		if ( ! $this->is_nexora_widget_enabled() ) {
			return [];
		}

		return [ 'nexora-ele-gtm-funnel' ];
	}

	/**
	 * Elementor compatibility.
	 */
	public function has_widget_inner_wrapper(): bool {
		return false;
	}

	/**
	 * Register controls.
	 */
	protected function register_controls(): void {
		if ( function_exists( 'nexora_gtm_funnel_register_content_controls' ) ) {
			nexora_gtm_funnel_register_content_controls( $this );
		}

		if ( function_exists( 'nexora_gtm_funnel_register_style_controls' ) ) {
			nexora_gtm_funnel_register_style_controls( $this );
		}
	}

	/**
	 * Render widget.
	 */
	protected function render(): void {
		if ( ! $this->is_nexora_widget_enabled() ) {
			$this->render_disabled_placeholder();
			return;
		}

		$settings = $this->get_settings_for_display();

		if ( ! is_array( $settings ) ) {
			$settings = [];
		}

		$uid = 'nexora-gtm-' . sanitize_key( (string) $this->get_id() );

		$render_file = __DIR__ . '/gtm-funnel/render.php';

		if ( ! is_readable( $render_file ) ) {
			return;
		}

		include $render_file;
	}
}
} // class_exists Nexora_GTM_Funnel_Widget
