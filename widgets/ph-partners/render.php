<?php
/**
 * Render Prospects Hive partners markup.
 *
 * @var string $uid Element id suffix.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $uid ) || ! is_string( $uid ) ) {
	return;
}

if ( ! function_exists( 'nexora_ph_render_section' ) ) {
	require_once NEXORA_ELE_PATH . 'includes/ph-markup.php';
}

if ( ! isset( $this ) || ! is_object( $this ) || ! method_exists( $this, 'get_settings_for_display' ) ) {
	return;
}
$settings = $this->get_settings_for_display();
if ( ! is_array( $settings ) ) {
	$settings = [];
}
if ( isset( $settings['partners_template'] ) && 'b2b-cards' === $settings['partners_template'] ) {
	// B2B badge cards style: own markup template, same wrapper and stylesheet.
	if ( ! function_exists( 'nexora_ph_ce_setting' ) ) {
		require_once NEXORA_ELE_PATH . 'widgets/ph-ce-lib/fields.php';
	}
	ob_start();
	include NEXORA_ELE_PATH . 'widgets/ph-partners/template-b2b.php';
	$b2b_html = (string) ob_get_clean();
	nexora_ph_render_section( 'partners', $uid, false, [], $b2b_html );
	return;
}

nexora_ph_render_section( 'partners', $uid, false, $settings );
