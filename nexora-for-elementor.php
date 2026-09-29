<?php
/**
 * Plugin Name:       Nexora for Elementor
 * Description:       Modern Elementor widgets for building high-converting websites.
 * Version:           1.1.1
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            wpsaki
 * Author URI:        https://profiles.wordpress.org/wpsaki/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nexora-elementor
 * Requires Plugins:  elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'NEXORA_ELE_FILE', __FILE__ );
define( 'NEXORA_ELE_PATH', plugin_dir_path( __FILE__ ) );
define( 'NEXORA_ELE_URL', plugin_dir_url( __FILE__ ) );

require_once NEXORA_ELE_PATH . 'includes/class-widget-registry.php';
require_once NEXORA_ELE_PATH . 'includes/class-settings.php';
require_once NEXORA_ELE_PATH . 'includes/class-admin-settings.php';

/**
 * Main Nexora Plugin Class
 */
final class Nexora_For_Elementor {

	/**
	 * Plugin version.
	 */
	const VERSION = '1.1.1';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Initialize plugin.
	 */
	public function init(): void {

		if ( is_admin() ) {
			Nexora_Ele_Admin_Settings::init();
		}

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'elementor_missing_notice' ] );
			return;
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_styles' ] );
		add_action( 'elementor/preview/enqueue_scripts', [ $this, 'enqueue_preview_scripts' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	/**
	 * Resolve asset URL (plugin-relative or absolute/external).
	 *
	 * @param array<string, mixed> $asset Asset definition.
	 */
	private function asset_src( array $asset ): string {
		$src = isset( $asset['src'] ) ? (string) $asset['src'] : '';

		if ( '' === $src ) {
			return '';
		}

		if ( ! empty( $asset['external'] ) || preg_match( '#^(https?:)?//#i', $src ) ) {
			return $src;
		}

		return plugins_url( ltrim( $src, '/' ), NEXORA_ELE_FILE );
	}

	/**
	 * Resolve asset version.
	 *
	 * @param array<string, mixed> $asset Asset definition.
	 * @return string|false|null
	 */
	private function asset_ver( array $asset ) {
		if ( ! array_key_exists( 'ver', $asset ) ) {
			return self::VERSION;
		}

		if ( true === $asset['ver'] ) {
			return self::VERSION;
		}

		return $asset['ver'];
	}

	/**
	 * Register styles/scripts for enabled widgets only.
	 */
	public function register_assets(): void {
		$registered_styles  = [];
		$registered_scripts = [];

		foreach ( Nexora_Ele_Settings::get_enabled_widgets() as $widget ) {
			$styles = isset( $widget['styles'] ) && is_array( $widget['styles'] ) ? $widget['styles'] : [];
			foreach ( $styles as $style ) {
				if ( ! is_array( $style ) || empty( $style['handle'] ) ) {
					continue;
				}
				$handle = (string) $style['handle'];
				if ( isset( $registered_styles[ $handle ] ) || wp_style_is( $handle, 'registered' ) ) {
					$registered_styles[ $handle ] = true;
					continue;
				}
				wp_register_style(
					$handle,
					$this->asset_src( $style ),
					isset( $style['deps'] ) && is_array( $style['deps'] ) ? $style['deps'] : [],
					$this->asset_ver( $style )
				);
				$registered_styles[ $handle ] = true;
			}

			$scripts = isset( $widget['scripts'] ) && is_array( $widget['scripts'] ) ? $widget['scripts'] : [];
			foreach ( $scripts as $script ) {
				if ( ! is_array( $script ) || empty( $script['handle'] ) ) {
					continue;
				}
				$handle = (string) $script['handle'];
				if ( isset( $registered_scripts[ $handle ] ) || wp_script_is( $handle, 'registered' ) ) {
					$registered_scripts[ $handle ] = true;
					continue;
				}
				wp_register_script(
					$handle,
					$this->asset_src( $script ),
					isset( $script['deps'] ) && is_array( $script['deps'] ) ? $script['deps'] : [],
					$this->asset_ver( $script ),
					! empty( $script['in_footer'] )
				);
				$registered_scripts[ $handle ] = true;
			}
		}
	}

	/**
	 * Enqueue enabled-widget styles in Elementor preview.
	 */
	public function enqueue_preview_styles(): void {
		$this->register_assets();

		foreach ( Nexora_Ele_Settings::get_enabled_widgets() as $widget ) {
			$styles = isset( $widget['styles'] ) && is_array( $widget['styles'] ) ? $widget['styles'] : [];
			foreach ( $styles as $style ) {
				if ( is_array( $style ) && ! empty( $style['handle'] ) ) {
					wp_enqueue_style( (string) $style['handle'] );
				}
			}
		}
	}

	/**
	 * Enqueue enabled-widget scripts in Elementor preview.
	 */
	public function enqueue_preview_scripts(): void {
		$this->register_assets();

		foreach ( Nexora_Ele_Settings::get_enabled_widgets() as $widget ) {
			$scripts = isset( $widget['scripts'] ) && is_array( $widget['scripts'] ) ? $widget['scripts'] : [];
			foreach ( $scripts as $script ) {
				if ( is_array( $script ) && ! empty( $script['handle'] ) ) {
					wp_enqueue_script( (string) $script['handle'] );
				}
			}
		}
	}

	/**
	 * Register all catalog widgets (soft-disable keeps them registered).
	 *
	 * Disabled widgets stay registered so Elementor preserves page data.
	 * They are hidden from the panel via show_in_panel() and skip assets/output.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ): void {
		// Elementor classes are available on this hook — load the soft-disable base first.
		require_once NEXORA_ELE_PATH . 'includes/class-widget-base.php';

		foreach ( Nexora_Ele_Widget_Registry::all() as $widget ) {
			$file  = isset( $widget['file'] ) ? (string) $widget['file'] : '';
			$class = isset( $widget['class'] ) ? (string) $widget['class'] : '';

			if ( '' === $file || '' === $class ) {
				continue;
			}

			$path = NEXORA_ELE_PATH . ltrim( str_replace( '\\', '/', $file ), '/' );
			if ( ! is_readable( $path ) ) {
				continue;
			}

			require_once $path;

			if ( ! class_exists( $class ) ) {
				continue;
			}

			$widgets_manager->register( new $class() );
		}
	}

	/**
	 * Elementor missing notice.
	 */
	public function elementor_missing_notice(): void {

		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<strong>Nexora for Elementor</strong>
				requires Elementor to be installed and activated.
			</p>
		</div>
		<?php
	}
}

new Nexora_For_Elementor();
