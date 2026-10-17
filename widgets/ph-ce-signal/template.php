<?php
/**
 * CE Signal markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-signal/markup.html byte for byte (parity gate).
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

$ce_d           = nexora_ph_ce_signal_defaults();
$ce_tag         = nexora_ph_ce_heading_tag( $settings, 'signal_heading_tag', $ce_d['heading_tag'] );
$ce_eyebrow     = nexora_ph_ce_text( $settings, 'signal_eyebrow', $ce_d['eyebrow'] );
$ce_heading     = nexora_ph_ce_text( $settings, 'signal_heading', $ce_d['heading'] );
$ce_highlight   = nexora_ph_ce_text( $settings, 'signal_highlight', $ce_d['highlight'] );
$ce_desc        = nexora_ph_ce_text( $settings, 'signal_description', $ce_d['description'] );
$ce_acct_name   = nexora_ph_ce_text( $settings, 'signal_account_name', $ce_d['account_name'] );
$ce_acct_label  = nexora_ph_ce_text( $settings, 'signal_account_label', $ce_d['account_label'] );
$ce_acct_icon   = nexora_ph_ce_attr( $settings, 'signal_account_icon', $ce_d['account_icon'] );
$ce_sig_label   = nexora_ph_ce_text( $settings, 'signal_signals_label', $ce_d['signals_label'] );
$ce_signals     = nexora_ph_ce_rows( $settings, 'signal_signals_rows', $ce_d['signals_rows'] );
$ce_subj_label  = nexora_ph_ce_text( $settings, 'signal_subject_label', $ce_d['subject_label'] );
$ce_subject     = nexora_ph_ce_text( $settings, 'signal_subject', $ce_d['subject'] );
$ce_paragraphs  = nexora_ph_ce_rows( $settings, 'signal_paragraphs_rows', $ce_d['paragraphs_rows'] );
$ce_signature   = implode( '<br>', array_map( 'esc_html', nexora_ph_ce_lines( nexora_ph_ce_setting( $settings, 'signal_signature', $ce_d['signature'] ) ) ) );
$ce_stats       = nexora_ph_ce_rows( $settings, 'signal_stats_rows', $ce_d['stats_rows'] );
$ce_button_text = nexora_ph_ce_text( $settings, 'signal_button_text', $ce_d['button_text'] );
$ce_button      = nexora_ph_ce_link( $settings, 'signal_button_link', $ce_d['button_url'] );

/*
 * Marks past the fourth in one paragraph keep the cesig/cemark 2s cadence inline
 * (CSS nth-of-type covers the first four per paragraph at the design count).
 */
$ce_mark_delays = static function ( string $html ): string {
	$seen = 0;
	$out  = preg_replace_callback(
		'/<mark>/',
		static function ( array $match ) use ( &$seen ): string {
			++$seen;

			return $seen > 4 ? '<mark style="animation-delay:' . ( 2 * ( $seen - 1 ) ) . 's">' : $match[0];
		},
		$html
	);

	return is_string( $out ) ? $out : $html;
};
?>
<!-- CE10 SIGNAL-BASED MESSAGE -->
<section class="sec" style="background: radial-gradient(760px 460px at 50% 30%, rgba(241,94,34,.12), transparent 65%), radial-gradient(700px 420px at 0% 100%, rgba(35,116,172,.10), transparent 62%), #FFFFFF">
<div class="gridlines"></div>
<div class="wrap" style="position: relative; display: flex; flex-direction: column; gap: 56px">
<div class="head center reveal" style="max-width: 820px">
<span class="tag"><?php echo $ce_eyebrow; ?></span>
<<?php echo $ce_tag; ?> class="h2"><?php echo $ce_heading; ?> <span class="grad-text"><?php echo $ce_highlight; ?></span></<?php echo $ce_tag; ?>>
<p class="lead"><?php echo $ce_desc; ?></p>
</div>
<div class="ce-sig-grid">
<div class="glass ce-acct reveal">
<div class="ce-acct-h"><span class="icon-o" style="width: 42px; height: 42px; border-radius: 12px"><?php echo nexora_ph_ce_svg( $ce_acct_icon ); ?></span><div><strong><?php echo $ce_acct_name; ?></strong><small><?php echo $ce_acct_label; ?></small></div></div>
<span class="ce-lab"><?php echo $ce_sig_label; ?></span>
<div class="ce-sigs"><?php foreach ( $ce_signals as $i => $row ) { $ce_delay = ( $i >= 4 ) ? ' style="animation-delay:' . ( 2 * $i ) . 's"' : ''; ?><span<?php echo $ce_delay; ?>><?php echo esc_html( $row['signal_text'] ); ?></span><?php } ?></div>
</div>
<div class="ce-mail reveal">
<div class="ce-mail-h"><span><?php echo $ce_subj_label; ?></span><strong style="font-weight: 500"><?php echo $ce_subject; ?></strong></div>
<div class="ce-mail-b">
<?php foreach ( $ce_paragraphs as $row ) { ?><p><?php echo $ce_mark_delays( nexora_ph_ce_mark( $row['signal_paragraph_text'] ) ); ?></p>
<?php } ?><p><?php echo $ce_signature; ?></p>
</div>
</div>
<div class="ce-stats reveal" style="display: flex; flex-direction: column; gap: 16px">
<?php foreach ( $ce_stats as $i => $row ) { ?><div class="glass ce-stat"><span class="<?php echo 0 === $i ? 'icon' : 'icon-b'; ?>" style="width: 42px; height: 42px; border-radius: 12px"><?php echo nexora_ph_ce_svg( $row['signal_stat_icon'] ); ?></span><b class="grad-text"><?php echo esc_html( $row['signal_stat_value'] ); ?></b><small><?php echo esc_html( $row['signal_stat_label'] ); ?></small></div>
<?php } ?></div>
</div>
<a class="btn btn-dark sheen reveal" href="<?php echo $ce_button['url']; ?>"<?php echo $ce_button['target'] . $ce_button['rel']; ?> style="align-self: center"><?php echo $ce_button_text; ?> <svg class="btn-arr" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"></path><path d="M8 7h9v9"></path></svg></a>
</div>
</section>
