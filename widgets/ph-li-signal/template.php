<?php
/**
 * LI Signal markup with editable holes.
 *
 * Defaults reproduce widgets/ph-li-signal/markup.html (parity gate).
 *
 * @var array<string, mixed> $settings Widget settings from render.php.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/defaults.php';

if ( ! function_exists( 'nexora_ph_ce_setting' ) ) {
	require_once NEXORA_ELE_PATH . 'widgets/ph-ce-lib/fields.php';
}

$d           = nexora_ph_li_signal_defaults();
$heading_tag = nexora_ph_ce_heading_tag( $settings, 'heading_tag', $d['heading_tag'] );
$eyebrow     = nexora_ph_ce_text( $settings, 'eyebrow', $d['eyebrow'] );
$heading     = nexora_ph_ce_text( $settings, 'heading', $d['heading'] );
$highlight   = nexora_ph_ce_text( $settings, 'highlight', $d['highlight'] );
$groups      = nexora_ph_ce_rows( $settings, 'groups_rows', $d['groups_rows'] );
$nodes       = nexora_ph_ce_rows( $settings, 'nodes_rows', $d['nodes_rows'] );
$core_src    = nexora_ph_ce_media( $settings, 'core_image', $d['core_image']['url'] );
$core_alt    = nexora_ph_ce_attr( $settings, 'core_alt', $d['core_alt'] );
$core_label  = nexora_ph_ce_text( $settings, 'core_label', $d['core_label'] );
$out_icon    = nexora_ph_ce_svg( nexora_ph_ce_setting( $settings, 'outcome_icon', $d['outcome_icon'] ) );
$out_title   = nexora_ph_ce_text( $settings, 'outcome_title', $d['outcome_title'] );
$out_body    = nexora_ph_ce_text( $settings, 'outcome_body', $d['outcome_body'] );

$left_groups  = array();
$right_groups = array();
foreach ( $groups as $row ) {
	$col = (string) nexora_ph_ce_setting( $row, 'signal_column', 'left' );
	if ( 'right' === $col ) {
		$right_groups[] = $row;
	} else {
		$left_groups[] = $row;
	}
}

$render_group = static function ( array $row ): void {
	$gkey = (string) nexora_ph_ce_setting( $row, 'signal_group', 'social' );
	if ( ! in_array( $gkey, array( 'social', 'intent', 'company' ), true ) ) {
		$gkey = 'social';
	}
	$title = nexora_ph_ce_text( $row, 'signal_title', '' );
	$sub   = nexora_ph_ce_text( $row, 'signal_subtitle', '' );
	$lines = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'signal_bullets', '' ) );
	echo '<div class="sc-side card hv-card" data-g="' . esc_attr( $gkey ) . '" tabindex="0"><h3>' . $title . '</h3><p class="muted">' . $sub . '</p><ul>';
	foreach ( $lines as $line ) {
		echo '<li><span class=check>✓</span>' . esc_html( $line ) . '</li>';
	}
	echo '</ul></div>';
};
?>
<!-- LI4 SIGNAL-BASED OUTREACH (signal radar) -->
<section class="sec sg-o sc-sec">
<div class="wrap" style="display: flex; flex-direction: column; gap: 40px">
<div class="head center reveal" style="max-width: 900px">
<span class="tag"><?php echo $eyebrow; ?></span>
<<?php echo $heading_tag; ?> class="h2"><?php echo $heading; ?> <br class="h2br"><span class="grad-text"><?php echo $highlight; ?></span></<?php echo $heading_tag; ?>>

</div>
<div class="sc-grid hv-grid">
<div class="sc-col">
<?php foreach ( $left_groups as $row ) { $render_group( $row ); } ?>
</div>
<div class="sc-radar" aria-hidden="true">
<span class="sc-ring r1"></span><span class="sc-ring r2"></span><span class="sc-ring r3"></span><span class="sc-sweep"></span>
<div class="sc-core"><span><img src="<?php echo $core_src; ?>" alt="<?php echo $core_alt; ?>" width="34" height="34"></span><b><?php echo $core_label; ?></b></div>
<div class="sc-orbit"><?php
foreach ( $nodes as $row ) {
	$gkey = (string) nexora_ph_ce_setting( $row, 'node_group', 'social' );
	if ( ! in_array( $gkey, array( 'social', 'intent', 'company' ), true ) ) {
		$gkey = 'social';
	}
	$angle = preg_replace( '/[^0-9.\-]/', '', (string) nexora_ph_ce_setting( $row, 'node_angle', '0' ) );
	$delay = preg_replace( '/[^0-9.\-]/', '', (string) nexora_ph_ce_setting( $row, 'node_delay', '0' ) );
	$label = nexora_ph_ce_text( $row, 'node_label', '' );
	$icon  = nexora_ph_ce_svg( nexora_ph_ce_setting( $row, 'node_icon', '' ) );
	echo '<div class="sc-node" data-g="' . esc_attr( $gkey ) . '" style="--a: ' . esc_attr( $angle ) . 'deg; --k: ' . esc_attr( $delay ) . '"><span class="sc-dot">' . $icon . '</span><b>' . $label . '</b></div>';
}
?></div>
</div>
<div class="sc-col">
<?php foreach ( $right_groups as $row ) { $render_group( $row ); } ?>
<div class="sc-side sc-out card hv-card"><span class="icon" style="width: 38px; height: 38px; border-radius: 11px"><?php echo $out_icon; ?></span><div><b><?php echo $out_title; ?></b><p><?php echo $out_body; ?></p></div></div>
</div>
</div>
</div>
</section>
