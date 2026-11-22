<?php
/**
 * CE Problem markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-problem/markup.html byte for byte (parity gate);
 * the golden is the design slice of CE2 PROBLEM, unchanged.
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

$ce_d           = nexora_ph_ce_problem_defaults();
$ce_tag         = nexora_ph_ce_heading_tag( $settings, 'problem_heading_tag', $ce_d['heading_tag'] );
$ce_eyebrow     = nexora_ph_ce_text( $settings, 'problem_eyebrow', $ce_d['eyebrow'] );
$ce_heading     = nexora_ph_ce_text( $settings, 'problem_heading', $ce_d['heading'] );
$ce_highlight   = nexora_ph_ce_text( $settings, 'problem_highlight', $ce_d['highlight'] );
$ce_desc        = nexora_ph_ce_text( $settings, 'problem_description', $ce_d['description'] );
$ce_chart_label = nexora_ph_ce_text( $settings, 'problem_chart_label', $ce_d['chart_label'] );
$ce_leaks_label = nexora_ph_ce_text( $settings, 'problem_leaks_label', $ce_d['leaks_label'] );
$ce_footnote    = nexora_ph_ce_text( $settings, 'problem_footnote', $ce_d['footnote'] );
$ce_items       = nexora_ph_ce_rows( $settings, 'problem_rows', $ce_d['rows'] );
$ce_bars        = nexora_ph_ce_rows( $settings, 'problem_chart_rows', $ce_d['chart_rows'] );
$ce_x_icon      = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"></path></svg>';
?>
<!-- CE2 PROBLEM (sticky title card + scrolling timeline) -->
<section class="sec pt-sec" style="background: #FFFFFF">
<div class="head center reveal pt-head">
<span class="tag"><?php echo $ce_eyebrow; ?></span>
<<?php echo $ce_tag; ?> class="h2"><?php echo $ce_heading; ?> <span class="grad-text"><?php echo $ce_highlight; ?></span></<?php echo $ce_tag; ?>>
<p class="lead"><?php echo $ce_desc; ?></p>
</div>
<div class="wrap pt-wrap">
<div class="pt-left">
<div class="mid pt-card reveal">
<div class="gridlines" style="background-image: linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px)"></div>
<div class="pt-chart">
<div class="ce-funnel-top"><span class="ce-lab" style="color: #A9B6C2"><?php echo $ce_chart_label; ?></span><span class="ce-warn"><i></i><?php echo $ce_leaks_label; ?></span></div>
<div class="ce-frows">
<?php
foreach ( $ce_bars as $ce_i => $ce_bar ) {
	$ce_w     = (float) nexora_ph_ce_setting( $ce_bar, 'bar_width', 50 );
	$ce_w     = max( 0, min( 100, $ce_w ) );
	$ce_w_txt = rtrim( rtrim( number_format( $ce_w, 2, '.', '' ), '0' ), '.' );
	$ce_delay = number_format( $ce_i * 0.15, 2, '.', '' );
	$ce_leak  = (string) nexora_ph_ce_setting( $ce_bar, 'leak_label', '' );
	echo '<div class="ce-frow"><div class="ce-ftrack"><span class="ce-fbar" style="width: ' . esc_attr( $ce_w_txt ) . '%; animation-delay: ' . esc_attr( $ce_delay ) . 's"><b>' . nexora_ph_ce_text( $ce_bar, 'bar_label', '' ) . '</b></span></div>';
	if ( '' !== trim( $ce_leak ) ) {
		echo '<span class="ce-leak">' . $ce_x_icon . esc_html( $ce_leak ) . '</span>';
	} else {
		echo '<span></span>';
	}
	echo '</div>' . "\n";
}
?></div>
<p class="pt-fnote"><?php echo $ce_footnote; ?></p>
</div>
</div>
</div>
<div class="pt-tl">
<div class="pt-line" aria-hidden="true"><span class="pt-fill"></span></div>
<?php foreach ( $ce_items as $row ) { ?><div class="pt-item"><span class="pt-dot" aria-hidden="true"></span><span class="pt-num"><?php echo esc_html( $row['problem_num'] ); ?></span><h3><?php echo esc_html( $row['problem_title'] ); ?></h3><p><?php echo esc_html( $row['problem_body'] ); ?></p></div><?php } ?><?php echo "\n"; ?>
</div>
</div>
</section>
