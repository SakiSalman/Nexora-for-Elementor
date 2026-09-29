<?php
/**
 * Widget enable/disable settings.
 *
 * Option shape: array<widget_id, '1'|'0'>.
 * Missing keys use each widget's default_enabled (new widgets stay on after updates).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nexora_Ele_Settings', false ) ) {

	/**
	 * Settings API helpers.
	 */
	final class Nexora_Ele_Settings {

		const OPTION_KEY = 'nexora_ele_widget_status';

		/**
		 * Raw saved status map.
		 *
		 * @return array<string, string>|null Null when never saved.
		 */
		public static function get_raw_status(): ?array {
			$saved = get_option( self::OPTION_KEY, null );

			if ( null === $saved || false === $saved ) {
				return null;
			}

			if ( ! is_array( $saved ) ) {
				return null;
			}

			$clean = [];
			foreach ( $saved as $id => $value ) {
				$id = sanitize_key( (string) $id );
				if ( '' === $id ) {
					continue;
				}
				$clean[ $id ] = ( '1' === (string) $value || 1 === $value || true === $value ) ? '1' : '0';
			}

			return $clean;
		}

		/**
		 * Whether a registry widget is enabled.
		 *
		 * @param string $id Widget id.
		 */
		public static function is_widget_enabled( string $id ): bool {
			$id       = sanitize_key( $id );
			$widget   = Nexora_Ele_Widget_Registry::get( $id );
			$default  = $widget ? ! empty( $widget['default_enabled'] ) : false;
			$saved    = self::get_raw_status();

			if ( null === $saved ) {
				return $default;
			}

			if ( array_key_exists( $id, $saved ) ) {
				return '1' === $saved[ $id ];
			}

			// Newly added widget after a plugin update — use catalog default.
			return $default;
		}

		/**
		 * Enabled widget definitions only.
		 *
		 * @return array<string, array<string, mixed>>
		 */
		public static function get_enabled_widgets(): array {
			$enabled = [];

			foreach ( Nexora_Ele_Widget_Registry::all() as $id => $widget ) {
				if ( self::is_widget_enabled( (string) $id ) ) {
					$enabled[ $id ] = $widget;
				}
			}

			return $enabled;
		}

		/**
		 * Persist status map from settings form.
		 *
		 * @param array<string, mixed> $submitted Submitted checkbox map (id => '1').
		 * @return array<string, string>
		 */
		public static function save_status_from_request( array $submitted ): array {
			$status = [];

			foreach ( Nexora_Ele_Widget_Registry::ids() as $id ) {
				$status[ $id ] = ! empty( $submitted[ $id ] ) ? '1' : '0';
			}

			update_option( self::OPTION_KEY, $status, false );

			return $status;
		}

		/**
		 * Capability required to manage settings.
		 */
		public static function capability(): string {
			return 'manage_options';
		}
	}
}
