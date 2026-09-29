<?php
/**
 * Render markup for ELE GTM Funnel.
 *
 * Expected variables in scope:
 * @var array  $settings Widget settings.
 * @var string $uid      Unique instance id (nexora-gtm-{element_id}).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_array( $settings ) ) {
	$settings = [];
}

if ( empty( $uid ) || ! is_string( $uid ) ) {
	$uid = 'nexora-gtm-' . wp_unique_id();
}

/**
 * Sanitize a CSS color for use in inline styles / SVG attributes.
 *
 * @param mixed  $color   Raw color.
 * @param string $fallback Fallback hex.
 */
$nexora_safe_color = static function ( $color, string $fallback ): string {
	if ( ! is_string( $color ) || '' === trim( $color ) ) {
		return $fallback;
	}

	$color = trim( $color );

	if ( function_exists( 'sanitize_hex_color' ) ) {
		$hex = sanitize_hex_color( $color );
		if ( $hex ) {
			return $hex;
		}
	}

	// Allow rgb()/rgba()/hsl()/hsla() and named colors without CSS-breaking chars.
	if ( preg_match( '/^(#([0-9a-f]{3}|[0-9a-f]{6}|[0-9a-f]{8})|rgba?\([^;{}<>]*\)|hsla?\([^;{}<>]*\)|[a-z]+)$/i', $color ) ) {
		return $color;
	}

	return $fallback;
};

/**
 * Allowlisted SVG for icons (basic sanitization).
 *
 * @param string $svg SVG markup.
 */
$safe_svg = static function ( string $svg ): string {
	$svg = trim( $svg );
	if ( '' === $svg || false === stripos( $svg, '<svg' ) ) {
		return '';
	}

	$cleaned = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $svg );
	$svg     = is_string( $cleaned ) ? $cleaned : '';

	$cleaned = preg_replace( '/\son\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $svg );
	$svg     = is_string( $cleaned ) ? $cleaned : '';

	// Block foreignObject / iframe / embed which can nest HTML.
	$cleaned = preg_replace( '/<\/?(?:foreignObject|iframe|embed|object|link|meta)\b[^>]*>/i', '', $svg );
	$svg     = is_string( $cleaned ) ? $cleaned : '';

	return $svg;
};

$raw_steps = ! empty( $settings['process_steps'] ) && is_array( $settings['process_steps'] )
	? $settings['process_steps']
	: ( function_exists( 'nexora_gtm_funnel_default_steps' ) ? nexora_gtm_funnel_default_steps() : [] );

$raw_tiers = ! empty( $settings['funnel_tiers'] ) && is_array( $settings['funnel_tiers'] )
	? $settings['funnel_tiers']
	: ( function_exists( 'nexora_gtm_funnel_default_tiers' ) ? nexora_gtm_funnel_default_tiers() : [] );

$steps = [];
foreach ( $raw_steps as $step ) {
	if ( is_array( $step ) ) {
		$steps[] = $step;
	}
}

$tiers = [];
foreach ( $raw_tiers as $tier ) {
	if ( is_array( $tier ) ) {
		$tiers[] = $tier;
	}
}

if ( empty( $tiers ) && function_exists( 'nexora_gtm_funnel_default_tiers' ) ) {
	$tiers = nexora_gtm_funnel_default_tiers();
}

if ( empty( $steps ) && function_exists( 'nexora_gtm_funnel_default_steps' ) ) {
	$steps = nexora_gtm_funnel_default_steps();
}

$geometry_settings = [
	'top_width'    => $settings['top_width'] ?? null,
	'bottom_width' => $settings['bottom_width'] ?? null,
	'top_y'        => $settings['top_y'] ?? null,
	'tier_height'  => $settings['tier_height'] ?? null,
	'tier_gap'     => $settings['tier_gap'] ?? null,
	'curve_depth'  => $settings['curve_depth'] ?? null,
	'node_offset'  => $settings['node_offset'] ?? null,
	'node_radius'  => $settings['node_radius'] ?? null,
];

$geometry = class_exists( 'Nexora_Funnel_Geometry' )
	? Nexora_Funnel_Geometry::resolve( count( $tiers ), $geometry_settings )
	: [
		'tiers'         => [],
		'viewBoxHeight' => 480,
		'viewWidth'     => 600,
	];

