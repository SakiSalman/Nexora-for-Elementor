<?php
/**
 * CE solution markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-solution/markup.html byte for byte (parity gate).
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

$ce_d             = nexora_ph_ce_solution_defaults();
$ce_eyebrow       = nexora_ph_ce_text( $settings, 'solution_eyebrow', $ce_d['eyebrow'] );
$ce_heading_tag   = nexora_ph_ce_heading_tag( $settings, 'solution_heading_tag', $ce_d['heading_tag'] );
$ce_heading       = nexora_ph_ce_text( $settings, 'solution_heading', $ce_d['heading'] );
$ce_highlight     = nexora_ph_ce_text( $settings, 'solution_highlight', $ce_d['highlight'] );
$ce_description   = nexora_ph_ce_text( $settings, 'solution_description', $ce_d['description'] );
$ce_flow_label    = nexora_ph_ce_attr( $settings, 'solution_flow_label', $ce_d['flow_label'] );
$ce_flow          = nexora_ph_ce_rows( $settings, 'solution_flow_rows', $ce_d['flow'] );
$ce_button_text   = nexora_ph_ce_text( $settings, 'solution_button_text', $ce_d['button_text'] );
$ce_button        = nexora_ph_ce_link( $settings, 'solution_button_link', $ce_d['button_url'] );
$ce_cards         = nexora_ph_ce_rows( $settings, 'solution_cards', $ce_d['cards'] );
$ce_card_count    = count( $ce_cards );
$ce_title_tag_dflt = $ce_d['cards'][0]['solution_card_title_tag'];

// The design paints each card a different --bg; the palette cycles by position so
// added rows get a design color too. Phase 3: the "Card background" picker overrides
// every card, the positional design color stays as the var fallback. Derived from $i.
$ce_bgs = array( 'var(--ph-ce-solution-card-bg, #FFFFFF)', 'var(--ph-ce-solution-card-bg, #FFF6F0)', 'var(--ph-ce-solution-card-bg, #F4F8FC)', 'var(--ph-ce-solution-card-bg, #FFF1E9)' );
?>
<!-- CE3 SOLUTION (sticky title left, fixes stack on scroll) -->
<section class="sec ivory ss-sec">
<div class="wrap ss-wrap">
<div class="head ss-side">
<span class="tag" style="align-self: flex-start"><?php echo $ce_eyebrow; ?></span>
<<?php echo $ce_heading_tag; ?> class="h2"><?php echo $ce_heading; ?> <span class="grad-text"><?php echo $ce_highlight; ?></span></<?php echo $ce_heading_tag; ?>>
<p class="lead"><?php echo $ce_description; ?></p>
<div class="sv-flow" aria-label="<?php echo $ce_flow_label; ?>"><?php foreach ( $ce_flow as $ce_i => $ce_pill ) { ?><?php echo $ce_i ? '<i></i>' : ''; ?><span><?php echo esc_html( $ce_pill['solution_pill'] ); ?></span><?php } ?></div>
<a class="btn btn-dark sheen" href="<?php echo $ce_button['url']; ?>"<?php echo $ce_button['target'] . $ce_button['rel']; ?> style="align-self: flex-start"><?php echo $ce_button_text; ?> <svg class="btn-arr" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"></path><path d="M8 7h9v9"></path></svg></a>
</div>
<div class="ss-stack">
<?php foreach ( $ce_cards as $ce_i => $ce_card ) { $ce_row_tag = nexora_ph_ce_heading_tag( $ce_card, 'solution_card_title_tag', $ce_title_tag_dflt ); ?><article class="ss-card" style="--i: <?php echo $ce_i; ?>; --bg: <?php echo esc_attr( $ce_bgs[ $ce_i % count( $ce_bgs ) ] ); ?>">
<div class="ss-top"><span class="ss-num"><?php echo sprintf( '%02d', $ce_i + 1 ); ?></span><span class="icon" style="width: 52px; height: 52px; border-radius: 16px"><?php echo nexora_ph_ce_svg( $ce_card['solution_card_icon'] ); ?></span></div>
<<?php echo $ce_row_tag; ?>><?php echo esc_html( $ce_card['solution_card_title'] ); ?></<?php echo $ce_row_tag; ?>>
<p><?php echo esc_html( $ce_card['solution_card_description'] ); ?></p>
<span class="sb-res"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M9 7h8v8"></path></svg><?php echo esc_html( $ce_card['solution_card_result'] ); ?></span>
</article><?php if ( $ce_i + 1 === $ce_card_count ) { echo "\n"; } ?>
<?php } ?>
</div>
</div>
</section>
