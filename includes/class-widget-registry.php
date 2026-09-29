<?php
/**
 * Canonical catalog of Nexora Elementor widgets.
 *
 * Add new widgets here only — bootstrap + settings read from this registry.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nexora_Ele_Widget_Registry', false ) ) {

	/**
	 * Widget registry.
	 */
	final class Nexora_Ele_Widget_Registry {

		/**
		 * Cached definitions.
		 *
		 * @var array<string, array<string, mixed>>|null
		 */
		private static $widgets = null;

		/**
		 * All widget definitions keyed by id.
		 *
		 * @return array<string, array<string, mixed>>
		 */
		public static function all(): array {
			if ( null !== self::$widgets ) {
				return self::$widgets;
			}

			$definitions = [
				'ele-gtm-funnel' => [
					'id'              => 'ele-gtm-funnel',
					'title'           => __( 'ELE GTM Funnel', 'nexora-elementor' ),
					'description'     => __( 'GTM strategy process cards with an interactive sales funnel.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-gtm-funnel.php',
					'class'           => 'Nexora_GTM_Funnel_Widget',
					'default_enabled' => true,
					'styles'          => [
						[
							'handle'   => 'nexora-plus-jakarta-font',
							'src'      => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap',
							'deps'     => [],
							'ver'      => null,
							'external' => true,
						],
						[
							'handle' => 'nexora-ele-gtm-funnel',
							'src'    => 'assets/css/nexora-ele-gtm-funnel.css',
							'deps'   => [ 'nexora-plus-jakarta-font' ],
							'ver'    => true,
						],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ele-gtm-funnel',
							'src'       => 'assets/js/nexora-ele-gtm-funnel.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
				'ele-timeline' => [
					'id'              => 'ele-timeline',
					'title'           => __( 'ELE Timeline', 'nexora-elementor' ),
					'description'     => __( 'Scroll-driven storytelling timeline with sticky image and progress track.', 'nexora-elementor' ),
					'file'            => 'widgets/class-ele-timeline.php',
					'class'           => 'Nexora_Timeline_Widget',
					'default_enabled' => true,
					'styles'          => [
						[
							'handle'   => 'nexora-plus-jakarta-font',
							'src'      => 'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap',
							'deps'     => [],
							'ver'      => null,
							'external' => true,
						],
						[
							'handle' => 'nexora-ele-timeline',
							'src'    => 'assets/css/nexora-ele-timeline.css',
							'deps'   => [ 'nexora-plus-jakarta-font' ],
							'ver'    => true,
						],
					],
					'scripts'         => [
						[
							'handle'    => 'nexora-ele-timeline',
							'src'       => 'assets/js/nexora-ele-timeline.js',
							'deps'      => [ 'jquery' ],
							'ver'       => true,
							'in_footer' => true,
						],
					],
				],
			];

			/**
			 * Filter the Nexora widget registry.
			 *
			 * @param array<string, array<string, mixed>> $definitions Widget definitions.
			 */
			self::$widgets = apply_filters( 'nexora_ele_widget_registry', $definitions );

			return self::$widgets;
		}

		/**
		 * Single widget definition.
		 *
		 * @param string $id Widget id.
		 * @return array<string, mixed>|null
		 */
		public static function get( string $id ): ?array {
			$all = self::all();
			return isset( $all[ $id ] ) && is_array( $all[ $id ] ) ? $all[ $id ] : null;
		}

		/**
		 * Widget ids.
		 *
		 * @return string[]
		 */
		public static function ids(): array {
			return array_keys( self::all() );
		}
	}
}
