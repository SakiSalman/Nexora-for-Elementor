<?php
/**
 * Render Prospects Hive video markup.
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

if ( ! function_exists( 'nexora_ph_apply_content' ) ) {
	require_once NEXORA_ELE_PATH . 'includes/ph-content.php';
}

if ( ! function_exists( 'nexora_ph_video_inject_player' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-video/player.php';
}

if ( ! isset( $this ) || ! is_object( $this ) || ! method_exists( $this, 'get_settings_for_display' ) ) {
	return;
}

$settings = $this->get_settings_for_display();
if ( ! is_array( $settings ) ) {
	$settings = [];
}

$file = NEXORA_ELE_PATH . 'widgets/ph-video/markup.html';
if ( ! is_readable( $file ) ) {
	return;
}

$html = file_get_contents( $file );
if ( ! is_string( $html ) || '' === $html ) {
	return;
}

$html = nexora_ph_apply_content( $html, 'video', $settings );
$html = nexora_ph_video_inject_player( $html, $settings );

nexora_ph_render_section( 'video', $uid, false, $settings, $html );
