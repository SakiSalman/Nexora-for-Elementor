<?php
/**
 * Admin settings page: enable / disable widgets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nexora_Ele_Admin_Settings', false ) ) {

	/**
	 * Settings UI.
	 */
	final class Nexora_Ele_Admin_Settings {

		const PAGE_SLUG = 'nexora-for-elementor';

		/**
		 * Hook admin menu + save handler.
		 */
		public static function init(): void {
			add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
			add_action( 'admin_init', [ __CLASS__, 'handle_save' ] );
			add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
			add_filter( 'plugin_action_links_' . plugin_basename( NEXORA_ELE_FILE ), [ __CLASS__, 'action_links' ] );
		}

		/**
		 * Settings link on Plugins screen.
		 *
		 * @param string[] $links Existing links.
		 * @return string[]
		 */
		public static function action_links( array $links ): array {
			$url     = admin_url( 'admin.php?page=' . self::PAGE_SLUG );
			$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'nexora-elementor' ) . '</a>';
			return $links;
		}

		/**
		 * Enqueue admin UI assets on this page only.
		 *
		 * @param string $hook Current admin page hook.
		 */
		public static function enqueue_assets( string $hook ): void {
			if ( false === strpos( $hook, self::PAGE_SLUG ) ) {
				return;
			}

			$version = class_exists( 'Nexora_For_Elementor', false )
				? Nexora_For_Elementor::VERSION
				: '1.2.1';

			wp_enqueue_style(
				'nexora-plus-jakarta-font',
				'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
				[],
				null
			);

			wp_enqueue_style(
				'nexora-ele-admin',
				plugins_url( 'assets/css/nexora-ele-admin.css', NEXORA_ELE_FILE ),
				[ 'nexora-plus-jakarta-font' ],
				$version
			);

			wp_enqueue_script(
				'nexora-ele-admin',
				plugins_url( 'assets/js/nexora-ele-admin.js', NEXORA_ELE_FILE ),
				[],
				$version,
				true
			);
		}

		/**
		 * Top-level admin menu (room for future subpages).
		 */
		public static function register_menu(): void {
			add_menu_page(
				__( 'Nexora for Elementor', 'nexora-elementor' ),
				__( 'Nexora', 'nexora-elementor' ),
				Nexora_Ele_Settings::capability(),
				self::PAGE_SLUG,
				[ __CLASS__, 'render_page' ],
				'dashicons-screenoptions',
				58
			);

			add_submenu_page(
				self::PAGE_SLUG,
				__( 'Widgets', 'nexora-elementor' ),
				__( 'Widgets', 'nexora-elementor' ),
				Nexora_Ele_Settings::capability(),
				self::PAGE_SLUG,
				[ __CLASS__, 'render_page' ]
			);
		}

		/**
		 * Save widget toggles.
		 */
		public static function handle_save(): void {
			if ( empty( $_POST['nexora_ele_settings_page'] ) || self::PAGE_SLUG !== $_POST['nexora_ele_settings_page'] ) {
				return;
			}

			if ( ! current_user_can( Nexora_Ele_Settings::capability() ) ) {
				return;
			}

			check_admin_referer( 'nexora_ele_save_widgets', 'nexora_ele_settings_nonce' );

			$submitted = isset( $_POST['nexora_ele_widgets'] ) && is_array( $_POST['nexora_ele_widgets'] )
				? wp_unslash( $_POST['nexora_ele_widgets'] )
				: [];

			Nexora_Ele_Settings::save_status_from_request( $submitted );

			// Flush Elementor CSS/data cache so re-enabled widgets paint correctly.
			if ( class_exists( '\Elementor\Plugin', false ) && isset( \Elementor\Plugin::$instance->files_manager ) ) {
				\Elementor\Plugin::$instance->files_manager->clear_cache();
			}

			wp_safe_redirect(
				add_query_arg(
					[
						'page'             => self::PAGE_SLUG,
						'nexora-ele-saved' => '1',
					],
					admin_url( 'admin.php' )
				)
			);
			exit;
		}

		/**
		 * Default icon SVG for a widget card.
		 *
		 * @param string $id Widget id.
		 */
		private static function widget_icon_svg( string $id ): string {
			if ( 'ele-timeline' === $id ) {
				return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v18"/><circle cx="12" cy="6" r="2.2"/><circle cx="12" cy="12" r="2.2"/><circle cx="12" cy="18" r="2.2"/></svg>';
			}

			return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16l-3.2 4.8a4 4 0 0 0-.8 2.4V20l-4-2.5L7.2 20v-8.8a4 4 0 0 0-.8-2.4L4 4z"/></svg>';
		}

		/**
		 * Render settings page.
		 */
		public static function render_page(): void {
			if ( ! current_user_can( Nexora_Ele_Settings::capability() ) ) {
				return;
			}

			$widgets = Nexora_Ele_Widget_Registry::all();
			$total   = count( $widgets );
			$enabled = 0;

			foreach ( array_keys( $widgets ) as $id ) {
				if ( Nexora_Ele_Settings::is_widget_enabled( (string) $id ) ) {
					++$enabled;
				}
			}

			$label_on  = __( 'Enabled', 'nexora-elementor' );
			$label_off = __( 'Disabled', 'nexora-elementor' );
			?>
			<div class="wrap nexora-ele-admin">
				<header class="nexora-ele-admin__hero">
					<p class="nexora-ele-admin__brand"><?php echo esc_html__( 'Nexora', 'nexora-elementor' ); ?></p>
					<h1 class="nexora-ele-admin__title"><?php echo esc_html__( 'Widgets', 'nexora-elementor' ); ?></h1>
					<p class="nexora-ele-admin__lede">
						<?php echo esc_html__( 'Turn widgets off to hide them from Elementor’s panel and skip their CSS/JS. Existing placements stay safe and come back when you re-enable.', 'nexora-elementor' ); ?>
					</p>
				</header>

				<?php if ( ! empty( $_GET['nexora-ele-saved'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
					<div class="nexora-ele-admin__notice" role="status">
						<span class="nexora-ele-admin__notice-dot" aria-hidden="true"></span>
						<?php echo esc_html__( 'Widget settings saved.', 'nexora-elementor' ); ?>
					</div>
				<?php endif; ?>

				<form method="post" action="" class="nexora-ele-admin__form">
					<?php wp_nonce_field( 'nexora_ele_save_widgets', 'nexora_ele_settings_nonce' ); ?>
					<input type="hidden" name="nexora_ele_settings_page" value="<?php echo esc_attr( self::PAGE_SLUG ); ?>" />

					<div class="nexora-ele-admin__toolbar">
						<div class="nexora-ele-admin__count">
							<span class="nexora-ele-admin__count-pill" data-nexora-enabled-count><?php echo esc_html( (string) $enabled ); ?></span>
							<span>
								<?php echo esc_html__( 'of', 'nexora-elementor' ); ?>
								<strong data-nexora-total-count><?php echo esc_html( (string) $total ); ?></strong>
								<?php echo esc_html__( 'widgets enabled', 'nexora-elementor' ); ?>
							</span>
						</div>
						<div class="nexora-ele-admin__bulk">
							<button type="button" class="nexora-ele-admin__bulk-btn" data-nexora-enable-all>
								<?php echo esc_html__( 'Enable all', 'nexora-elementor' ); ?>
							</button>
							<button type="button" class="nexora-ele-admin__bulk-btn" data-nexora-disable-all>
								<?php echo esc_html__( 'Disable all', 'nexora-elementor' ); ?>
							</button>
						</div>
					</div>

					<div class="nexora-ele-admin__grid">
						<?php if ( empty( $widgets ) ) : ?>
							<div class="nexora-ele-admin__empty">
								<?php echo esc_html__( 'No widgets registered in the catalog yet.', 'nexora-elementor' ); ?>
							</div>
						<?php else : ?>
							<?php foreach ( $widgets as $id => $widget ) :
								$widget_id = (string) $id;
								$is_on     = Nexora_Ele_Settings::is_widget_enabled( $widget_id );
								$title     = isset( $widget['title'] ) ? (string) $widget['title'] : $widget_id;
								$desc      = isset( $widget['description'] ) ? (string) $widget['description'] : '';
								$input_id  = 'nexora-ele-widget-' . $widget_id;
								?>
								<label
									class="nexora-ele-admin__card<?php echo $is_on ? ' is-enabled' : ''; ?>"
									for="<?php echo esc_attr( $input_id ); ?>"
								>
									<span class="nexora-ele-admin__card-main">
										<span class="nexora-ele-admin__icon">
											<?php echo self::widget_icon_svg( $widget_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG ?>
										</span>
										<span class="nexora-ele-admin__meta">
											<span class="nexora-ele-admin__widget-title"><?php echo esc_html( $title ); ?></span>
											<?php if ( '' !== $desc ) : ?>
												<span class="nexora-ele-admin__widget-desc"><?php echo esc_html( $desc ); ?></span>
											<?php endif; ?>
											<code class="nexora-ele-admin__widget-id"><?php echo esc_html( $widget_id ); ?></code>
											<span
												class="nexora-ele-admin__status"
												data-on="<?php echo esc_attr( $label_on ); ?>"
												data-off="<?php echo esc_attr( $label_off ); ?>"
											><?php echo esc_html( $is_on ? $label_on : $label_off ); ?></span>
										</span>
									</span>
									<span class="nexora-ele-admin__switch">
										<input
											type="checkbox"
											id="<?php echo esc_attr( $input_id ); ?>"
											name="nexora_ele_widgets[<?php echo esc_attr( $widget_id ); ?>]"
											value="1"
											<?php checked( $is_on ); ?>
										/>
										<span class="nexora-ele-admin__switch-ui" aria-hidden="true"></span>
									</span>
								</label>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>

					<div class="nexora-ele-admin__footer">
						<p class="nexora-ele-admin__footer-note">
							<?php echo esc_html__( 'Changes apply after you save. Disabled widgets keep their page data; they only hide from the add panel and stop loading assets/output.', 'nexora-elementor' ); ?>
						</p>
						<button type="submit" class="nexora-ele-admin__save">
							<?php echo esc_html__( 'Save changes', 'nexora-elementor' ); ?>
						</button>
					</div>
				</form>
			</div>
			<?php
		}
	}
}
