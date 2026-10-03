<?php
/**
 * Render Prospects Hive contact markup.
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

$file = NEXORA_ELE_PATH . 'widgets/ph-contact/markup.html';
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

$html = nexora_ph_apply_content( $html, 'contact', $settings );

$shortcode = isset( $settings['contact_form_shortcode'] ) ? trim( (string) $settings['contact_form_shortcode'] ) : '';
$form_html = '';
if ( '' !== $shortcode ) {
	$form_html = do_shortcode( $shortcode );
	$form_html = is_string( $form_html ) ? $form_html : '';
}
if ( '' === trim( wp_strip_all_tags( $form_html ) ) ) {
	$form_html = '<p class="ct-form-placeholder">' . esc_html__( 'Add a Contact Form 7 shortcode in the Contact form section.', 'nexora-elementor' ) . '</p>';
}

$html = str_replace( '__PH_CONTACT_FORM__', $form_html, $html );

nexora_ph_render_section( 'contact', $uid, false, [], $html );
