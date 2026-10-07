<?php
/**
 * Render Prospects Hive footer markup.
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

$file = NEXORA_ELE_PATH . 'widgets/ph-footer/markup.html';
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

$html = nexora_ph_apply_content( $html, 'footer', $settings );

$shortcode = isset( $settings['footer_newsletter_shortcode'] ) ? trim( (string) $settings['footer_newsletter_shortcode'] ) : '';
$shortcode = html_entity_decode( $shortcode, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
$shortcode = preg_replace( '/[\x{201C}\x{201D}\x{2018}\x{2019}]/u', '"', $shortcode );
$shortcode = is_string( $shortcode ) ? trim( $shortcode ) : '';
if ( '' !== $shortcode && preg_match( '/\[contact-form-7\b[^\]]*\]/i', $shortcode, $shortcode_match ) ) {
	$shortcode = $shortcode_match[0];
}

$form_html = '';
if ( '' !== $shortcode ) {
	$form_html = do_shortcode( $shortcode );
	$form_html = is_string( $form_html ) ? $form_html : '';
}
// Input-only CF7 forms have no text nodes — do not use wp_strip_all_tags().
$has_form = '' !== $form_html && (
	false !== strpos( $form_html, 'wpcf7' )
	|| (bool) preg_match( '/<(?:input|textarea|button|select)\b/i', $form_html )
);
if ( ! $has_form ) {
	if ( '' !== $shortcode ) {
		$form_html = '<p class="ft-news-placeholder">' . esc_html__( 'Newsletter shortcode did not render. Check the form ID and that Contact Form 7 is active.', 'nexora-elementor' ) . '</p>';
	} else {
		$form_html = '<p class="ft-news-placeholder">' . esc_html__( 'Add a Contact Form 7 shortcode in the Newsletter form section.', 'nexora-elementor' ) . '</p>';
	}
}

$html = str_replace( '__PH_FOOTER_NEWSLETTER__', $form_html, $html );

nexora_ph_render_section( 'footer', $uid, false, [], $html );
