<?php
/**
 * CE Why markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-why/markup.html byte for byte (parity gate).
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

$ce_d        = nexora_ph_ce_why_defaults();
$heading_tag = nexora_ph_ce_heading_tag( $settings, 'why_heading_tag', $ce_d['heading_tag'] );
$eyebrow     = nexora_ph_ce_text( $settings, 'why_eyebrow', $ce_d['eyebrow'] );
$heading     = nexora_ph_ce_text( $settings, 'why_heading', $ce_d['heading'] );
$highlight   = nexora_ph_ce_text( $settings, 'why_highlight', $ce_d['highlight'] );
$rows        = nexora_ph_ce_rows( $settings, 'why_rows', $ce_d['rows'] );
?>
<!-- CE9 WHY CHOOSE US -->
<section class="sec ivory">
<div class="wrap" style="display: flex; flex-direction: column; gap: 64px">
<div class="head reveal" style="max-width: 860px">
<span class="tag" style="align-self: flex-start"><?php echo $eyebrow; ?></span>
<<?php echo $heading_tag; ?> class="h2"><?php echo $heading; ?> <span class="grad-text"><?php echo $highlight; ?></span></<?php echo $heading_tag; ?>>
</div>
<div class="g3 hv-grid" style="row-gap: 52px">
<?php foreach ( $rows as $i => $row ) { $row_tag = nexora_ph_ce_heading_tag( $row, 'why_tag', $ce_d['row_tag'] ); ?>
<div class="card reveal hv-card" style="position: relative; padding: 42px 28px 28px; display: flex; flex-direction: column; gap: 12px"><span class="icon-top"><?php echo nexora_ph_ce_svg( nexora_ph_ce_setting( $row, 'why_icon', '' ) ); ?></span><<?php echo $row_tag; ?> style="font-size: 20px; font-weight: 600"><?php echo nexora_ph_ce_text( $row, 'why_title', '' ); ?></<?php echo $row_tag; ?>><p class="muted" style="font-size: 15px; line-height: 1.65"><?php echo nexora_ph_ce_text( $row, 'why_description', '' ); ?></p></div>
<?php } ?>
</div>
</div>
</section>
