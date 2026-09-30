<?php
/**
 * Render Prospects Hive pricing markup.
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

if ( ! function_exists( 'nexora_ph_pricing_payload' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-pricing/data.php';
}

if ( ! isset( $this ) || ! is_object( $this ) || ! method_exists( $this, 'get_settings_for_display' ) ) {
	return;
}
$settings = $this->get_settings_for_display();
if ( ! is_array( $settings ) ) {
	$settings = [];
}

$file = NEXORA_ELE_PATH . 'widgets/ph-pricing/markup.html';
if ( ! is_readable( $file ) ) {
	return;
}
$html = file_get_contents( $file );
if ( ! is_string( $html ) || '' === $html ) {
	return;
}

$html = str_replace( '__PH_PRICING_JSON__', nexora_ph_pricing_json( nexora_ph_pricing_payload( $settings ) ), $html );
nexora_ph_render_section( 'pricing', $uid, true, [], $html );
