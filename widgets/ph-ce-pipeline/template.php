<?php
/**
 * CE Pipeline markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-pipeline/markup.html byte for byte (parity gate).
 * The tab list state ({{ plTrack }}, pl0..plN) and the panel ({{ plCur }} / sc-for)
 * stay literal runtime interpolations; the stage panel copy rides the JSON bridge
 * (the parity tool strips that script tag).
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

if ( ! function_exists( 'nexora_ph_ce_pipeline_json' ) ) {
	require_once __DIR__ . '/data.php';
}

$ce_d          = nexora_ph_ce_pipeline_defaults();
$eyebrow       = nexora_ph_ce_text( $settings, 'pipeline_eyebrow', $ce_d['eyebrow'] );
$heading_tag   = nexora_ph_ce_heading_tag( $settings, 'pipeline_heading_tag', $ce_d['heading_tag'] );
$heading       = nexora_ph_ce_text( $settings, 'pipeline_heading', $ce_d['heading'] );
$highlight     = nexora_ph_ce_text( $settings, 'pipeline_highlight', $ce_d['highlight'] );
$lead          = nexora_ph_ce_text( $settings, 'pipeline_lead', $ce_d['lead'] );
$tablist_label = nexora_ph_ce_attr( $settings, 'pipeline_tablist_label', $ce_d['tablist_label'] );
$rows          = nexora_ph_ce_rows( $settings, 'pipeline_stages', $ce_d['stages'] );

// Panel copy for the runtime: one JSON entry per row, in row order.
$panel = array();
foreach ( $rows as $row ) {
	$panel[] = array(
		'title'  => (string) nexora_ph_ce_setting( $row, 'pipeline_stage_title', '' ),
		'body'   => (string) nexora_ph_ce_setting( $row, 'pipeline_stage_body', '' ),
		'points' => nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'pipeline_stage_points', '' ) ),
	);
}
$stage_count = count( $rows );

// The design CSS is hard-wired to 5 stages; any other count switches the track to
// the data-stages / --pl-n variable path (inactive at the design defaults).
$track_extra = '';
if ( 5 !== $stage_count ) {
	$track_extra = ' data-stages="' . esc_attr( (string) $stage_count ) . '" style="--pl-n: ' . esc_attr( (string) $stage_count ) . '"';
}
?>
<!-- CE5 B2B PIPELINE (Option A: interactive stages) -->
<section class="sec ivory" style="padding: 110px 0"><script type="application/json" class="ph-ce-pipeline-data"><?php echo nexora_ph_ce_pipeline_json( $panel ); ?></script>
<div class="wrap" style="display: flex; flex-direction: column; gap: 48px">
<div class="head center" style="max-width: 860px">
<span class="tag blue"><?php echo $eyebrow; ?></span>
<<?php echo $heading_tag; ?> class="h2"><?php echo $heading; ?> <span class="grad-text"><?php echo $highlight; ?></span></<?php echo $heading_tag; ?>>
<p class="lead"><?php echo $lead; ?></p>
</div>
<div class="{{ plTrack }}" role="tablist" aria-label="<?php echo $tablist_label; ?>"<?php echo $track_extra; ?>>
<div class="pl-line" aria-hidden="true"><span></span></div>
<?php foreach ( $rows as $i => $row ) { ?><button type="button" class="{{ pl<?php echo (int) $i; ?>.cls }}" aria-selected="{{ pl<?php echo (int) $i; ?>.sel }}" onClick="{{ pl<?php echo (int) $i; ?>.pick }}"><span class="pl-ic"><?php echo nexora_ph_ce_svg( nexora_ph_ce_setting( $row, 'pipeline_stage_icon', '' ) ); ?></span><span class="pl-lab"><?php echo nexora_ph_ce_text( $row, 'pipeline_stage_label', '' ); ?></span><span class="pl-sub"><?php echo nexora_ph_ce_text( $row, 'pipeline_stage_sub', '' ); ?></span></button><?php } ?>

</div>
<div class="pl-card glass" role="tabpanel">
<div class="pl-left">
<div class="pl-step"><span class="pl-num">{{ plCur.no }}</span><span class="pl-of">/ <?php echo sprintf( '%02d', $stage_count ); ?></span></div>
<h3>{{ plCur.title }}</h3>
<p class="muted">{{ plCur.body }}</p>
</div>
<ul class="pl-points">
<sc-for list="{{ plCur.points }}" as="pt" hint-placeholder-count="4">
<li><span class="pl-ck"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6L9 17l-5-5"></path></svg></span>{{ pt }}</li>
</sc-for>
</ul>
</div>
</div>
</section>
