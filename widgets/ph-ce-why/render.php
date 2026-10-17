<?php
/**
 * Render Cold Email why markup through the phase 2 template.
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
if ( ! function_exists( 'nexora_ph_ce_setting' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-ce-lib/fields.php';
}

$settings = [];
if ( isset( $this ) && is_object( $this ) && method_exists( $this, 'get_settings_for_display' ) ) {
	$settings = $this->get_settings_for_display();
}
if ( ! is_array( $settings ) ) {
	$settings = [];
}

ob_start();
include NEXORA_ELE_PATH . 'widgets/ph-ce-why/template.php';
$html = (string) ob_get_clean();

nexora_ph_render_section( 'ce-why', $uid, false, [], $html );
