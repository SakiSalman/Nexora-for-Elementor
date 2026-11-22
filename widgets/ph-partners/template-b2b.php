<?php
/**
 * PH Partners, "B2B badge cards" template style.
 *
 * @var array<string, mixed> $settings Widget settings from render.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/defaults-b2b.php';

if ( ! function_exists( 'nexora_ph_ce_setting' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-ce-lib/fields.php';
}

$d         = nexora_ph_partners_b2b_defaults();
$tag       = nexora_ph_ce_heading_tag( $settings, 'pr_heading_tag', $d['heading_tag'] );
$eyebrow   = nexora_ph_ce_text( $settings, 'pr_eyebrow', $d['eyebrow'] );
$heading   = nexora_ph_ce_text( $settings, 'pr_heading', $d['heading'] );
$highlight = nexora_ph_ce_text( $settings, 'pr_highlight', $d['highlight'] );
$desc      = nexora_ph_ce_text( $settings, 'pr_description', $d['description'] );
$cards     = nexora_ph_ce_rows( $settings, 'pr_cards', $d['cards'] );
?>
<!-- B2B12 OUR OFFICIAL PARTNERS -->
<section class="sec pr-sec">
<div class="wrap" style="display: flex; flex-direction: column; gap: 48px">
<div class="head center reveal" style="max-width: 900px">
<span class="tag blue"><?php echo $eyebrow; ?></span>
<<?php echo $tag; ?> class="h2"><?php echo $heading; ?> <br class="h2br"><span class="grad-text"><?php echo $highlight; ?></span></<?php echo $tag; ?>>
<p class="lead"><?php echo $desc; ?></p>
</div>
<div class="pr-grid hv-grid"><?php
foreach ( $cards as $row ) {
	$url = (string) ( ( isset( $row['card_image'] ) && is_array( $row['card_image'] ) && isset( $row['card_image']['url'] ) ) ? $row['card_image']['url'] : '' );
	// The shared renderer rewrites "assets/" in the whole markup; keep every image URL out of its reach.
	$src  = str_replace( 'assets/', 'assets&#47;', esc_url( $url ) );
	$chip = (string) nexora_ph_ce_setting( $row, 'card_chip', '' );
	echo '<article class="card hv-card pr-card">';
	if ( '' !== $src ) {
		echo '<img src="' . $src . '" alt="' . nexora_ph_ce_attr( $row, 'card_alt', '' ) . '" width="96" height="96">';
	}
	echo '<h3>' . nexora_ph_ce_text( $row, 'card_name', '' ) . '</h3>';
	if ( '' !== trim( $chip ) ) {
		echo '<span class="chip">' . esc_html( $chip ) . '</span>';
	}
	echo '<p class="muted">' . nexora_ph_ce_text( $row, 'card_summary', '' ) . '</p></article>';
}
?></div>
</div>
</section>
