<?php
/**
 * CE services markup with editable holes.
 *
 * Defaults reproduce widgets/ph-ce-services/markup.html byte for byte (parity gate).
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

$ce_d         = nexora_ph_ce_services_defaults();
$eyebrow      = nexora_ph_ce_text( $settings, 'services_eyebrow', $ce_d['eyebrow'] );
$heading      = nexora_ph_ce_text( $settings, 'services_heading', $ce_d['heading'] );
$highlight    = nexora_ph_ce_text( $settings, 'services_highlight', $ce_d['highlight'] );
$description  = nexora_ph_ce_text( $settings, 'services_description', $ce_d['description'] );
$heading_tag  = nexora_ph_ce_heading_tag( $settings, 'services_heading_tag', $ce_d['heading_tag'] );
$cards        = nexora_ph_ce_rows( $settings, 'services_cards', $ce_d['cards'] );

// Icon tint select value → the design's baked stroke color. When the chosen tint
// differs from the color baked into the icon body, the stroke attribute value is
// swapped; the default rows keep the harvested bytes untouched.
$ce_tints = array(
	'orange' => '#C2410C',
	'blue'   => '#1A5A87',
	'dark'   => '#0B1620',
);
?>
<!-- CE4 SERVICES (homepage style) -->
<section id="services" class="sec" style="background: #FFFFFF">
<div class="wrap" style="display: flex; flex-direction: column; gap: 56px">
<div class="head center reveal" style="max-width: 900px">
<span class="tag"><?php echo $eyebrow; ?></span>
<<?php echo $heading_tag; ?> class="h2"><?php echo $heading; ?> <span class="grad-text"><?php echo $highlight; ?></span></<?php echo $heading_tag; ?>>
<p class="lead"><?php echo $description; ?></p>
</div>
<div class="g4 hv-grid">
<?php
foreach ( $cards as $card ) {
	$svg        = nexora_ph_ce_svg( $card['services_icon'] );
	$icon_tint  = (string) $card['services_icon_tint'];
	$icon_color = isset( $ce_tints[ $icon_tint ] ) ? $ce_tints[ $icon_tint ] : '';
	// Swap the baked stroke only when the chosen tint differs (default rows pass through untouched).
	if ( '' !== $icon_color && preg_match( '/stroke="(#[0-9A-Fa-f]{6})"/', $svg, $ce_stroke ) && $ce_stroke[1] !== $icon_color ) {
		$svg = str_replace( 'stroke="' . $ce_stroke[1] . '"', 'stroke="' . $icon_color . '"', $svg );
	}
	$icon_class = 'blue' === $icon_tint ? 'icon-b' : 'icon-o';
	$badge      = esc_html( (string) $card['services_badge'] );
	$title      = esc_html( (string) $card['services_title'] );
	$title_tag  = nexora_ph_ce_heading_tag( $card, 'services_title_tag', 'h3' );
	$desc       = esc_html( (string) $card['services_description'] );
	$chips      = nexora_ph_ce_lines( $card['services_chips'] );
	$checks     = nexora_ph_ce_lines( $card['services_checklist'] );
	$layout     = (string) $card['services_card_layout'];
	if ( 'checklist' === $layout ) {
		?>
<article class="hv-card card span2 reveal stack-sm" style="padding: 36px; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 28px">
<div style="display: flex; flex-direction: column; gap: 14px"><div class="<?php echo esc_attr( $icon_class ); ?>"><?php echo $svg; ?></div><<?php echo $title_tag; ?> style="font-size: 23px; line-height: 1.25; font-weight: 600"><?php echo $title; ?></<?php echo $title_tag; ?>><p class="muted" style="font-size: 15.5px; line-height: 1.6"><?php echo $desc; ?></p></div>
<ul style="margin: 0; padding: 20px; list-style: none; display: flex; flex-direction: column; justify-content: center; gap: 14px; border-radius: 18px; background: linear-gradient(160deg, #FFF6F0, #FFFFFF); border: 1px solid #F6E1D5; font-size: 15px; font-weight: 500"><?php foreach ( $checks as $check ) { ?><li style="display: flex; gap: 10px; align-items: center"><span class="check">✓</span><?php echo esc_html( $check ); ?></li><?php } ?></ul>
</article>
<?php	} elseif ( 'standard' === $layout ) {
		?>
<article class="hv-card card reveal" style="padding: 32px; display: flex; flex-direction: column; gap: 14px"><div class="<?php echo esc_attr( $icon_class ); ?>"><?php echo $svg; ?></div><<?php echo $title_tag; ?> style="font-size: 21px; font-weight: 600"><?php echo $title; ?></<?php echo $title_tag; ?>><p class="muted" style="font-size: 15.5px; line-height: 1.6"><?php echo $desc; ?></p></article>
<?php	} else {
		?>
<article class="hv-card card span2 reveal hv-on" style="padding: 36px; display: flex; flex-direction: column; gap: 16px">
<div style="display: flex; justify-content: space-between; align-items: flex-start"><div class="<?php echo esc_attr( $icon_class ); ?>"><?php echo $svg; ?></div><span class="chip"><?php echo $badge; ?></span></div>
<<?php echo $title_tag; ?> style="font-size: 26px; line-height: 1.2; font-weight: 600"><?php echo $title; ?></<?php echo $title_tag; ?>>
<p class="muted" style="font-size: 16px; line-height: 1.65; max-width: 520px"><?php echo $desc; ?></p>
<div style="display: flex; flex-wrap: wrap; gap: 8px"><?php foreach ( $chips as $chip ) { ?><span class="chip"><?php echo esc_html( $chip ); ?></span><?php } ?></div>
</article>
<?php	}
}
?>
</div>
</div>
</section>