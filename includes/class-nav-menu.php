<?php
/**
 * Mega menu checkbox on Appearance → Menus items.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_nav_menu_field' ) ) {
	/**
	 * Print the Mega menu checkbox on one menu item.
	 *
	 * @param int    $item_id Item id.
	 * @param object $item    Menu item.
	 * @param int    $depth   Depth.
	 * @param object $args    Menu args.
	 */
	function nexora_ph_nav_menu_field( $item_id, $item, $depth, $args ): void {
		unset( $item, $depth, $args );
		$item_id = (int) $item_id;
		$on      = function_exists( 'get_post_meta' ) && 'yes' === get_post_meta( $item_id, '_nexora_ph_mega', true );
		$id      = 'edit-menu-item-nexora-mega-' . $item_id;
		?>
		<p class="field-nexora-mega description description-wide">
			<label for="<?php echo esc_attr( $id ); ?>">
				<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" class="nexora-ph-mega" name="menu-item-nexora-mega[<?php echo esc_attr( (string) $item_id ); ?>]" value="yes" <?php checked( $on ); ?> />
				<?php esc_html_e( 'Mega menu', 'nexora-elementor' ); ?>
			</label>
			<span class="description"><?php esc_html_e( 'Opens the mega menu edited in the PH Nav widget. Saving keeps one checked item, and a top-level item uses it. A child item’s Description is the dropdown line. Enable Description under Screen Options.', 'nexora-elementor' ); ?></span>
		</p>
		<?php
	}
}

if ( ! function_exists( 'nexora_ph_nav_save_mega' ) ) {
	/**
	 * Keep one Mega menu checkbox for the menu being saved.
	 *
	 * @param int $menu_id Menu term id.
	 */
	function nexora_ph_nav_save_mega( $menu_id ): void {
		static $done = false;
		if ( $done || empty( $_POST['menu-item-db-id'] ) || ! is_array( $_POST['menu-item-db-id'] ) ) {
			return;
		}
		if ( function_exists( 'current_user_can' ) && ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}
		$done    = true;
		$menu_id = (int) $menu_id;
		$ordered = [];
		foreach ( $_POST['menu-item-db-id'] as $id ) {
			$id = (int) $id;
			if ( $id > 0 && nexora_ph_nav_item_belongs_to_menu( $id, $menu_id ) ) {
				$ordered[] = $id;
			}
		}
		$posted = [];
		if ( isset( $_POST['menu-item-nexora-mega'] ) && is_array( $_POST['menu-item-nexora-mega'] ) ) {
			foreach ( array_keys( $_POST['menu-item-nexora-mega'] ) as $id ) {
				$posted[] = (int) $id;
			}
		}
		$previous = [];
		foreach ( $ordered as $id ) {
			if ( function_exists( 'get_post_meta' ) && 'yes' === get_post_meta( $id, '_nexora_ph_mega', true ) ) {
				$previous[] = $id;
			}
		}
		if ( ! function_exists( 'nexora_ph_nav_mega_winner' ) ) {
			require_once NEXORA_ELE_PATH . 'widgets/ph-nav/data.php';
		}
		$winner = nexora_ph_nav_mega_winner( $ordered, $posted, $previous );
		foreach ( $ordered as $id ) {
			if ( $id === $winner ) {
				update_post_meta( $id, '_nexora_ph_mega', 'yes' );
				continue;
			}
			delete_post_meta( $id, '_nexora_ph_mega' );
		}
	}
}

if ( ! function_exists( 'nexora_ph_nav_item_belongs_to_menu' ) ) {
	/**
	 * Whether a nav menu item is assigned to this menu.
	 *
	 * @param int $item_id Item id.
	 * @param int $menu_id Menu term id.
	 */
	function nexora_ph_nav_item_belongs_to_menu( $item_id, $menu_id ): bool {
		if ( ! function_exists( 'wp_get_object_terms' ) ) {
			return true;
		}
		$terms = wp_get_object_terms( (int) $item_id, 'nav_menu', [ 'fields' => 'ids' ] );
		if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
			return false;
		}
		return in_array( (int) $menu_id, array_map( 'intval', $terms ), true );
	}
}

if ( ! function_exists( 'nexora_ph_nav_menu_footer' ) ) {
	/**
	 * Checking one Mega menu box clears the others in this menu.
	 */
	function nexora_ph_nav_menu_footer(): void {
		echo '<script>document.addEventListener("change",function(event){var box=event.target;if(!box||!box.matches||!box.matches("input.nexora-ph-mega")||!box.checked){return;}document.querySelectorAll("input.nexora-ph-mega").forEach(function(other){if(other!==box){other.checked=false;}});});</script>';
	}
}

if ( function_exists( 'add_action' ) ) {
	add_action( 'wp_nav_menu_item_custom_fields', 'nexora_ph_nav_menu_field', 10, 4 );
	add_action( 'wp_update_nav_menu', 'nexora_ph_nav_save_mega' );
	add_action( 'admin_footer-nav-menus.php', 'nexora_ph_nav_menu_footer' );
}