$funnel_position      = $settings['funnel_position'] ?? 'right';
$process_span         = max( 4, min( 8, (int) ( $settings['process_col_span'] ?? 6 ) ) );
$funnel_span          = max( 4, min( 8, (int) ( $settings['funnel_col_span'] ?? 6 ) ) );
$use_min_height       = ( $settings['use_min_height'] ?? 'yes' ) === 'yes';
$mobile_stack         = $settings['mobile_stack_order'] ?? 'process_first';
$hide_funnel_mobile   = ( $settings['hide_funnel_mobile'] ?? '' ) === 'yes';
$show_section_heading = ( $settings['show_section_heading'] ?? '' ) === 'yes';

$interaction_mode = $settings['interaction_mode'] ?? 'hover';
$default_active   = $settings['default_active'] ?? 'none';
$default_index    = max( 1, (int) ( $settings['default_active_index'] ?? 1 ) );
$dim_inactive     = ( $settings['dim_inactive'] ?? 'yes' ) === 'yes';
$auto_rotate      = ( $settings['auto_rotate'] ?? '' ) === 'yes';
$rotate_interval  = max( 1000, (int) ( $settings['rotate_interval'] ?? 3000 ) );
$enable_keyboard  = ( $settings['enable_keyboard'] ?? 'yes' ) === 'yes';

$js_config = [
	'mode'           => in_array( $interaction_mode, [ 'hover', 'click', 'both' ], true ) ? $interaction_mode : 'hover',
	'defaultActive'  => in_array( $default_active, [ 'none', 'first', 'specific' ], true ) ? $default_active : 'none',
	'defaultIndex'   => $default_index,
	'dimInactive'    => $dim_inactive,
	'autoRotate'     => $auto_rotate,
	'rotateInterval' => $rotate_interval,
	'enableKeyboard' => $enable_keyboard,
];

$config_json = wp_json_encode( $js_config );
if ( false === $config_json ) {
	$config_json = '{}';
}

$card_bg_start     = $nexora_safe_color( $settings['card_bg_start'] ?? '', '#2c374d' );
$card_bg_mid       = $nexora_safe_color( $settings['card_bg_mid'] ?? '', '#3c4c6a' );
$card_bg_end       = $nexora_safe_color( $settings['card_bg_end'] ?? '', '#596d94' );
$card_active_start = $nexora_safe_color( $settings['card_active_bg_start'] ?? '', '#34425d' );
$card_active_mid   = $nexora_safe_color( $settings['card_active_bg_mid'] ?? '', '#495a7c' );
$card_active_end   = $nexora_safe_color( $settings['card_active_bg_end'] ?? '', '#6982b0' );

$glow_dy = 6.0;
if ( isset( $settings['glow_dy']['size'] ) && is_numeric( $settings['glow_dy']['size'] ) ) {
	$glow_dy = (float) $settings['glow_dy']['size'];
}

$glow_blur = 10.0;
if ( isset( $settings['glow_blur']['size'] ) && is_numeric( $settings['glow_blur']['size'] ) ) {
	$glow_blur = (float) $settings['glow_blur']['size'];
}

$glow_color = $nexora_safe_color( $settings['glow_color'] ?? '', '#000000' );

$glow_opacity = 0.22;
if ( isset( $settings['glow_opacity']['size'] ) && is_numeric( $settings['glow_opacity']['size'] ) ) {
	$glow_opacity = max( 0, min( 1, (float) $settings['glow_opacity']['size'] ) );
}

$wrapper_classes = [
	'nexora-ele-gtm-funnel',
	'nexora-gtm__wrapper',
	'nexora-gtm--funnel-' . sanitize_html_class( (string) $funnel_position ),
];

if ( ! $use_min_height ) {
	$wrapper_classes[] = 'nexora-gtm--no-min-height';
}

if ( 'funnel_first' === $mobile_stack ) {
	$wrapper_classes[] = 'nexora-gtm--stack-funnel-first';
}

if ( $hide_funnel_mobile ) {
	$wrapper_classes[] = 'nexora-gtm--hide-funnel-mobile';
}

