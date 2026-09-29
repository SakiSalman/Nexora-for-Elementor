<?php
/**
 * PH Final CTA widget.
 *
 * Markup, CSS, and behavior are copied from the Prospects Hive design export.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once dirname( __DIR__ ) . '/includes/class-widget-base.php';
require_once __DIR__ . '/ph-cta/controls-content.php';

if ( ! class_exists( 'Nexora_PH_Cta_Widget', false ) ) {

	/**
	 * Prospects Hive cta widget.
	 */
	class Nexora_PH_Cta_Widget extends Nexora_Ele_Widget_Base {

		/**
		 * Widget name.
		 */
		public function get_name(): string {
			return 'ele-ph-cta';
		}

		/**
		 * Widget title.
		 */
		public function get_title(): string {
			return esc_html__( 'PH Final CTA', 'nexora-elementor' );
		}

		/**
		 * Widget icon.
		 */
		public function get_icon(): string {
			return 'eicon-call-to-action';
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
			return [ 'nexora', 'prospects', 'cta' ];
		}

		/**
		 * Style dependencies.
		 */
		public function get_style_depends(): array {
			if ( ! $this->is_nexora_widget_enabled() ) {
				return [];
			}

			return [ 'nexora-ph-fonts', 'nexora-ph-shared', 'nexora-ph-cta' ];
		}

		/**
		 * Script dependencies.
		 */
		public function get_script_depends(): array {
			if ( ! $this->is_nexora_widget_enabled() ) {
				return [];
			}

			return [];
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
			if ( function_exists( 'nexora_ph_cta_register_content_controls' ) ) {
				nexora_ph_cta_register_content_controls( $this );
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

			$raw = (string) $this->get_id();
			if ( '' === $raw ) {
				$raw = wp_unique_id();
			}

			$uid = 'ph' . sanitize_html_class( $raw );

			require NEXORA_ELE_PATH . 'widgets/ph-cta/render.php';
		}
	}
}
