<?php
/**
 * Shared Elementor widget base for soft enable/disable.
 *
 * Widgets stay registered so Elementor keeps saved instances.
 * Disabled widgets are hidden from the panel and skip front-end output/assets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( '\Elementor\Widget_Base', false ) ) {
	return;
}

use Elementor\Widget_Base;

if ( ! class_exists( 'Nexora_Ele_Widget_Base', false ) ) {

	/**
	 * Soft-disable aware widget base.
	 */
	abstract class Nexora_Ele_Widget_Base extends Widget_Base {

		/**
		 * Hide from Elementor panel when disabled in Nexora settings.
		 */
		public function show_in_panel(): bool {
			return $this->is_nexora_widget_enabled();
		}

		/**
		 * Whether this widget is enabled in Nexora settings.
		 */
		protected function is_nexora_widget_enabled(): bool {
			if ( ! class_exists( 'Nexora_Ele_Settings', false ) ) {
				return true;
			}

			return Nexora_Ele_Settings::is_widget_enabled( $this->get_name() );
		}

		/**
		 * Editor-only placeholder when the widget is disabled but still on the page.
		 */
		protected function render_disabled_placeholder(): void {
			$is_edit = false;

			if ( class_exists( '\Elementor\Plugin', false ) && isset( \Elementor\Plugin::$instance->editor ) ) {
				$is_edit = (bool) \Elementor\Plugin::$instance->editor->is_edit_mode();
			}

			if ( ! $is_edit ) {
				return;
			}

			$title = $this->get_title();
			$url   = admin_url( 'admin.php?page=nexora-for-elementor' );
			?>
			<div class="nexora-ele-disabled-placeholder" style="padding:20px;border:1px dashed #cbd5e1;border-radius:8px;background:#f8fafc;color:#475569;font-family:system-ui,sans-serif;font-size:13px;line-height:1.5;">
				<strong style="display:block;margin-bottom:6px;color:#0f172a;">
					<?php echo esc_html( $title ); ?>
				</strong>
				<?php echo esc_html__( 'This widget is disabled in Nexora settings. Your content is preserved — re-enable it to show on the site.', 'nexora-elementor' ); ?>
				<?php if ( current_user_can( 'manage_options' ) ) : ?>
					<br />
					<a href="<?php echo esc_url( $url ); ?>" style="color:#fa5a18;font-weight:700;">
						<?php echo esc_html__( 'Open Nexora settings', 'nexora-elementor' ); ?>
					</a>
				<?php endif; ?>
			</div>
			<?php
		}
	}
}
