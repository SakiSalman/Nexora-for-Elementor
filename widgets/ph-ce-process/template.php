<?php
/**
 * CE Process markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-process/markup.html byte for byte (parity gate).
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

$ce_d         = nexora_ph_ce_process_defaults();
$ce_vd        = $ce_d['visual_defaults'];
$ce_tag       = nexora_ph_ce_heading_tag( $settings, 'process_heading_tag', $ce_d['heading_tag'] );
$ce_eyebrow   = nexora_ph_ce_text( $settings, 'process_eyebrow', $ce_d['eyebrow'] );
$ce_heading   = nexora_ph_ce_text( $settings, 'process_heading', $ce_d['heading'] );
$ce_highlight = nexora_ph_ce_text( $settings, 'process_highlight', $ce_d['highlight'] );
$ce_desc      = nexora_ph_ce_text( $settings, 'process_description', $ce_d['description'] );
$ce_steps     = nexora_ph_ce_rows( $settings, 'process_steps', $ce_d['steps'] );

/*
 * The funnel visual carries an SVG gradient id (cevg). Defaults use every visual
 * once, so the original ids ship untouched; when the same visual is picked on
 * more than one row, its internal ids get a -{row index} suffix so two copies
 * never collide in the document.
 */
$ce_vis_used = array();
foreach ( $ce_steps as $ce_count_row ) {
	$ce_v            = (string) $ce_count_row['process_visual'];
	$ce_vis_used[ $ce_v ] = ( isset( $ce_vis_used[ $ce_v ] ) ? $ce_vis_used[ $ce_v ] : 0 ) + 1;
}
?>
<!-- CE6 PROCESS (stacked cards, sticky on scroll) -->
<section id="process" class="sec stk-sec">
<div class="wrap" style="display: flex; flex-direction: column; gap: 48px">
<div class="head center reveal" style="max-width: 900px"><span class="tag"><?php echo $ce_eyebrow; ?></span><<?php echo $ce_tag; ?> class="h2"><?php echo $ce_heading; ?> <span class="grad-text"><?php echo $ce_highlight; ?></span></<?php echo $ce_tag; ?>><p class="lead"><?php echo $ce_desc; ?></p></div>
<div class="stk">
<?php
foreach ( $ce_steps as $i => $row ) {
	$ce_num       = sprintf( '%02d', $i + 1 );
	$ce_label     = esc_html( (string) $row['process_step_label'] );
	$ce_row_tag   = nexora_ph_ce_heading_tag( $row, 'process_step_title_tag', $ce_d['steps'][0]['process_step_title_tag'] );
	$ce_title     = esc_html( (string) $row['process_step_title'] );
	$ce_rdesc     = esc_html( (string) $row['process_step_desc'] );
	$ce_tools     = esc_html( (string) $row['process_step_tools'] );
	$ce_timeline  = esc_html( (string) $row['process_step_timeline'] );
	$ce_out_lab   = esc_html( (string) $row['process_step_outcome_label'] );
	$ce_outcome   = esc_html( (string) $row['process_step_outcome'] );
	$ce_step_svg  = nexora_ph_ce_svg( $row['process_step_icon'] );
	$ce_bg_row = nexora_ph_ce_attr( $row, 'process_card_bg', '' );
	$ce_bg_des = isset( $ce_d['steps'][ $i ]['process_card_bg'] ) ? (string) $ce_d['steps'][ $i ]['process_card_bg'] : '';
	// Grouped Style → Colors pickers; fallback bytes are the defaults.php design colors.
	$ce_bg_pick = [
		'var(--ph-ce-process-card-1, #D9EAF7)',
		'var(--ph-ce-process-card-2, #FFE3D1)',
		'var(--ph-ce-process-card-3, #FFF0C9)',
		'var(--ph-ce-process-card-4, #DDF1E6)',
		'var(--ph-ce-process-card-5, #FFD6C2)',
	];
	if ( '' !== $ce_bg_row && $ce_bg_row !== $ce_bg_des ) {
		$ce_bg = $ce_bg_row; // per-row override wins when set
	} elseif ( $i < 5 && '' !== $ce_bg_des ) {
		$ce_bg = $ce_bg_pick[ $i ]; // grouped picker
	} else {
		$ce_bg = '' !== $ce_bg_des ? $ce_bg_des : (string) $ce_d['steps'][0]['process_card_bg'];
	}
	$ce_visual    = (string) $row['process_visual'];
	$ce_dup       = isset( $ce_vis_used[ $ce_visual ] ) && $ce_vis_used[ $ce_visual ] > 1;
	$ce_vid       = $ce_dup ? '-' . $i : '';

	$ce_f1_svg    = nexora_ph_ce_svg( $row['process_f1_icon'] );
	$ce_f1_tag    = nexora_ph_ce_heading_tag( $row, 'process_f1_title_tag', $ce_d['steps'][0]['process_f1_title_tag'] );
	$ce_f1_title  = esc_html( (string) $row['process_f1_title'] );
	$ce_f1_desc   = esc_html( (string) $row['process_f1_desc'] );
	$ce_f2_svg    = nexora_ph_ce_svg( $row['process_f2_icon'] );
	$ce_f2_tag    = nexora_ph_ce_heading_tag( $row, 'process_f2_title_tag', $ce_d['steps'][0]['process_f2_title_tag'] );
	$ce_f2_title  = esc_html( (string) $row['process_f2_title'] );
	$ce_f2_desc   = esc_html( (string) $row['process_f2_desc'] );
	$ce_f3_svg    = nexora_ph_ce_svg( $row['process_f3_icon'] );
	$ce_f3_tag    = nexora_ph_ce_heading_tag( $row, 'process_f3_title_tag', $ce_d['steps'][0]['process_f3_title_tag'] );
	$ce_f3_title  = esc_html( (string) $row['process_f3_title'] );
	$ce_f3_desc   = esc_html( (string) $row['process_f3_desc'] );
	?>
<article class="stk-card" style="--bg: <?php echo $ce_bg; ?>; --i: <?php echo $i; ?>">
<div class="stk-left">
<span class="stk-lab"><em><?php echo $ce_num; ?></em><?php echo $ce_label; ?></span>
<<?php echo $ce_row_tag; ?>><?php echo $ce_title; ?></<?php echo $ce_row_tag; ?>>
<p><?php echo $ce_rdesc; ?></p>
<div class="stk-meta"><div><span>Tools</span><b><?php echo $ce_tools; ?></b></div><div><span>Timeline</span><b><?php echo $ce_timeline; ?></b></div></div>
<div class="stk-out"><span class="icon" style="width: 44px; height: 44px; border-radius: 13px"><?php echo $ce_step_svg; ?></span><div><small><?php echo $ce_out_lab; ?></small><b><?php echo $ce_outcome; ?></b></div></div>
</div>
<div class="stk-panel">
<?php
	if ( 'v1' === $ce_visual ) {
		$ce_market_lab = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v1_market_label', $ce_vd['v1']['process_v1_market_label'] ) );
		$ce_market_val = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v1_market_value', $ce_vd['v1']['process_v1_market_value'] ) );
		$ce_market_unit = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v1_market_unit', $ce_vd['v1']['process_v1_market_unit'] ) );
		$ce_chips      = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v1_chips', $ce_vd['v1']['process_v1_chips'] ) );
		$ce_list_lab   = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v1_list_label', $ce_vd['v1']['process_v1_list_label'] ) );
		$ce_list_val   = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v1_list_value', $ce_vd['v1']['process_v1_list_value'] ) );
		$ce_list_unit  = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v1_list_unit', $ce_vd['v1']['process_v1_list_unit'] ) );
		?>
<div class="fw-vis ce-vis"><div class="ce-v1">
<div class="ce-v1-col"><span class="ce-vl"><?php echo $ce_market_lab; ?></span><b><?php echo $ce_market_val; ?></b><small><?php echo $ce_market_unit; ?></small></div>
<div class="ce-v1-mid"><?php foreach ( $ce_chips as $ci => $ce_chip ) { $ce_delay = rtrim( rtrim( sprintf( '%.1f', 0.6 * $ci ), '0' ), '.' ); if ( 0 === strpos( $ce_delay, '0.' ) ) { $ce_delay = substr( $ce_delay, 1 ); } ?><span class="fw-chip" style="position: static; animation-delay: <?php echo $ce_delay; ?>s"><?php echo esc_html( $ce_chip ); ?></span><?php } ?></div>
<div class="ce-v1-col hot"><span class="ce-vl"><?php echo $ce_list_lab; ?></span><b><?php echo $ce_list_val; ?></b><small><?php echo $ce_list_unit; ?></small></div>
</div><svg class="ce-v1-flow" viewBox="0 0 560 250" preserveAspectRatio="none" aria-hidden="true"><path class="fw-dash" d="M150 125 H410" stroke="url(#cevg<?php echo $ce_vid; ?>)" stroke-width="2.5" fill="none"></path><defs><linearGradient id="cevg<?php echo $ce_vid; ?>" x1="0" x2="1"><stop offset="0" stop-color="#5FB0E8"></stop><stop offset="1" stop-color="#F15E22"></stop></linearGradient></defs></svg></div>
<?php	} elseif ( 'v2' === $ce_visual ) {
		$ce_auth = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v2_auth', $ce_vd['v2']['process_v2_auth'] ) );
		$ce_mbox = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v2_rows', $ce_vd['v2']['process_v2_rows'] ) );
		?>
<div class="fw-vis ce-vis"><div class="ce-v2">
<div class="ce-v2-auth"><?php foreach ( $ce_auth as $ce_auth_chip ) { ?><span class="fw-sync"><?php echo esc_html( $ce_auth_chip ); ?></span><?php } ?></div>
<?php
		foreach ( $ce_mbox as $ri => $ce_mrow ) {
			$ce_parts  = explode( '|', $ce_mrow, 2 );
			$ce_addr   = trim( $ce_parts[0] );
			$ce_stat   = isset( $ce_parts[1] ) ? trim( $ce_parts[1] ) : '';
			$ce_ready  = 'Ready' === $ce_stat;
			$ce_dur    = (string) ( 6 + 1.5 * $ri );
			?>
<div class="ce-v2-row<?php echo $ce_ready ? ' ok' : ''; ?>"><span><?php echo esc_html( $ce_addr ); ?></span><div class="vbar"><?php echo $ce_ready ? '<span style="animation: none; width: 100%"></span>' : '<span style="animation-duration: ' . $ce_dur . 's"></span>'; ?></div><em><?php echo esc_html( $ce_stat ); ?></em></div>
<?php		} ?>
</div></div>
<?php	} elseif ( 'v3' === $ce_visual ) {
		$ce_counter = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v3_counter', $ce_vd['v3']['process_v3_counter'] ) );
		$ce_subject = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v3_subject', $ce_vd['v3']['process_v3_subject'] ) );
		$ce_body    = nexora_ph_ce_mark( nexora_ph_ce_setting( $row, 'process_v3_body', $ce_vd['v3']['process_v3_body'] ) );
		$ce_body2   = nexora_ph_ce_mark( nexora_ph_ce_setting( $row, 'process_v3_body2', $ce_vd['v3']['process_v3_body2'] ) );
		$ce_seq     = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v3_steps', $ce_vd['v3']['process_v3_steps'] ) );
		?>
<div class="fw-vis ce-vis"><div class="ce-v3">
<div class="ce-v3-mail"><div class="ce-v3-subj"><span class="ce-vl" style="color: #56626D"><?php echo $ce_counter; ?></span><span>Subject: <b><?php echo $ce_subject; ?></b></span></div><p><?php echo $ce_body; ?></p><p style="margin-top: 8px"><?php echo $ce_body2; ?></p></div>
<div class="ce-v3-seq"><?php foreach ( $ce_seq as $si => $ce_step ) { $ce_sp = explode( '|', $ce_step, 2 ); ?><span<?php echo 0 === $si ? ' class="on"' : ''; ?>><?php echo esc_html( trim( $ce_sp[0] ) ); ?><small><?php echo esc_html( isset( $ce_sp[1] ) ? trim( $ce_sp[1] ) : '' ); ?></small></span><?php } ?></div>
</div></div>
<?php	} elseif ( 'v4' === $ce_visual ) {
		$ce_cols      = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v4_columns', $ce_vd['v4']['process_v4_columns'] ) );
		$ce_mover     = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v4_mover_text', $ce_vd['v4']['process_v4_mover_text'] ) );
		$ce_mover_tag = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v4_mover_tag', $ce_vd['v4']['process_v4_mover_tag'] ) );
		$ce_dots      = array( '#8FA0AF', '#5FB0E8', '#F15E22' );
		$ce_last_dot  = $ce_dots[2];
		?>
<div class="fw-vis fw-vis-board" style="padding: 22px"><div class="fw-board">
<?php
		foreach ( $ce_cols as $ci => $ce_col ) {
			$ce_seg       = array_map( 'trim', explode( '|', $ce_col ) );
			$ce_name      = array_shift( $ce_seg );
			$ce_dot       = $ce_dots[ $ci % 3 ];
			$ce_last_col  = ( $ci === count( $ce_cols ) - 1 );
			$ce_last_dot  = $ce_dot;
			$ce_card_last = count( $ce_seg ) - 1;
			?>
<div class="fw-col"><span class="fw-colh"><span class="fw-dot" style="background: <?php echo $ce_dot; ?>"></span><?php echo esc_html( $ce_name ); ?></span><?php foreach ( $ce_seg as $gi => $ce_card ) { ?><div class="fw-mini"<?php if ( $ce_last_col && $gi === $ce_card_last ) { ?> style="background: linear-gradient(145deg, #FF8A4C, #F15E22); color: #0B1620; border-color: transparent"<?php } ?>><?php echo esc_html( $ce_card ); ?></div><?php } ?></div>
<?php		} ?>
<div class="fw-mover"><span class="fw-dot" style="background: <?php echo $ce_last_dot; ?>"></span><?php echo $ce_mover; ?><span class="fw-tag"><?php echo $ce_mover_tag; ?></span></div>
</div></div>
<?php	} else {
		$ce_bars      = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v5_bars', $ce_vd['v5']['process_v5_bars'] ) );
		$ce_labels    = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v5_labels', $ce_vd['v5']['process_v5_labels'] ) );
		$ce_side_lab  = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v5_label', $ce_vd['v5']['process_v5_label'] ) );
		$ce_side_val  = esc_html( (string) nexora_ph_ce_setting( $row, 'process_v5_value', $ce_vd['v5']['process_v5_value'] ) );
		$ce_side_chips = nexora_ph_ce_lines( nexora_ph_ce_setting( $row, 'process_v5_chips', $ce_vd['v5']['process_v5_chips'] ) );
		?>
<div class="fw-vis ce-vis"><div class="ce-v5">
<div class="ce-v5-chart"><div class="ce-v5-bars"><?php foreach ( $ce_bars as $ce_h ) { ?><span style="height: <?php echo esc_attr( preg_replace( '/[^0-9.]/', '', $ce_h ) ); ?>%"></span><?php } ?></div><div class="ce-v5-x"><?php foreach ( $ce_labels as $ce_l ) { ?><small><?php echo esc_html( $ce_l ); ?></small><?php } ?></div></div>
<div class="ce-v5-side"><span class="ce-vl"><?php echo $ce_side_lab; ?></span><b class="grad-text"><?php echo $ce_side_val; ?></b><?php foreach ( $ce_side_chips as $ce_sc ) { ?><span class="fw-sync"><?php echo esc_html( $ce_sc ); ?></span><?php } ?></div>
</div></div>
<?php } ?>
<div class="g3 fw-cards"><div class="fw-card"><span class="fw-ic"><?php echo $ce_f1_svg; ?></span><<?php echo $ce_f1_tag; ?>><?php echo $ce_f1_title; ?></<?php echo $ce_f1_tag; ?>><p><?php echo $ce_f1_desc; ?></p></div><div class="fw-card"><span class="fw-ic"><?php echo $ce_f2_svg; ?></span><<?php echo $ce_f2_tag; ?>><?php echo $ce_f2_title; ?></<?php echo $ce_f2_tag; ?>><p><?php echo $ce_f2_desc; ?></p></div><div class="fw-card"><span class="fw-ic"><?php echo $ce_f3_svg; ?></span><<?php echo $ce_f3_tag; ?>><?php echo $ce_f3_title; ?></<?php echo $ce_f3_tag; ?>><p><?php echo $ce_f3_desc; ?></p></div></div>
</div>
</article>
<?php } ?>
</div>
</div>
</section>