$wrapper_style = sprintf(
	'--ngtm-process-span: %d; --ngtm-funnel-span: %d; --ngtm-glow: url(#active-glow-%s); --ngtm-card-bg: linear-gradient(to right, %s, %s, %s); --ngtm-card-active-bg: linear-gradient(to right, %s, %s, %s);',
	$process_span,
	$funnel_span,
	esc_attr( $uid ),
	$card_bg_start,
	$card_bg_mid,
	$card_bg_end,
	$card_active_start,
	$card_active_mid,
	$card_active_end
);

$view_width  = (int) ( $geometry['viewWidth'] ?? 600 );
$view_height = (float) ( $geometry['viewBoxHeight'] ?? 480 );
if ( $view_width < 1 ) {
	$view_width = 600;
}
if ( $view_height < 1 ) {
	$view_height = 480;
}

$groups  = [];
$current = null;

foreach ( $steps as $index => $step ) {
	if ( ! is_array( $step ) ) {
		continue;
	}

	$heading = isset( $step['group_heading'] ) ? trim( (string) $step['group_heading'] ) : '';

	if ( '' !== $heading || null === $current ) {
		if ( null !== $current ) {
			$groups[] = $current;
		}
		$current = [
			'heading' => $heading,
			'items'   => [],
		];
	}

	$step['_index']       = $index;
	$current['items'][] = $step;
}

if ( null !== $current ) {
	$groups[] = $current;
}

$resolve_tier_key = static function ( array $step, int $index, array $tiers ): string {
	$linked = isset( $step['linked_tier'] ) ? (int) $step['linked_tier'] : ( $index + 1 );
	$linked = max( 1, $linked );
	$tier_i = $linked - 1;

	if ( isset( $tiers[ $tier_i ] ) && is_array( $tiers[ $tier_i ] ) && ! empty( $tiers[ $tier_i ]['_id'] ) ) {
		return (string) $tiers[ $tier_i ]['_id'];
	}

	return (string) $tier_i;
};

?>
<div
	class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>"
	data-nexora-gtm-funnel
	data-instance="<?php echo esc_attr( $uid ); ?>"
	data-config="<?php echo esc_attr( $config_json ); ?>"
	style="<?php echo esc_attr( $wrapper_style ); ?>"
