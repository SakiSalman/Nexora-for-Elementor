<?php
/**
 * CE Hero markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-hero/markup.html byte for byte (parity gate).
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

$ce_d          = nexora_ph_ce_hero_defaults();
$ce_tag        = nexora_ph_ce_heading_tag( $settings, 'hero_heading_tag', $ce_d['heading_tag'] );
$ce_eyebrow    = nexora_ph_ce_text( $settings, 'hero_eyebrow', $ce_d['eyebrow'] );
$ce_heading    = nexora_ph_ce_text( $settings, 'hero_heading', $ce_d['heading'] );
$ce_highlight  = nexora_ph_ce_text( $settings, 'hero_highlight', $ce_d['highlight'] );
$ce_desc       = nexora_ph_ce_text( $settings, 'hero_description', $ce_d['description'] );
$ce_btn_text   = nexora_ph_ce_text( $settings, 'hero_button_text', $ce_d['button_text'] );
$ce_btn        = nexora_ph_ce_link( $settings, 'hero_button_link', $ce_d['button_url'] );
$ce_btn2_text  = nexora_ph_ce_text( $settings, 'hero_secondary_text', $ce_d['secondary_text'] );
/*
 * The secondary button is an internal anchor (#contact): seed the URL control
 * default when the key is missing, because the link helper's missing-key
 * assumption is an external link (target="_blank"), which the design doesn't have.
 */
if ( ! is_array( $settings ) || ! array_key_exists( 'hero_secondary_link', $settings ) ) {
	$settings['hero_secondary_link'] = array(
		'url'         => $ce_d['secondary_url'],
		'is_external' => 'false',
		'nofollow'    => 'false',
	);
}
$ce_btn2       = nexora_ph_ce_link( $settings, 'hero_secondary_link', $ce_d['secondary_url'] );
$ce_title      = nexora_ph_ce_text( $settings, 'hero_campaign_title', $ce_d['campaign_title'] );
$ce_subtitle   = nexora_ph_ce_text( $settings, 'hero_campaign_subtitle', $ce_d['campaign_subtitle'] );
$ce_live       = nexora_ph_ce_text( $settings, 'hero_live_text', $ce_d['live_text'] );
$ce_meter_l    = nexora_ph_ce_text( $settings, 'hero_meter_label', $ce_d['meter_label'] );
$ce_meter_v    = nexora_ph_ce_text( $settings, 'hero_meter_value', $ce_d['meter_value'] );
$ce_steps      = nexora_ph_ce_rows( $settings, 'hero_steps', $ce_d['steps'] );
$ce_results    = nexora_ph_ce_rows( $settings, 'hero_results', $ce_d['results'] );
$ce_badges     = nexora_ph_ce_rows( $settings, 'hero_badges', $ce_d['badges'] );
$ce_stat_num   = nexora_ph_ce_text( $settings, 'hero_stat_number', $ce_d['stat_number'] );
$ce_stat_label = nexora_ph_ce_text( $settings, 'hero_stat_label', $ce_d['stat_label'] );
$ce_stat_desc  = nexora_ph_ce_text( $settings, 'hero_stat_desc', $ce_d['stat_desc'] );
$ce_logos      = nexora_ph_ce_rows( $settings, 'hero_logos', $ce_d['logos'] );
$ce_cert_text  = nexora_ph_ce_text( $settings, 'hero_cert_text', $ce_d['cert_text'] );
$ce_cert_bold  = nexora_ph_ce_text( $settings, 'hero_cert_bold', $ce_d['cert_bold'] );
$ce_partners   = nexora_ph_ce_rows( $settings, 'hero_partners', $ce_d['partners'] );

