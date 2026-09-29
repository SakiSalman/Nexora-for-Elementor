<?php
/**
 * Nexora ELE Timeline Widget
 *
 * Scroll-driven storytelling timeline with sticky image panel.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/includes/class-widget-base.php';
require_once __DIR__ . '/timeline/defaults.php';
require_once __DIR__ . '/timeline/controls-content.php';
require_once __DIR__ . '/timeline/controls-style.php';

if ( ! class_exists( 'Nexora_Timeline_Widget', false ) ) {

class Nexora_Timeline_Widget extends Nexora_Ele_Widget_Base {

	/**
	 * Widget name.
	 */
	public function get_name(): string {
		return 'ele-timeline';
	}

	/**
	 * Widget title.
	 */
	public function get_title(): string {
		return esc_html__( 'ELE Timeline', 'nexora-elementor' );
	}

	/**
	 * Widget icon.
	 */
	public function get_icon(): string {
		return 'eicon-time-line';
	}

	/**
	 * Widget categories.
	 */
	public function get_categories(): array {
		return [ 'general' ];
	}

	/**
	 * Widget keywords.
	 */
	public function get_keywords(): array {
		return [
			'timeline',
			'scroll',
			'process',
			'story',
			'nexora',
		];
	}

	/**
	 * Widget style dependencies.
	 */
	public function get_style_depends(): array {
		if ( ! $this->is_nexora_widget_enabled() ) {
			return [];
		}

		return [ 'nexora-ele-timeline' ];
	}

	/**
	 * Widget script dependencies.
	 */
	public function get_script_depends(): array {
		if ( ! $this->is_nexora_widget_enabled() ) {
			return [];
		}

		return [ 'nexora-ele-timeline' ];
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
		if ( function_exists( 'nexora_timeline_register_content_controls' ) ) {
			nexora_timeline_register_content_controls( $this );
		}

		if ( function_exists( 'nexora_timeline_register_style_controls' ) ) {
			nexora_timeline_register_style_controls( $this );
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

		$uid = 'nexora-tl-' . sanitize_key( (string) $this->get_id() );

		$render_file = __DIR__ . '/timeline/render.php';

		if ( ! is_readable( $render_file ) ) {
			return;
		}

		include $render_file;
	}
}
}
