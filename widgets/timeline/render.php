<?php
/**
 * Render markup for ELE Timeline.
 *
 * Expected variables in scope:
 * @var array  $settings Widget settings.
 * @var string $uid      Unique instance id (nexora-tl-{element_id}).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_array( $settings ) ) {
	$settings = [];
}

if ( empty( $uid ) || ! is_string( $uid ) ) {
	$uid = 'nexora-tl-' . wp_unique_id();
}

$safe_color = static function ( $color ): string {
	if ( ! is_string( $color ) || '' === trim( $color ) ) {
		return '';
	}

	$color = trim( $color );

	if ( function_exists( 'sanitize_hex_color' ) ) {
		$hex = sanitize_hex_color( $color );
		if ( $hex ) {
			return $hex;
		}
	}

	if ( preg_match( '/^(#([0-9a-f]{3}|[0-9a-f]{6}|[0-9a-f]{8})|rgba?\([^;{}<>]*\)|hsla?\([^;{}<>]*\)|[a-z]+)$/i', $color ) ) {
		return $color;
	}

	return '';
};

$raw_items = ! empty( $settings['timeline_items'] ) && is_array( $settings['timeline_items'] )
	? $settings['timeline_items']
	: ( function_exists( 'nexora_timeline_default_items' ) ? nexora_timeline_default_items() : [] );

$items = [];

foreach ( $raw_items as $index => $item ) {
	if ( ! is_array( $item ) ) {
		continue;
	}

	$img = isset( $item['item_image'] ) && is_array( $item['item_image'] ) ? $item['item_image'] : [];
	$url = isset( $img['url'] ) ? esc_url( (string) $img['url'] ) : '';

	$items[] = [
		'id'          => $index + 1,
		'number'      => isset( $item['item_number'] ) ? (string) $item['item_number'] : sprintf( '%02d', $index + 1 ),
		'title'       => isset( $item['item_title'] ) ? (string) $item['item_title'] : '',
		'description' => isset( $item['item_description'] ) ? (string) $item['item_description'] : '',
		'image'       => $url,
		'link'        => isset( $item['item_link'] ) && is_array( $item['item_link'] ) ? $item['item_link'] : [],
		'class'       => isset( $item['item_class'] ) ? sanitize_html_class( (string) $item['item_class'] ) : '',
		'colors'      => [
			'num'         => $safe_color( $item['item_number_color'] ?? '' ),
			'num_active'  => $safe_color( $item['item_number_color_active'] ?? '' ),
			'title'       => $safe_color( $item['item_title_color'] ?? '' ),
			'title_active'=> $safe_color( $item['item_title_color_active'] ?? '' ),
			'desc'        => $safe_color( $item['item_desc_color'] ?? '' ),
		],
	];
}

if ( empty( $items ) ) {
	return;
}

$left_span  = isset( $settings['left_col_span'] ) ? (string) $settings['left_col_span'] : '6';
$right_span = isset( $settings['right_col_span'] ) ? (string) $settings['right_col_span'] : '6';
$allowed_spans = [ '4', '5', '6', '7', '8' ];

if ( ! in_array( $left_span, $allowed_spans, true ) ) {
	$left_span = '6';
}

if ( ! in_array( $right_span, $allowed_spans, true ) ) {
	$right_span = '6';
}

$enable_interactions = ! isset( $settings['enable_interactions'] ) || 'yes' === $settings['enable_interactions'];
$swap_delay          = isset( $settings['image_swap_delay'] ) ? (int) $settings['image_swap_delay'] : 150;

if ( $swap_delay < 0 ) {
	$swap_delay = 0;
}

if ( $swap_delay > 1000 ) {
	$swap_delay = 1000;
}

$first       = $items[0];
$config_json = wp_json_encode(
	[
		'enabled'   => $enable_interactions,
		'swapDelay' => $swap_delay,
	]
);

if ( ! is_string( $config_json ) ) {
	$config_json = '{"enabled":true,"swapDelay":150}';
}

// Inline CSS vars block Elementor live Style updates in the editor.
// Keep them on the public frontend only as a reliability fallback.
$is_elementor_edit_or_preview = false;

if ( class_exists( '\Elementor\Plugin', false ) ) {
	$elementor = \Elementor\Plugin::$instance;

	if ( isset( $elementor->editor ) && method_exists( $elementor->editor, 'is_edit_mode' ) && $elementor->editor->is_edit_mode() ) {
		$is_elementor_edit_or_preview = true;
	}

	if ( isset( $elementor->preview ) && method_exists( $elementor->preview, 'is_preview_mode' ) && $elementor->preview->is_preview_mode() ) {
		$is_elementor_edit_or_preview = true;
	}
}

$root_style = '';

if ( ! $is_elementor_edit_or_preview && function_exists( 'nexora_timeline_build_root_styles' ) ) {
	$root_style = nexora_timeline_build_root_styles( $settings );
}
?>
<div
	id="<?php echo esc_attr( $uid ); ?>"
	class="nexora-ele-timeline"
	data-nexora-timeline
	data-config="<?php echo esc_attr( $config_json ); ?>"
	<?php if ( '' !== $root_style ) : ?>
		style="<?php echo esc_attr( $root_style ); ?>"
	<?php endif; ?>
>
	<div class="nexora-ele-timeline__inner">
		<div class="nexora-ele-timeline__grid">

			<div class="nexora-ele-timeline__left nexora-ele-timeline__col nexora-ele-timeline__col--<?php echo esc_attr( $left_span ); ?>">
				<div class="nexora-ele-timeline__visual" data-timeline-visual>
					<div class="nexora-ele-timeline__image-frame">
						<img
							class="nexora-ele-timeline__desktop-image fade-image"
							src="<?php echo esc_url( $first['image'] ); ?>"
							alt="<?php echo esc_attr( $first['title'] ); ?>"
							decoding="async"
						/>
						<span class="nexora-ele-timeline__image-overlay" aria-hidden="true"></span>
					</div>
					<?php if ( ! isset( $settings['show_large_active_number'] ) || 'yes' === $settings['show_large_active_number'] ) : ?>
						<div class="nexora-ele-timeline__active-num" data-active-num aria-hidden="true">
							<?php echo esc_html( $first['number'] ); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<div class="nexora-ele-timeline__right nexora-ele-timeline__col nexora-ele-timeline__col--<?php echo esc_attr( $right_span ); ?>">
				<div class="nexora-ele-timeline__steps">
					<div class="timeline-track-line" aria-hidden="true"></div>
					<div class="timeline-progress-line" data-progress-line style="height: 0%;" aria-hidden="true"></div>

					<?php foreach ( $items as $idx => $item ) : ?>
						<?php
						$step_classes = [ 'timeline-step', 'group', 'relative' ];
						if ( 0 === $idx ) {
							$step_classes[] = 'active';
						}
						if ( '' !== $item['class'] ) {
							$step_classes[] = $item['class'];
						}

						$item_style_parts = [];
						if ( '' !== $item['colors']['num'] ) {
							$item_style_parts[] = '--nexora-tl-item-num:' . $item['colors']['num'];
						}
						if ( '' !== $item['colors']['num_active'] ) {
							$item_style_parts[] = '--nexora-tl-item-num-active:' . $item['colors']['num_active'];
						}
						if ( '' !== $item['colors']['title'] ) {
							$item_style_parts[] = '--nexora-tl-item-title:' . $item['colors']['title'];
						}
						if ( '' !== $item['colors']['title_active'] ) {
							$item_style_parts[] = '--nexora-tl-item-title-active:' . $item['colors']['title_active'];
						}
						if ( '' !== $item['colors']['desc'] ) {
							$item_style_parts[] = '--nexora-tl-item-desc:' . $item['colors']['desc'];
						}
						$item_style = implode( ';', $item_style_parts );

						$link_url = '';
						if ( ! empty( $item['link']['url'] ) ) {
							$link_url = (string) $item['link']['url'];
						}

						$mobile_hidden = 0 === $idx ? '' : ' is-hidden';
						?>
						<div
							class="<?php echo esc_attr( implode( ' ', $step_classes ) ); ?>"
							data-step-id="<?php echo esc_attr( (string) $item['id'] ); ?>"
							data-step-image="<?php echo esc_url( $item['image'] ); ?>"
							data-step-number="<?php echo esc_attr( $item['number'] ); ?>"
							role="button"
							tabindex="0"
							aria-label="<?php echo esc_attr( $item['title'] ); ?>"
							<?php if ( '' !== $item_style ) : ?>
								style="<?php echo esc_attr( $item_style ); ?>"
							<?php endif; ?>
						>
							<div class="timeline-node" aria-hidden="true"></div>

							<div class="nexora-ele-timeline__step-body">
								<div class="step-num"><?php echo esc_html( $item['number'] ); ?></div>

								<?php if ( '' !== $link_url ) : ?>
									<a
										class="step-title-link"
										href="<?php echo esc_url( $link_url ); ?>"
										<?php echo ! empty( $item['link']['is_external'] ) ? 'target="_blank"' : ''; ?>
										<?php
										if ( ! empty( $item['link']['nofollow'] ) ) {
											echo 'rel="nofollow"';
										} elseif ( ! empty( $item['link']['is_external'] ) ) {
											echo 'rel="noopener noreferrer"';
										}
										?>
									>
										<h3 class="step-title"><?php echo esc_html( $item['title'] ); ?></h3>
									</a>
								<?php else : ?>
									<h3 class="step-title"><?php echo esc_html( $item['title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( '' !== $item['description'] ) : ?>
									<p class="step-desc"><?php echo esc_html( $item['description'] ); ?></p>
								<?php endif; ?>

								<div class="nexora-ele-timeline__mobile-image mobile-image-container<?php echo esc_attr( $mobile_hidden ); ?>" data-mobile-image>
									<div class="nexora-ele-timeline__mobile-frame">
										<?php if ( '' !== $item['image'] ) : ?>
											<img
												src="<?php echo esc_url( $item['image'] ); ?>"
												alt="<?php echo esc_attr( $item['title'] ); ?>"
												decoding="async"
											/>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</div>
</div>