// Badge subtitle: esc_html can emit the design's double quotes as &quot;, restore them.
$ce_quoted     = static function ( string $text ): string {
	return str_replace( '&quot;', '"', $text );
};
?>
<!-- CE1 HERO -->
<section class="hero-sec" style="position: relative; overflow: hidden; padding: 150px 0 190px; min-height: clamp(780px, 100vh, 960px); display: flex; align-items: center; background: radial-gradient(760px 520px at 88% 8%, var(--ph-ce-hero-glow-orange, rgba(241,94,34,.26)), var(--ph-ce-hero-glow-orange-end, transparent) var(--ph-ce-hero-glow-orange-end-stop, 60%)), radial-gradient(820px 560px at 72% 70%, var(--ph-ce-hero-glow-blue, rgba(35,116,172,.26)), var(--ph-ce-hero-glow-blue-end, transparent) var(--ph-ce-hero-glow-blue-end-stop, 62%)), radial-gradient(700px 500px at 0% 0%, var(--ph-ce-hero-glow-blue, rgba(35,116,172,.18)), var(--ph-ce-hero-glow-blue-end, transparent) var(--ph-ce-hero-glow-blue-end-stop, 60%)), radial-gradient(640px 460px at 8% 95%, var(--ph-ce-hero-glow-orange, rgba(241,94,34,.16)), var(--ph-ce-hero-glow-orange-end, transparent) var(--ph-ce-hero-glow-orange-end-stop, 60%)), linear-gradient(135deg, #FFF3EC 0%, #FFFFFF 42%, #EAF3FB 100%)">
<div class="gridlines"></div>
<div class="wrap stack-sm" style="position: relative; display: grid; grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr); gap: 56px; align-items: center">
<div class="reveal" style="display: flex; flex-direction: column; gap: 26px; position: relative; z-index: 2">
<span class="tag" style="align-self: flex-start"><?php echo $ce_eyebrow; ?></span>
<<?php echo $ce_tag; ?> style="font-size: clamp(36px, 4.3vw, 62px); line-height: 1.06; font-weight: 700; letter-spacing: -0.045em; text-wrap: balance; max-width: 720px"><?php echo $ce_heading; ?> <span class="grad-text"><?php echo $ce_highlight; ?></span></<?php echo $ce_tag; ?>>
<p class="lead" style="max-width: 600px; font-size: 19px"><?php echo $ce_desc; ?></p>
<div style="display: flex; flex-wrap: wrap; gap: 12px">
<a class="btn btn-primary sheen" href="<?php echo $ce_btn['url']; ?>"<?php echo $ce_btn['target'] . $ce_btn['rel']; ?>><?php echo $ce_btn_text; ?> <svg class="btn-arr" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7"></path><path d="M8 7h9v9"></path></svg></a>
<a class="btn btn-white" href="<?php echo $ce_btn2['url']; ?>"<?php echo $ce_btn2['target'] . $ce_btn2['rel']; ?>><?php echo $ce_btn2_text; ?></a>
</div>
</div>
<div class="ce-hv reveal">
<div class="ring" style="inset: 4% 6%; border-radius: 50%"></div>
<div class="ring" style="inset: 4% 6%; border-radius: 50%; animation-delay: 2.2s; border-color: rgba(35,116,172,.3)"></div>
<div class="glass ce-seq">
<div class="ce-seq-top"><span class="icon" style="width: 42px; height: 42px; border-radius: 13px"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0B1620" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 2L11 13"></path><path d="M22 2l-7 20-4-9-9-4z"></path></svg></span><div><strong><?php echo $ce_title; ?></strong><small><?php echo $ce_subtitle; ?></small></div><span class="ce-live"><i></i><?php echo $ce_live; ?></span></div>
<div class="ce-meter"><span><?php echo $ce_meter_l; ?></span><b><?php echo $ce_meter_v; ?></b><div class="ce-bar"><span></span></div></div>
<ol class="ce-steps">
<?php foreach ( $ce_steps as $i => $row ) {
	$ce_state_key = isset( $row['hero_step_state'] ) && isset( $ce_d['states'][ $row['hero_step_state'] ] ) ? (string) $row['hero_step_state'] : 'sent';
	$ce_state     = $ce_d['states'][ $ce_state_key ];
	// Design markers: Sent rows show ✓, the other states show the 1-based step number.
	$ce_sd        = 'sent' === $ce_state_key ? '✓' : (string) ( $i + 1 );
	?><li<?php echo $ce_state['li']; ?>><span class="ce-sd"><?php echo $ce_sd; ?></span><div><b><?php echo esc_html( (string) $row['hero_step_title'] ); ?></b><small><?php echo esc_html( (string) $row['hero_step_note'] ); ?></small></div><span class="<?php echo $ce_state['st']; ?>"><?php echo esc_html( (string) $row['hero_step_status'] ); ?></span></li>
<?php } ?></ol>
<div class="ce-kpis"><?php foreach ( $ce_results as $row ) { ?><div><b class="grad-text"><?php echo esc_html( (string) $row['hero_result_value'] ); ?></b><small><?php echo esc_html( (string) $row['hero_result_label'] ); ?></small></div><?php } ?></div>
</div>
<?php foreach ( $ce_badges as $i => $row ) { $ce_shell = $ce_d['badge_shells'][ $i % count( $ce_d['badge_shells'] ) ]; ?><?php echo $ce_shell['open'] . $ce_shell['icon']; ?><span><strong><?php echo esc_html( (string) $row['hero_badge_title'] ); ?></strong><br><span class="muted" style="font-size: 12px"><?php echo $ce_quoted( esc_html( (string) $row['hero_badge_sub'] ) ); ?></span></span></div>
<?php } ?>
</div>
</div>
</section>
<section aria-label="Clients" class="logo-sec" style="position: relative; margin-top: -118px; z-index: 3">
<div class="wrap">
<div class="logo-pill stack-sm reveal">
<div style="display: flex; flex-direction: column; gap: 6px; padding-right: 26px; border-right: 1px solid rgba(11,22,32,.08)">
<div style="display: flex; align-items: center; gap: 12px">
<div style="display: flex"><span style="width: 30px; height: 30px; border-radius: 50%; border: 2px solid #FFFFFF; background: linear-gradient(135deg, #FF9D72, #F15E22)"></span><span style="width: 30px; height: 30px; border-radius: 50%; border: 2px solid #FFFFFF; margin-left: -9px; background: linear-gradient(135deg, #7CC2F0, #2374AC)"></span><span style="width: 30px; height: 30px; border-radius: 50%; border: 2px solid #FFFFFF; margin-left: -9px; background: linear-gradient(135deg, #DCE4EA, #8FA0AF)"></span><span style="width: 30px; height: 30px; border-radius: 50%; border: 2px solid #FFFFFF; margin-left: -9px; background: linear-gradient(135deg, #FFD7C4, #F5A77E)"></span></div>
<span style="display: flex; gap: 2px" aria-label="Rated 5 out of 5"><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3 6.9 7.5.7-5.7 5 1.7 7.4L12 18.3 5.5 22l1.7-7.4-5.7-5 7.5-.7z" fill="#F15E22"></path></svg><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3 6.9 7.5.7-5.7 5 1.7 7.4L12 18.3 5.5 22l1.7-7.4-5.7-5 7.5-.7z" fill="#F15E22"></path></svg><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3 6.9 7.5.7-5.7 5 1.7 7.4L12 18.3 5.5 22l1.7-7.4-5.7-5 7.5-.7z" fill="#F15E22"></path></svg><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3 6.9 7.5.7-5.7 5 1.7 7.4L12 18.3 5.5 22l1.7-7.4-5.7-5 7.5-.7z" fill="#F15E22"></path></svg><svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2l3 6.9 7.5.7-5.7 5 1.7 7.4L12 18.3 5.5 22l1.7-7.4-5.7-5 7.5-.7z" fill="#F15E22"></path></svg></span>
</div>
<div style="display: flex; align-items: baseline; gap: 10px"><span class="grad-text" style="font-size: 46px; font-weight: 700; letter-spacing: -0.04em; line-height: 1.05"><?php echo $ce_stat_num; ?></span><span style="font-size: 14px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: #3A4753"><?php echo $ce_stat_label; ?></span></div>
<span class="muted" style="font-size: 14.5px; line-height: 1.5"><?php echo $ce_stat_desc; ?></span>
</div>
<div class="mask-x"><div class="marquee pause" style="gap: 16px; align-items: center"><?php for ( $ce_pass = 0; $ce_pass < $ce_d['prints']; $ce_pass++ ) : ?><?php foreach ( $ce_logos as $i => $row ) : ?><span class="logo-chip"<?php echo $ce_pass > 0 ? ' aria-hidden="true"' : ''; ?>><img class="logo-img" src="<?php echo nexora_ph_ce_media( $row, 'hero_logo_image', $ce_d['logos'][ $i ]['hero_logo_image']['url'] ?? '' ); ?>" alt="<?php echo $ce_pass > 0 ? '' : nexora_ph_ce_attr( $row, 'hero_logo_alt', $ce_d['logos'][ $i ]['hero_logo_alt'] ?? '' ); ?>"></span><?php endforeach; ?><?php endfor; ?></div></div>
<div style="grid-column: 1 / -1; display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 14px 22px; padding-top: 18px; border-top: 1px solid rgba(11,22,32,.07); font-size: 14px; color: #56626D"><span><?php echo $ce_cert_text; ?> <strong style="color: #0B1620"><?php echo $ce_cert_bold; ?></strong></span><span style="display: flex; align-items: center; gap: 14px"><?php foreach ( $ce_partners as $i => $row ) { ?><img src="<?php echo nexora_ph_ce_media( $row, 'hero_partner_image', $ce_d['partners'][ $i ]['hero_partner_image']['url'] ?? '' ); ?>" alt="<?php echo nexora_ph_ce_attr( $row, 'hero_partner_alt', $ce_d['partners'][ $i ]['hero_partner_alt'] ?? '' ); ?>" style="height: 28px; width: auto"><?php } ?></span></div>
</div>
</div>
</section>
