<?php
/**
 * Render Prospects Hive cases markup.
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

if ( ! function_exists( 'nexora_ph_cases_payload' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-cases/data.php';
}

if ( ! isset( $this ) || ! is_object( $this ) || ! method_exists( $this, 'get_settings_for_display' ) ) {
	return;
}
$settings = $this->get_settings_for_display();
if ( ! is_array( $settings ) ) {
	$settings = [];
}

$file = NEXORA_ELE_PATH . 'widgets/ph-cases/markup.html';
if ( ! is_readable( $file ) ) {
	return;
}
$html = file_get_contents( $file );
if ( ! is_string( $html ) || '' === $html ) {
	return;
}

if ( ! function_exists( 'nexora_ph_apply_content' ) ) {
	require_once NEXORA_ELE_PATH . 'includes/ph-content.php';
}

// Apply mapped header / chip / aria / fact labels while the JSON placeholder
// is still in the file so content-map byte offsets stay aligned with markup.html.
$html = nexora_ph_apply_content( $html, 'cases', $settings );
$html = str_replace( '__PH_CASES_JSON__', nexora_ph_cases_json( nexora_ph_cases_payload( $settings ) ), $html );

nexora_ph_render_section( 'cases', $uid, true, [], $html );
