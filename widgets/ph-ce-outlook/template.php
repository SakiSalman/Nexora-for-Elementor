<?php
/**
 * CE Outlook markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-outlook/markup.html byte for byte (parity gate).
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

$ce_d        = nexora_ph_ce_outlook_defaults();
$heading_tag = nexora_ph_ce_heading_tag( $settings, 'outlook_heading_tag', $ce_d['heading_tag'] );
$eyebrow     = nexora_ph_ce_text( $settings, 'outlook_eyebrow', $ce_d['eyebrow'] );
$heading     = nexora_ph_ce_text( $settings, 'outlook_heading', $ce_d['heading'] );
$highlight   = nexora_ph_ce_text( $settings, 'outlook_highlight', $ce_d['highlight'] );
$description = nexora_ph_ce_text( $settings, 'outlook_description', $ce_d['description'] );
$chart_label = nexora_ph_ce_text( $settings, 'outlook_chart_label', $ce_d['chart_label'] );
$legend_text = nexora_ph_ce_text( $settings, 'outlook_legend_text', $ce_d['legend_text'] );
$phases      = nexora_ph_ce_rows( $settings, 'outlook_phases', $ce_d['phases'] );
?>
<!-- CE7 90-DAY OUTLOOK (growth chart) -->
<section class="sec ivory">
<div class="wrap" style="display: flex; flex-direction: column; gap: 52px">
<div class="split reveal">
<div class="head"><span class="tag blue"><?php echo $eyebrow; ?></span><<?php echo $heading_tag; ?> class="h2"><?php echo $heading; ?> <span class="grad-text"><?php echo $highlight; ?></span></<?php echo $heading_tag; ?>></div>
<p class="lead"><?php echo $description; ?></p>
</div>
<div class="glass ce-growth reveal">
<div class="ce-g-top"><span class="ce-lab"><?php echo $chart_label; ?></span><span class="ce-g-leg"><i></i><?php echo $legend_text; ?></span></div>
<div class="ce-g-grid">
<?php foreach ( $phases as $i => $row ) {
	$phase_on   = ! empty( $row['outlook_phase_on'] ) ? ' on' : '';
	$bar_style  = 'height: ' . esc_attr( (string) $row['outlook_bar_height'] ) . '%; animation-delay: ' . sprintf( '%.2f', $i * 0.18 ) . 's';
	$bar_label  = esc_html( $row['outlook_bar_label'] );
	$tag        = nexora_ph_ce_heading_tag( $row, 'outlook_title_tag', 'h3' );
	$title      = esc_html( $row['outlook_title'] );
	$phase_desc = esc_html( $row['outlook_description'] );
?><div class="ce-g-col<?php echo $phase_on; ?>"><div class="ce-g-barwrap"><span class="ce-g-bar" style="<?php echo $bar_style; ?>"><em><?php echo $bar_label; ?></em></span></div><div class="ce-g-txt"><<?php echo $tag; ?>><?php echo $title; ?></<?php echo $tag; ?>><p class="muted"><?php echo $phase_desc; ?></p></div></div>
<?php } ?>
</div>
</div>
</div>
</section>