>
	<main class="nexora-gtm__main" id="gtm-section-<?php echo esc_attr( $uid ); ?>">

		<?php if ( $show_section_heading ) : ?>
			<div class="nexora-gtm__section-heading">
				<?php if ( ! empty( $settings['section_eyebrow'] ) ) : ?>
					<div class="nexora-gtm__section-eyebrow"><?php echo esc_html( (string) $settings['section_eyebrow'] ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $settings['section_title'] ) ) : ?>
					<h2 class="nexora-gtm__section-title"><?php echo esc_html( (string) $settings['section_title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $settings['section_subtitle'] ) ) : ?>
					<p class="nexora-gtm__section-subtitle"><?php echo esc_html( (string) $settings['section_subtitle'] ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<div class="nexora-gtm__grid">

			<section class="nexora-gtm__process" aria-label="<?php echo esc_attr__( 'Strategy Execution Steps', 'nexora-elementor' ); ?>">

				<?php foreach ( $groups as $group ) : ?>
					<div class="nexora-gtm__group">
						<?php if ( ! empty( $group['heading'] ) ) : ?>
							<h2 class="nexora-gtm__group-heading"><?php echo esc_html( (string) $group['heading'] ); ?></h2>
						<?php endif; ?>

						<div class="nexora-gtm__group-steps">
							<?php foreach ( $group['items'] as $step ) :
								if ( ! is_array( $step ) ) {
									continue;
								}

								$index      = (int) ( $step['_index'] ?? 0 );
								$tier_key   = $resolve_tier_key( $step, $index, $tiers );
								$badge      = sanitize_html_class( (string) ( $step['badge_style'] ?? 'dark' ) );
								if ( ! in_array( $badge, [ 'accent', 'dark', 'blue' ], true ) ) {
									$badge = 'dark';
								}
								$icon_tone  = ( $step['icon_tone'] ?? 'accent' ) === 'muted' ? 'muted' : 'accent';
								$icon_frame = ( $step['icon_frame'] ?? '' ) === 'yes';
								$icon_html  = function_exists( 'nexora_gtm_resolve_icon_html' )
									? nexora_gtm_resolve_icon_html(
										$step['step_icon'] ?? null,
										(string) ( $step['step_icon_svg'] ?? '' ),
										$safe_svg
									)
									: $safe_svg( (string) ( $step['step_icon_svg'] ?? '' ) );
								$item_id    = ! empty( $step['_id'] ) ? sanitize_html_class( (string) $step['_id'] ) : (string) $index;
								$kb_attrs   = $enable_keyboard
									? ' tabindex="0" role="button" aria-pressed="false"'
									: '';
								?>
								<article
									class="nexora-gtm__card process-card group elementor-repeater-item-<?php echo esc_attr( $item_id ); ?>"
									data-tier="<?php echo esc_attr( $tier_key ); ?>"
									<?php echo $kb_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								>
									<div class="nexora-gtm__badge-wrap">
										<div class="nexora-gtm__badge nexora-gtm__badge--<?php echo esc_attr( $badge ); ?>">
											<?php echo esc_html( (string) ( $step['step_number'] ?? '' ) ); ?>
										</div>
									</div>
									<div class="nexora-gtm__card-body card-body">
										<div class="nexora-gtm__card-head">
											<?php if ( $icon_html ) : ?>
												<?php if ( $icon_frame ) : ?>
													<div class="nexora-gtm__card-icon-frame">
														<span class="nexora-gtm__card-icon<?php echo 'muted' === $icon_tone ? ' nexora-gtm__card-icon--muted' : ''; ?>">
															<?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager / sanitized SVG ?>
														</span>
													</div>
												<?php else : ?>
													<span class="nexora-gtm__card-icon<?php echo 'muted' === $icon_tone ? ' nexora-gtm__card-icon--muted' : ''; ?>">
														<?php echo $icon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													</span>
												<?php endif; ?>
											<?php endif; ?>
											<h3 class="nexora-gtm__card-title">
												<?php echo esc_html( (string) ( $step['step_title'] ?? '' ) ); ?>
											</h3>
										</div>
										<?php if ( ! empty( $step['step_description'] ) ) : ?>
											<p class="nexora-gtm__card-desc">
												<?php echo esc_html( (string) $step['step_description'] ); ?>
											</p>
										<?php endif; ?>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endforeach; ?>

			</section>

			<section class="nexora-gtm__funnel-col" aria-label="<?php echo esc_attr__( 'Growth Conversion Funnel', 'nexora-elementor' ); ?>">
				<div class="nexora-gtm__funnel-container" id="funnel-container-<?php echo esc_attr( $uid ); ?>">
					<svg
						viewBox="0 0 <?php echo esc_attr( (string) $view_width ); ?> <?php echo esc_attr( (string) $view_height ); ?>"
						class="nexora-gtm__funnel-svg"
						xmlns="http://www.w3.org/2000/svg"
					>
						<defs>
							<?php foreach ( $tiers as $i => $tier ) :
								if ( ! is_array( $tier ) ) {
									continue;
								}
								$grad_id = 'grad-' . $uid . '-' . $i;
								?>
								<linearGradient id="<?php echo esc_attr( $grad_id ); ?>" x1="0%" y1="0%" x2="100%" y2="100%">
									<stop offset="0%" stop-color="<?php echo esc_attr( $nexora_safe_color( $tier['gradient_start'] ?? '', '#7325e8' ) ); ?>" />
									<stop offset="50%" stop-color="<?php echo esc_attr( $nexora_safe_color( $tier['gradient_mid'] ?? '', '#8432f6' ) ); ?>" />
									<stop offset="100%" stop-color="<?php echo esc_attr( $nexora_safe_color( $tier['gradient_end'] ?? '', '#8e37f8' ) ); ?>" />
								</linearGradient>
							<?php endforeach; ?>

							<filter id="active-glow-<?php echo esc_attr( $uid ); ?>" x="-20%" y="-20%" width="140%" height="140%">
								<feDropShadow
									dx="0"
									dy="<?php echo esc_attr( (string) $glow_dy ); ?>"
									stdDeviation="<?php echo esc_attr( (string) $glow_blur ); ?>"
									flood-color="<?php echo esc_attr( $glow_color ); ?>"
									flood-opacity="<?php echo esc_attr( (string) $glow_opacity ); ?>"
								/>
							</filter>

							<filter id="node-shadow-<?php echo esc_attr( $uid ); ?>" x="-30%" y="-30%" width="160%" height="160%">
								<feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#0f172a" flood-opacity="0.25" />
							</filter>
						</defs>

						<?php
						foreach ( $tiers as $i => $tier ) :
							if ( ! is_array( $tier ) ) {
								continue;
							}

							$geo = $geometry['tiers'][ $i ] ?? null;
							if ( ! is_array( $geo ) || empty( $geo['path'] ) ) {
								continue;
							}

							$tier_key  = ! empty( $tier['_id'] ) ? (string) $tier['_id'] : (string) $i;
							$grad_id   = 'grad-' . $uid . '-' . $i;
							$kb_attrs  = $enable_keyboard
								? ' tabindex="0" role="button" aria-pressed="false"'
								: '';
							$node_html = function_exists( 'nexora_gtm_resolve_icon_html' )
								? nexora_gtm_resolve_icon_html(
									$tier['node_icon'] ?? null,
									(string) ( $tier['node_icon_svg'] ?? '' ),
									$safe_svg
								)
								: $safe_svg( (string) ( $tier['node_icon_svg'] ?? '' ) );
							$show_node = ( $tier['show_node'] ?? 'yes' ) === 'yes';
							$node_r    = isset( $geo['nodeRadius'] ) ? (float) $geo['nodeRadius'] : 20.0;
							?>
							<g
								class="nexora-gtm__tier funnel-tier"
								data-tier="<?php echo esc_attr( $tier_key ); ?>"
								style="transform-origin: <?php echo esc_attr( (string) ( $geo['transformOrigin'] ?? '300px 80px' ) ); ?>;"
								<?php echo $kb_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							>
								<path
									d="<?php echo esc_attr( (string) $geo['path'] ); ?>"
									fill="url(#<?php echo esc_attr( $grad_id ); ?>)"
									class="tier-path"
								/>
								<text
									x="<?php echo esc_attr( (string) ( $view_width / 2 ) ); ?>"
									y="<?php echo esc_attr( (string) ( $geo['valueY'] ?? 0 ) ); ?>"
									text-anchor="middle"
									class="nexora-gtm__tier-value font-sans"
								><?php echo esc_html( (string) ( $tier['tier_value'] ?? '' ) ); ?></text>
								<text
									x="<?php echo esc_attr( (string) ( $view_width / 2 ) ); ?>"
									y="<?php echo esc_attr( (string) ( $geo['labelY'] ?? 0 ) ); ?>"
									text-anchor="middle"
									class="nexora-gtm__tier-label font-sans"
								><?php echo esc_html( (string) ( $tier['tier_label'] ?? '' ) ); ?></text>
								<text
									x="<?php echo esc_attr( (string) ( $view_width / 2 ) ); ?>"
									y="<?php echo esc_attr( (string) ( $geo['subY'] ?? 0 ) ); ?>"
									text-anchor="middle"
									class="nexora-gtm__tier-sub font-sans"
								><?php echo esc_html( (string) ( $tier['tier_sublabel'] ?? '' ) ); ?></text>

								<?php if ( $show_node ) : ?>
									<g
										class="nexora-gtm__node"
										transform="translate(<?php echo esc_attr( (string) ( $geo['nodeX'] ?? 0 ) ); ?>, <?php echo esc_attr( (string) ( $geo['nodeY'] ?? 0 ) ); ?>)"
										filter="url(#node-shadow-<?php echo esc_attr( $uid ); ?>)"
									>
										<circle
											cx="0"
											cy="0"
											r="<?php echo esc_attr( (string) $node_r ); ?>"
											fill="<?php echo esc_attr( $nexora_safe_color( $tier['node_color'] ?? '', '#6d26e4' ) ); ?>"
											stroke="#ffffff"
											stroke-width="2.5"
											stroke-opacity="0.9"
										/>
										<?php if ( $node_html ) : ?>
											<foreignObject x="-8" y="-8" width="16" height="16">
												<div xmlns="http://www.w3.org/1999/xhtml" class="nexora-gtm__node-icon">
													<?php echo $node_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Icons_Manager / sanitized SVG ?>
												</div>
											</foreignObject>
										<?php endif; ?>
									</g>
								<?php endif; ?>
							</g>
						<?php endforeach; ?>
					</svg>
				</div>
			</section>

		</div>
	</main>
</div>
