<?php
/**
 * Shared Prospects Hive style color tokens.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'nexora_ph_style_colors_vars' ) ) {
	/**
	 * Build the conventional CSS variable map for one gradient control.
	 *
	 * @param string $prefix Variable name without leading dashes, such as ph-hero-core.
	 * @param string $type   Gradient type, linear or radial.
	 * @return array<string, string>
	 */
	function nexora_ph_style_colors_vars( string $prefix, string $type = 'linear' ): array {
		$vars = [
			'color'        => '--' . $prefix,
			'color_b'      => '--' . $prefix . '-end',
			'color_stop'   => '--' . $prefix . '-start-stop',
			'color_b_stop' => '--' . $prefix . '-end-stop',
		];

		if ( 'radial' === $type ) {
			$vars['gradient_position'] = '--' . $prefix . '-position';
			return $vars;
		}

		$vars['gradient_angle'] = '--' . $prefix . '-angle';
		return $vars;
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_position_options' ) ) {
	/**
	 * Return radial position options with the original design origin first.
	 *
	 * @param string $design_origin CSS origin, such as 88% 8%.
	 * @return array<string, string>
	 */
	function nexora_ph_style_colors_position_options( string $design_origin ): array {
		return [
			$design_origin  => sprintf( 'Design default (%s)', $design_origin ),
			'center center' => 'Center Center',
			'center left'   => 'Center Left',
			'center right'  => 'Center Right',
			'top center'    => 'Top Center',
			'top left'      => 'Top Left',
			'top right'     => 'Top Right',
			'bottom center' => 'Bottom Center',
			'bottom left'   => 'Bottom Left',
			'bottom right'  => 'Bottom Right',
		];
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_bridge' ) ) {
	/**
	 * Build gradient `fields_options` that emit CSS variables only.
	 *
	 * Every visible gradient field emits a variable, except fields listed in
	 * `$hidden`, which are hidden because the CSS keeps their value fixed.
	 *
	 * @param array<string, string> $vars    Field name => CSS variable name.
	 * @param string                $type    Gradient type, linear or radial.
	 * @param array<string, mixed>  $options Optional `hidden` fields and `position_options`.
	 * @return array<string, array<string, mixed>>
	 */
	function nexora_ph_style_colors_bridge( array $vars, string $type = 'linear', array $options = [] ): array {
		$value_templates = [
			'color'             => '{{VALUE}}',
			'color_b'           => '{{VALUE}}',
			'color_stop'        => '{{SIZE}}{{UNIT}}',
			'color_b_stop'      => '{{SIZE}}{{UNIT}}',
			'gradient_angle'    => '{{SIZE}}{{UNIT}}',
			'gradient_position' => '{{VALUE}}',
		];

		$fields_options = [
			'gradient_type' => [
				'options' => [ $type => 'radial' === $type ? 'Radial' : 'Linear' ],
			],
		];

		foreach ( $vars as $field => $variable ) {
			if ( ! isset( $value_templates[ $field ] ) ) {
				continue;
			}
			$fields_options[ $field ]['selectors'] = [
				'{{SELECTOR}}' => $variable . ': ' . $value_templates[ $field ] . ';',
			];
		}

		$hidden = isset( $options['hidden'] ) && is_array( $options['hidden'] ) ? $options['hidden'] : [];
		foreach ( $hidden as $field ) {
			$fields_options[ $field ]['condition'] = [ 'background' => '__fixed_in_css__' ];
		}

		if ( ! empty( $options['position_options'] ) && is_array( $options['position_options'] ) ) {
			$fields_options['gradient_position']['options'] = $options['position_options'];
		}

		return $fields_options;
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_linear_gradient' ) ) {
	/**
	 * Build a two-stop linear gradient control owned by CSS variables.
	 *
	 * @param string $name       Inventory name.
	 * @param string $label      Control label.
	 * @param string $group      Control group.
	 * @param string $selector   Elementor selector.
	 * @param string $prefix     CSS variable prefix without dashes, such as ph-logos-av1.
	 * @param string $start      Start color.
	 * @param string $end        End color.
	 * @param int    $angle      Gradient angle in degrees.
	 * @param int    $start_stop Start stop percentage.
	 * @param int    $end_stop   End stop percentage.
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_linear_gradient(
		string $name,
		string $label,
		string $group,
		string $selector,
		string $prefix,
		string $start,
		string $end,
		int $angle,
		int $start_stop = 0,
		int $end_stop = 100
	): array {
		return [
			'name'           => $name,
			'label'          => $label,
			'group'          => $group,
			'selector'       => $selector,
			'default'        => [
				'background'     => 'gradient',
				'color'          => $start,
				'color_b'        => $end,
				'color_stop'     => [ 'unit' => '%', 'size' => $start_stop ],
				'color_b_stop'   => [ 'unit' => '%', 'size' => $end_stop ],
				'gradient_type'  => 'linear',
				'gradient_angle' => [ 'unit' => 'deg', 'size' => $angle ],
			],
			'signature'      => sprintf(
				'linear-gradient(var(--%1$s-angle,%2$ddeg),var(--%1$s,%3$s) var(--%1$s-start-stop,%4$d%%),var(--%1$s-end,%5$s) var(--%1$s-end-stop,%6$d%%))',
				$prefix,
				$angle,
				$start,
				$start_stop,
				$end,
				$end_stop
			),
			'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( $prefix ) ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_glass_gradient' ) ) {
	/**
	 * Build a three-stop translucent glass gradient owned by CSS variables.
	 *
	 * The picker owns the outer stops. The tinted middle stop is a solid control
	 * (`{prefix}-mid`) blended at a fixed opacity with color-mix, so the original
	 * rgba middle tint is preserved while its color stays editable.
	 *
	 * @param string $name        Inventory name.
	 * @param string $label       Control label.
	 * @param string $group       Control group.
	 * @param string $selector    Elementor selector.
	 * @param string $prefix      CSS variable prefix without dashes, such as ph-nav-bar.
	 * @param string $start       Start color.
	 * @param string $end         End color.
	 * @param int    $angle       Gradient angle in degrees.
	 * @param string $mid_default Middle tint as a solid hex.
	 * @param int    $mid_alpha   Middle tint opacity percentage.
	 * @param int    $mid_stop    Middle stop percentage.
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_glass_gradient(
		string $name,
		string $label,
		string $group,
		string $selector,
		string $prefix,
		string $start,
		string $end,
		int $angle,
		string $mid_default,
		int $mid_alpha,
		int $mid_stop
	): array {
		$entry              = nexora_ph_style_colors_linear_gradient( $name, $label, $group, $selector, $prefix, $start, $end, $angle );
		$entry['signature'] = sprintf(
			'linear-gradient(var(--%1$s-angle,%2$ddeg),var(--%1$s,%3$s) var(--%1$s-start-stop,0%%),color-mix(in srgb,var(--%1$s-mid,%4$s) %5$d%%,transparent) %6$d%%,var(--%1$s-end,%7$s) var(--%1$s-end-stop,100%%))',
			$prefix,
			$angle,
			$start,
			$mid_default,
			$mid_alpha,
			$mid_stop,
			$end
		);
		$entry['notes'] = sprintf( 'The matching middle-tint solid stays the fixed middle stop (%d%%, %d%% opacity) so the two-stop picker controls the outer colors.', $mid_stop, $mid_alpha );
		return $entry;
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_solid_mid_gradient' ) ) {
	/**
	 * Build a three-stop opaque linear gradient owned by CSS variables.
	 *
	 * The picker owns the outer stops. The fixed middle stop is a solid control
	 * (`{prefix}-mid`, so the inventory token is `{name-without-widget-prefix}-mid`).
	 *
	 * @param string $name        Inventory name.
	 * @param string $label       Control label.
	 * @param string $group       Control group.
	 * @param string $selector    Elementor selector.
	 * @param string $prefix      CSS variable prefix without dashes, such as ph-faq-open.
	 * @param string $start       Start color.
	 * @param string $end         End color.
	 * @param int    $angle       Gradient angle in degrees.
	 * @param string $mid_default Middle stop as a solid hex.
	 * @param int    $mid_stop    Middle stop percentage.
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_solid_mid_gradient(
		string $name,
		string $label,
		string $group,
		string $selector,
		string $prefix,
		string $start,
		string $end,
		int $angle,
		string $mid_default,
		int $mid_stop,
		int $end_stop = 100
	): array {
		$entry              = nexora_ph_style_colors_linear_gradient( $name, $label, $group, $selector, $prefix, $start, $end, $angle, 0, $end_stop );
		$entry['signature'] = sprintf(
			'linear-gradient(var(--%1$s-angle,%2$ddeg),var(--%1$s,%3$s) var(--%1$s-start-stop,0%%),var(--%1$s-mid,%4$s) %5$d%%,var(--%1$s-end,%6$s) var(--%1$s-end-stop,%7$d%%))',
			$prefix,
			$angle,
			$start,
			$mid_default,
			$mid_stop,
			$end,
			$end_stop
		);
		$entry['notes'] = sprintf( 'The matching middle solid stays the fixed middle stop (%d%%) so the two-stop picker controls the outer colors.', $mid_stop );
		return $entry;
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_radial_gradient' ) ) {
	/**
	 * Build one radial gradient layer owned by CSS variables.
	 *
	 * @param string $name     Inventory name.
	 * @param string $label    Control label.
	 * @param string $group    Control group.
	 * @param string $selector Elementor selector.
	 * @param string $prefix   CSS variable prefix without dashes.
	 * @param string $size     Original CSS radial size, such as 700px 420px.
	 * @param string $origin   Original CSS radial origin, such as 88% 0%.
	 * @param string $start    Start color.
	 * @param string $end      End color (transparent stop spelled as zero alpha).
	 * @param int    $end_stop End stop percentage.
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_radial_gradient(
		string $name,
		string $label,
		string $group,
		string $selector,
		string $prefix,
		string $size,
		string $origin,
		string $start,
		string $end,
		int $end_stop,
		bool $lock_origin = false
	): array {
		$vars    = nexora_ph_style_colors_vars( $prefix, 'radial' );
		$options = [];
		if ( $lock_origin ) {
			unset( $vars['gradient_position'] );
			$options['hidden'] = [ 'gradient_position' ];
			$at                = $origin;
		} else {
			$options['position_options'] = nexora_ph_style_colors_position_options( $origin );
			$at                          = sprintf( 'var(--%s-position,%s)', $prefix, $origin );
		}

		return [
			'name'           => $name,
			'label'          => $label,
			'group'          => $group,
			'selector'       => $selector,
			'default'        => [
				'background'        => 'gradient',
				'color'             => $start,
				'color_b'           => $end,
				'color_stop'        => [ 'unit' => '%', 'size' => 0 ],
				'color_b_stop'      => [ 'unit' => '%', 'size' => $end_stop ],
				'gradient_type'     => 'radial',
				'gradient_position' => $origin,
			],
			'signature'      => sprintf(
				'radial-gradient(%1$s at %2$s,var(--%3$s,%4$s) var(--%3$s-start-stop,0%%),var(--%3$s-end,%5$s) var(--%3$s-end-stop,%6$d%%))',
				$size,
				$at,
				$prefix,
				$start,
				$end,
				$end_stop
			),
			'fields_options' => nexora_ph_style_colors_bridge( $vars, 'radial', $options ),
		] + ( $lock_origin
			? [ 'notes' => 'Radial origin stays fixed in CSS so Elementor cannot replace the design position with center center.' ]
			: [] );
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_stops_gradient' ) ) {
	/**
	 * Build a linear or radial gradient control with any number of stops.
	 *
	 * The picker owns the first and last stops (color and position) plus the angle
	 * or radial origin. Every stop between them is a fixed middle stop whose color
	 * is a solid control (`{prefix}-mid`, `{prefix}-mid-2`, ...). A middle stop may
	 * carry a third value, an opacity percentage, to keep a translucent tint.
	 * With `fixed` the stop positions stay in CSS (for example `1.1px` dots) and
	 * the position pickers are hidden.
	 *
	 * Spec keys: name, label, group, selector, prefix, type (linear|radial), angle
	 * (linear), size and origin (radial; an empty origin keeps the default
	 * position), stops (list of [color, position, optional mid opacity]), fixed.
	 *
	 * @param array<string, mixed> $spec Gradient spec.
	 * @return array<string, mixed>
	 */
	function nexora_ph_style_colors_stops_gradient( array $spec ): array {
		$prefix = (string) $spec['prefix'];
		$type   = isset( $spec['type'] ) ? (string) $spec['type'] : 'linear';
		$stops  = array_values( $spec['stops'] );
		$fixed  = ! empty( $spec['fixed'] );
		$last   = count( $stops ) - 1;
		$origin = isset( $spec['origin'] ) ? (string) $spec['origin'] : '';
		$format = static function ( $number ): string {
			return rtrim( rtrim( sprintf( '%.2F', (float) $number ), '0' ), '.' );
		};

		$parts = [];
		foreach ( $stops as $index => $stop ) {
			$color    = (string) $stop[0];
			$position = $stop[1];
			if ( 0 === $index ) {
				$text = sprintf( 'var(--%s,%s)', $prefix, $color );
				$text .= $fixed ? ' ' . $position : sprintf( ' var(--%s-start-stop,%s%%)', $prefix, $format( $position ) );
			} elseif ( $index === $last ) {
				$text = sprintf( 'var(--%s-end,%s)', $prefix, $color );
				$text .= $fixed ? ' ' . $position : sprintf( ' var(--%s-end-stop,%s%%)', $prefix, $format( $position ) );
			} else {
				$mid  = sprintf( 'var(--%s-mid%s,%s)', $prefix, $index > 1 ? '-' . $index : '', $color );
				$text = isset( $stop[2] )
					? sprintf( 'color-mix(in srgb,%s %s%%,transparent)', $mid, $format( $stop[2] ) )
					: $mid;
				$text .= ' ' . ( $fixed ? $position : $format( $position ) . '%' );
			}
			$parts[] = $text;
		}

		$default = [
			'background'    => 'gradient',
			'color'         => (string) $stops[0][0],
			'color_b'       => (string) $stops[ $last ][0],
			'gradient_type' => $type,
		];
		if ( ! $fixed ) {
			$default['color_stop']   = [ 'unit' => '%', 'size' => $stops[0][1] ];
			$default['color_b_stop'] = [ 'unit' => '%', 'size' => $stops[ $last ][1] ];
		}

		$options = [];
		if ( $fixed ) {
			$options['hidden'] = [ 'color_stop', 'color_b_stop' ];
		}

		if ( 'radial' === $type ) {
			$size = isset( $spec['size'] ) ? (string) $spec['size'] : 'circle';
			if ( '' !== $origin ) {
				$head                         = sprintf( '%1$s at var(--%2$s-position,%3$s)', $size, $prefix, $origin );
				$default['gradient_position'] = $origin;
				$options['position_options']  = nexora_ph_style_colors_position_options( $origin );
			} else {
				$head                         = '';
				$options['hidden'][]          = 'gradient_position';
				$default['gradient_position'] = 'center center';
			}
		} else {
			$angle                     = (int) $spec['angle'];
			$head                      = sprintf( 'var(--%s-angle,%ddeg)', $prefix, $angle );
			$default['gradient_angle'] = [ 'unit' => 'deg', 'size' => $angle ];
		}

		$vars = nexora_ph_style_colors_vars( $prefix, $type );
		if ( $fixed ) {
			unset( $vars['color_stop'], $vars['color_b_stop'] );
		}
		if ( 'radial' === $type && '' === $origin ) {
			unset( $vars['gradient_position'] );
		}

		$entry = [
			'name'           => (string) $spec['name'],
			'label'          => (string) $spec['label'],
			'group'          => (string) $spec['group'],
			'selector'       => (string) $spec['selector'],
			'default'        => $default,
			'signature'      => sprintf( '%s-gradient(%s)', $type, implode( ',', array_filter( array_merge( [ $head ], $parts ), 'strlen' ) ) ),
			'fields_options' => nexora_ph_style_colors_bridge( $vars, $type, $options ),
		];
		if ( $last > 1 ) {
			$entry['notes'] = 'The middle stops are solid controls (' . $prefix . '-mid ...) so the two-stop picker controls the outer colors.';
		}

		return $entry;
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_shared_subset' ) ) {
	/**
	 * Pick only the shared catalog entries a widget actually renders.
	 *
	 * @param list<string> $solid_tokens    Shared solid tokens.
	 * @param list<string> $gradient_names  Shared gradient names.
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>}
	 */
	function nexora_ph_style_colors_shared_subset( array $solid_tokens, array $gradient_names, array $label_overrides = [] ): array {
		$catalog = nexora_ph_style_colors_shared_catalog();

		$slice = [
			'solids'    => array_values(
				array_filter(
					$catalog['solids'],
					static fn( array $solid ): bool => in_array( $solid['token'], $solid_tokens, true )
				)
			),
			'gradients' => array_values(
				array_filter(
					$catalog['gradients'],
					static fn( array $gradient ): bool => in_array( $gradient['name'], $gradient_names, true )
				)
			),
		];

		if ( ! $label_overrides ) {
			return $slice;
		}

		return nexora_ph_style_colors_apply_labels(
			$slice,
			isset( $label_overrides['solids'] ) && is_array( $label_overrides['solids'] ) ? $label_overrides['solids'] : [],
			isset( $label_overrides['gradients'] ) && is_array( $label_overrides['gradients'] ) ? $label_overrides['gradients'] : []
		);
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_apply_labels' ) ) {
	/**
	 * Overlay paint-role labels onto solids (by token) and gradients (by name).
	 *
	 * @param array{solids?: list<array<string, mixed>>, gradients?: list<array<string, mixed>>} $slice Inventory slice.
	 * @param array<string, string>                                                               $solid_labels    token => label.
	 * @param array<string, string>                                                               $gradient_labels name => label.
	 * @return array{solids: list<array<string, mixed>>, gradients: list<array<string, mixed>>}
	 */
	function nexora_ph_style_colors_apply_labels( array $slice, array $solid_labels = [], array $gradient_labels = [] ): array {
		$solids = isset( $slice['solids'] ) && is_array( $slice['solids'] ) ? $slice['solids'] : [];
		$grads  = isset( $slice['gradients'] ) && is_array( $slice['gradients'] ) ? $slice['gradients'] : [];

		foreach ( $solids as $i => $solid ) {
			if ( ! is_array( $solid ) ) {
				continue;
			}
			$token = isset( $solid['token'] ) ? (string) $solid['token'] : '';
			if ( '' !== $token && isset( $solid_labels[ $token ] ) ) {
				$solids[ $i ]['label'] = (string) $solid_labels[ $token ];
			}
		}

		foreach ( $grads as $i => $gradient ) {
			if ( ! is_array( $gradient ) ) {
				continue;
			}
			$name = isset( $gradient['name'] ) ? (string) $gradient['name'] : '';
			if ( '' !== $name && isset( $gradient_labels[ $name ] ) ) {
				$grads[ $i ]['label'] = (string) $gradient_labels[ $name ];
			}
		}

		return [
			'solids'    => array_values( $solids ),
			'gradients' => array_values( $grads ),
		];
	}
}

if ( ! function_exists( 'nexora_ph_style_colors_shared_catalog' ) ) {
	/**
	 * Return shared solid and gradient controls used by PH widgets.
	 *
	 * @return array<string, array<int, array<string, mixed>>>
	 */
	function nexora_ph_style_colors_shared_catalog(): array {
		return [
			'solids'    => [
				[
					'token'   => 'ink',
					'label'   => 'Heading / body text',
					'default' => '#0B1620',
					'group'   => 'text',
					'shared'  => true,
				],
				[
					'token'   => 'muted',
					'label'   => 'Description / captions',
					'default' => '#56626D',
					'group'   => 'text',
					'shared'  => true,
				],
				[
					'token'   => 'lead',
					'label'   => 'Supporting text',
					'default' => '#3A4753',
					'group'   => 'text',
					'shared'  => true,
				],
				[
					'token'   => 'on-accent',
					'label'   => 'Text on buttons / accent fills',
					'default' => '#FFFFFF',
					'group'   => 'text',
					'shared'  => true,
				],
				[
					'token'   => 'on-dark',
					'label'   => 'Title on dark / card title (hover)',
					'default' => '#FFFFFF',
					'group'   => 'text',
					'shared'  => true,
				],
				[
					'token'   => 'lead-on-dark',
					'label'   => 'Description on dark / card description (hover)',
					'default' => '#C9D3DC',
					'group'   => 'text',
					'shared'  => true,
				],
				[
					'token'   => 'muted-on-dark',
					'label'   => 'Muted text on dark',
					'default' => '#A9B6C2',
					'group'   => 'text',
					'shared'  => true,
				],
				[
					'token'   => 'tag-on-dark',
					'label'   => 'Tag text on dark',
					'default' => '#FFC2A6',
					'group'   => 'tag',
					'shared'  => true,
				],
				[
					'token'   => 'chip-on-dark',
					'label'   => 'Chip text (hover)',
					'default' => '#DCE4EA',
					'group'   => 'tag',
					'shared'  => true,
				],
				[
					'token'   => 'more-on-dark',
					'label'   => 'Learn more link (hover)',
					'default' => '#FFB08C',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'accent',
					'label'   => 'Accent / highlight',
					'default' => '#F15E22',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'link',
					'label'   => 'Link / icon stroke',
					'default' => '#C2410C',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'link-blue',
					'label'   => 'Blue link / chip text',
					'default' => '#1A5A87',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'blue-accent',
					'label'   => 'Blue accent / tag dot',
					'default' => '#2374AC',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'grad-warm',
					'label'   => 'Heading gradient — warm stop',
					'default' => '#FF8A4C',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'grad-rose',
					'label'   => 'Heading gradient — rose stop',
					'default' => '#E0617A',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'grad-sky',
					'label'   => 'Heading gradient — sky stop',
					'default' => '#2B8AE0',
					'group'   => 'accent',
					'shared'  => true,
				],
				[
					'token'   => 'surface',
					'label'   => 'Section / card surface',
					'default' => '#FFFFFF',
					'group'   => 'surface',
					'shared'  => true,
				],
				[
					'token'   => 'card-border',
					'label'   => 'Card border',
					'default' => '#EFE7DE',
					'group'   => 'surface',
					'shared'  => true,
				],
				[
					'token'   => 'card-hover-border',
					'label'   => 'Card border (hover)',
					'default' => '#F6C3AA',
					'group'   => 'surface',
					'shared'  => true,
				],
				[
					'token'   => 'btn-mid',
					'label'   => 'Primary button middle stop',
					'default' => '#F15E22',
					'group'   => 'button',
					'shared'  => true,
				],
				[
					'token'   => 'btn-white-border',
					'label'   => 'Secondary button border',
					'default' => '#E6DDD3',
					'group'   => 'button',
					'shared'  => true,
				],
				[
					'token'   => 'btn-case-surface',
					'label'   => 'Case study button surface',
					'default' => '#FFF4EC',
					'group'   => 'button',
					'shared'  => true,
				],
				[
					'token'   => 'tag-border',
					'label'   => 'Eyebrow tag border',
					'default' => '#FBD3BF',
					'group'   => 'tag',
					'shared'  => true,
				],
				[
					'token'   => 'tag-blue-border',
					'label'   => 'Blue eyebrow tag border',
					'default' => '#C9E0F1',
					'group'   => 'tag',
					'shared'  => true,
				],
				[
					'token'   => 'chip-blue',
					'label'   => 'Blue chip surface',
					'default' => '#EEF5FB',
					'group'   => 'tag',
					'shared'  => true,
				],
				[
					'token'   => 'chip-orange',
					'label'   => 'Orange chip surface',
					'default' => '#FFF1E9',
					'group'   => 'tag',
					'shared'  => true,
				],
				[
					'token'   => 'chip-orange-text',
					'label'   => 'Orange chip text',
					'default' => '#B03E0B',
					'group'   => 'tag',
					'shared'  => true,
				],
				[
					'token'   => 'soft-surface',
					'label'   => 'Soft section background',
					'default' => '#FFF9F5',
					'group'   => 'fill',
					'shared'  => true,
				],
				[
					'token'   => 'cool-surface',
					'label'   => 'Cool section background',
					'default' => '#F7FAFD',
					'group'   => 'fill',
					'shared'  => true,
				],
				[
					'token'   => 'ink-surface',
					'label'   => 'Dark section background',
					'default' => '#0B1620',
					'group'   => 'fill',
					'shared'  => true,
				],
			],
			'gradients' => [
				[
					'name'     => 'btn_primary',
					'label'    => 'Primary button',
					'group'    => 'button',
					'selector' => '{{WRAPPER}} .nexora-ph a.btn-primary, {{WRAPPER}} .nexora-ph .btn-primary',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#FF8350',
						'color_b'        => '#DC5016',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
					'signature'      => 'linear-gradient(var(--ph-btn-angle,180deg),var(--ph-btn-soft,#FF8350) var(--ph-btn-start-stop,0%),var(--ph-btn-mid,#F15E22) 55%,var(--ph-btn-deep,#DC5016) var(--ph-btn-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge(
						[
							'color'          => '--ph-btn-soft',
							'color_b'        => '--ph-btn-deep',
							'color_stop'     => '--ph-btn-start-stop',
							'color_b_stop'   => '--ph-btn-end-stop',
							'gradient_angle' => '--ph-btn-angle',
						]
					),
					'notes'    => 'Three-stop CSS-var bridge keeps --ph-btn-mid (#F15E22) at 55%; the gradient control owns the outer stops.',
				],
				[
					'name'     => 'btn_dark',
					'label'    => 'Dark button',
					'group'    => 'button',
					'selector' => '{{WRAPPER}} .nexora-ph a.btn-dark, {{WRAPPER}} .nexora-ph .btn-dark',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#223647',
						'color_b'        => '#0B1620',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
					'signature'      => 'linear-gradient(var(--ph-btn-dark-angle,180deg),var(--ph-btn-dark,#223647) var(--ph-btn-dark-start-stop,0%),var(--ph-btn-dark-end,#0B1620) var(--ph-btn-dark-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-btn-dark' ) ),
				],
				[
					'name'           => 'btn_dark_hover',
					'label'          => 'Dark button (hover)',
					'group'          => 'button',
					'selector'       => '{{WRAPPER}} .nexora-ph a.btn-dark, {{WRAPPER}} .nexora-ph .btn-dark',
					'default'        => [
						'background'     => 'gradient',
						'color'          => '#FF8350',
						'color_b'        => '#DC5016',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
					'signature'      => 'linear-gradient(var(--ph-btn-dark-hover-angle,180deg),var(--ph-btn-dark-hover,#FF8350) var(--ph-btn-dark-hover-start-stop,0%),var(--ph-btn-mid,#F15E22) 55%,var(--ph-btn-dark-hover-end,#DC5016) var(--ph-btn-dark-hover-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-btn-dark-hover' ) ),
					'notes'          => 'Three-stop CSS-var bridge keeps --ph-btn-mid (#F15E22) at 55%; the gradient control owns the outer stops.',
				],
				[
					'name'     => 'btn_white',
					'label'    => 'White button',
					'group'    => 'button',
					'selector' => '{{WRAPPER}} .nexora-ph a.btn-white, {{WRAPPER}} .nexora-ph .btn-white',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#FFFFFF',
						'color_b'        => '#F6F2EE',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
				],
				[
					'name'     => 'btn_case',
					'label'    => 'Case study button (hover)',
					'group'    => 'button',
					'selector' => '{{WRAPPER}} .nexora-ph a.btn-case:hover, {{WRAPPER}} .nexora-ph a.btn-case:focus-visible',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#FF8350',
						'color_b'        => '#DC5016',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
					'signature'      => 'linear-gradient(var(--ph-btn-angle,180deg),var(--ph-btn-soft,#FF8350) var(--ph-btn-start-stop,0%),var(--ph-btn-mid,#F15E22) 55%,var(--ph-btn-deep,#DC5016) var(--ph-btn-end-stop,100%))',
					'fields_options' => [
						'color'          => [
							'selectors' => [ '{{SELECTOR}}' => '--ph-btn-soft: {{VALUE}};' ],
						],
						'color_b'        => [
							'selectors' => [ '{{SELECTOR}}' => '--ph-btn-deep: {{VALUE}};' ],
						],
						'color_stop'     => [
							'selectors' => [ '{{SELECTOR}}' => '--ph-btn-start-stop: {{SIZE}}{{UNIT}};' ],
						],
						'color_b_stop'   => [
							'selectors' => [ '{{SELECTOR}}' => '--ph-btn-end-stop: {{SIZE}}{{UNIT}};' ],
						],
						'gradient_type'  => [
							'options' => [ 'linear' => 'Linear' ],
						],
						'gradient_angle' => [
							'selectors' => [ '{{SELECTOR}}' => '--ph-btn-angle: {{SIZE}}{{UNIT}};' ],
						],
					],
					'notes'    => 'Three-stop CSS-var bridge keeps --ph-btn-mid (#F15E22) at 55%; the gradient control owns the outer stops.',
				],
				[
					'name'     => 'tag',
					'label'    => 'Eyebrow tag',
					'group'    => 'tag',
					'selector' => '{{WRAPPER}} .nexora-ph .tag:not(.blue)',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#FFF3EC',
						'color_b'        => '#FFE6D9',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
					'signature'      => 'linear-gradient(var(--ph-tag-angle,180deg),var(--ph-tag-start,#FFF3EC) var(--ph-tag-start-stop,0%),var(--ph-tag-end,#FFE6D9) var(--ph-tag-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge(
						[
							'color'          => '--ph-tag-start',
							'color_b'        => '--ph-tag-end',
							'color_stop'     => '--ph-tag-start-stop',
							'color_b_stop'   => '--ph-tag-end-stop',
							'gradient_angle' => '--ph-tag-angle',
						]
					),
				],
				nexora_ph_style_colors_linear_gradient( 'tag_blue', 'Blue eyebrow tag', 'tag', '{{WRAPPER}} .nexora-ph .tag.blue', 'ph-tag-blue', '#F2F8FD', '#E1EEF8', 180 ),
				[
					'name'     => 'icon',
					'label'    => 'Accent icon tile',
					'group'    => 'fill',
					'selector' => '{{WRAPPER}} .nexora-ph .icon',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#FF8350',
						'color_b'        => '#D94E14',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 145 ],
					],
					'signature'      => 'linear-gradient(var(--ph-icon-angle,145deg),var(--ph-icon-start,#FF8350) var(--ph-icon-start-stop,0%),var(--ph-btn-mid,#F15E22) 60%,var(--ph-icon-end,#D94E14) var(--ph-icon-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge(
						[
							'color'          => '--ph-icon-start',
							'color_b'        => '--ph-icon-end',
							'color_stop'     => '--ph-icon-start-stop',
							'color_b_stop'   => '--ph-icon-end-stop',
							'gradient_angle' => '--ph-icon-angle',
						]
					),
					'notes'    => 'Three-stop CSS-var bridge keeps --ph-btn-mid (#F15E22) at 60%; the gradient control owns the outer stops.',
				],
				nexora_ph_style_colors_linear_gradient( 'icon_o', 'Orange icon tile', 'fill', '{{WRAPPER}} .nexora-ph', 'ph-icon-o', '#FFF3EC', '#FFE0CF', 145 ),
				[
					'name'     => 'icon_b',
					'label'    => 'Blue icon tile',
					'group'    => 'fill',
					'selector' => '{{WRAPPER}} .nexora-ph',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#F0F7FC',
						'color_b'        => '#D8EAF6',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 145 ],
					],
					'signature'      => 'linear-gradient(var(--ph-icon-b-angle,145deg),var(--ph-icon-b-start,#F0F7FC) var(--ph-icon-b-start-stop,0%),var(--ph-icon-b-end,#D8EAF6) var(--ph-icon-b-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge(
						[
							'color'          => '--ph-icon-b-start',
							'color_b'        => '--ph-icon-b-end',
							'color_stop'     => '--ph-icon-b-start-stop',
							'color_b_stop'   => '--ph-icon-b-end-stop',
							'gradient_angle' => '--ph-icon-b-angle',
						]
					),
				],
				[
					'name'     => 'card',
					'label'    => 'Card background',
					'group'    => 'fill',
					'selector' => '{{WRAPPER}} .nexora-ph',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#FFFFFF',
						'color_b'        => '#FFFCFA',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
					'signature'      => 'linear-gradient(var(--ph-card-angle,180deg),var(--ph-card,#FFFFFF) var(--ph-card-start-stop,0%),var(--ph-card-end,#FFFCFA) var(--ph-card-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-card' ) ),
					'notes'          => 'Vars are set on the widget root so Elementor cannot paint over .card / .hv-card fills.',
				],
				nexora_ph_style_colors_radial_gradient( 'soft_orange', 'Soft section glow — orange', 'fill', '{{WRAPPER}} .nexora-ph .soft', 'ph-soft-orange', '760px 420px', '100% 0%', 'rgba(241,94,34,.09)', 'rgba(241,94,34,0)', 62, true ),
				nexora_ph_style_colors_radial_gradient( 'soft_blue', 'Soft section glow — blue', 'fill', '{{WRAPPER}} .nexora-ph .soft', 'ph-soft-blue', '760px 420px', '0% 100%', 'rgba(35,116,172,.09)', 'rgba(35,116,172,0)', 62, true ),
				nexora_ph_style_colors_radial_gradient( 'cool_blue', 'Cool section glow — blue', 'fill', '{{WRAPPER}} .nexora-ph .cool', 'ph-cool-blue', '760px 420px', '0% 0%', 'rgba(35,116,172,.08)', 'rgba(35,116,172,0)', 62, true ),
				nexora_ph_style_colors_radial_gradient( 'cool_orange', 'Cool section glow — orange', 'fill', '{{WRAPPER}} .nexora-ph .cool', 'ph-cool-orange', '760px 420px', '100% 100%', 'rgba(241,94,34,.07)', 'rgba(241,94,34,0)', 62, true ),
				nexora_ph_style_colors_radial_gradient( 'ink_orange', 'Dark section glow — orange', 'fill', '{{WRAPPER}} .nexora-ph .ink', 'ph-ink-orange', '700px 420px', '90% 0%', 'rgba(241,94,34,.28)', 'rgba(241,94,34,0)', 62, true ),
				nexora_ph_style_colors_radial_gradient( 'ink_blue', 'Dark section glow — blue', 'fill', '{{WRAPPER}} .nexora-ph .ink', 'ph-ink-blue', '700px 420px', '0% 100%', 'rgba(35,116,172,.36)', 'rgba(35,116,172,0)', 62, true ),
				[
					'name'     => 'grad_text',
					'label'    => 'Heading highlight gradient',
					'group'    => 'fill',
					'selector' => '{{WRAPPER}} .nexora-ph .grad-text',
					'default'  => [
						'background'     => 'gradient',
						'color'          => '#F15E22',
						'color_b'        => '#F15E22',
						'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
						'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 90 ],
					],
					'signature'      => 'linear-gradient(var(--ph-grad-angle,90deg),var(--ph-grad-start,#F15E22) var(--ph-grad-start-stop,0%),var(--ph-grad-warm,#FF8A4C) 20%,var(--ph-grad-rose,#E0617A) 36%,var(--ph-grad-sky,#2B8AE0) 52%,var(--ph-grad-rose,#E0617A) 68%,var(--ph-grad-warm,#FF8A4C) 84%,var(--ph-grad-end,#F15E22) var(--ph-grad-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge(
						[
							'color'          => '--ph-grad-start',
							'color_b'        => '--ph-grad-end',
							'color_stop'     => '--ph-grad-start-stop',
							'color_b_stop'   => '--ph-grad-end-stop',
							'gradient_angle' => '--ph-grad-angle',
						]
					),
					'notes'    => 'Restores the shipped effective look: 90deg, seven stops #F15E22 0%, #FF8A4C 20%, #E0617A 36%, #2B8AE0 52%, #E0617A 68%, #FF8A4C 84%, #F15E22 100%, 200% 100% repeating, shine2 9s. Elementor edits the two outer stops and angle; the fixed mid stops bridge through the shared grad-warm, grad-rose and grad-sky tokens at their original positions.',
				],
				nexora_ph_style_colors_radial_gradient( 'mid_orange', 'Mid section glow — orange', 'fill', '{{WRAPPER}} .nexora-ph .mid', 'ph-mid-orange', '700px 420px', '88% 0%', 'rgba(241,94,34,.32)', 'rgba(241,94,34,0)', 60, true ),
				nexora_ph_style_colors_radial_gradient( 'mid_blue', 'Mid section glow — blue', 'fill', '{{WRAPPER}} .nexora-ph .mid', 'ph-mid-blue', '760px 460px', '0% 100%', 'rgba(35,116,172,.42)', 'rgba(35,116,172,0)', 60, true ),
				nexora_ph_style_colors_linear_gradient( 'mid_base', 'Mid section background', 'fill', '{{WRAPPER}} .nexora-ph .mid', 'ph-mid-base', '#16283C', '#0B1620', 160, 0, 62 ),
				nexora_ph_style_colors_radial_gradient( 'ivory_orange', 'Ivory section glow — orange', 'fill', '{{WRAPPER}} .nexora-ph .ivory', 'ph-ivory-orange', '820px 460px', '100% 0%', 'rgba(241,94,34,.10)', 'rgba(241,94,34,0)', 62, true ),
				nexora_ph_style_colors_radial_gradient( 'ivory_blue', 'Ivory section glow — blue', 'fill', '{{WRAPPER}} .nexora-ph .ivory', 'ph-ivory-blue', '820px 460px', '0% 100%', 'rgba(35,116,172,.10)', 'rgba(35,116,172,0)', 62, true ),
				nexora_ph_style_colors_linear_gradient( 'ivory_base', 'Ivory section background', 'fill', '{{WRAPPER}} .nexora-ph .ivory', 'ph-ivory-base', '#FFFCF9', '#FFF6F0', 180 ),
				nexora_ph_style_colors_linear_gradient( 'check', 'Checkmark badge', 'fill', '{{WRAPPER}} .nexora-ph .check', 'ph-check', '#FF8350', '#F15E22', 145 ),
				nexora_ph_style_colors_radial_gradient( 'hv_orange', 'Card panel glow — orange (hover)', 'fill', '{{WRAPPER}} .nexora-ph', 'ph-hv-orange', '320px 200px', '100% 0%', 'rgba(241,94,34,.38)', 'rgba(241,94,34,0)', 70, true ),
				nexora_ph_style_colors_radial_gradient( 'hv_blue', 'Card panel glow — blue (hover)', 'fill', '{{WRAPPER}} .nexora-ph', 'ph-hv-blue', '320px 220px', '0% 100%', 'rgba(35,116,172,.45)', 'rgba(35,116,172,0)', 70, true ),
				nexora_ph_style_colors_linear_gradient( 'hv_dark', 'Card panel (hover)', 'fill', '{{WRAPPER}} .nexora-ph', 'ph-hv-dark', '#16283C', '#0B1620', 160, 0, 70 ),
				[
					'name'           => 'hv_icon',
					'label'          => 'Card icon tile (hover)',
					'group'          => 'fill',
					'selector'       => '{{WRAPPER}} .nexora-ph',
					'default'        => [
						'background'     => 'gradient',
						'color'          => '#FF8350',
						'color_b'        => '#D94E14',
						'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
						'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 145 ],
					],
					'signature'      => 'linear-gradient(var(--ph-hv-icon-angle,145deg),var(--ph-hv-icon,#FF8350) var(--ph-hv-icon-start-stop,0%),var(--ph-btn-mid,#F15E22) 60%,var(--ph-hv-icon-end,#D94E14) var(--ph-hv-icon-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-hv-icon' ) ),
					'notes'          => 'Three-stop CSS-var bridge keeps --ph-btn-mid (#F15E22) at 60%; the gradient control owns the outer stops. Vars are set on the widget root so Elementor cannot paint over .hv-card.',
				],
				[
					'name'           => 'play_btn',
					'label'          => 'Play button',
					'group'          => 'button',
					'selector'       => '{{WRAPPER}} .nexora-ph',
					'default'        => [
						'background'     => 'gradient',
						'color'          => '#FF8A4C',
						'color_b'        => '#D9480F',
						'color_stop'     => [ 'unit' => '%', 'size' => 0 ],
						'color_b_stop'   => [ 'unit' => '%', 'size' => 100 ],
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 145 ],
					],
					'signature'      => 'linear-gradient(var(--ph-play-angle,145deg),var(--ph-play,#FF8A4C) var(--ph-play-start-stop,0%),var(--ph-btn-mid,#F15E22) 60%,var(--ph-play-end,#D9480F) var(--ph-play-end-stop,100%))',
					'fields_options' => nexora_ph_style_colors_bridge( nexora_ph_style_colors_vars( 'ph-play' ) ),
					'notes'          => 'Three-stop CSS-var bridge keeps --ph-btn-mid (#F15E22) at 60%; the gradient control owns the outer stops.',
				],
				nexora_ph_style_colors_radial_gradient( 'media_empty_glow', 'Empty video poster glow', 'fill', '{{WRAPPER}} .nexora-ph .ph-story-poster--empty, {{WRAPPER}} .nexora-ph .ph-video-cover--empty', 'ph-media-empty-glow', '300px 200px', '80% 20%', 'rgba(241,94,34,.35)', 'rgba(241,94,34,0)', 70 ),
				nexora_ph_style_colors_linear_gradient( 'media_empty_base', 'Empty video poster background', 'fill', '{{WRAPPER}} .nexora-ph .ph-story-poster--empty, {{WRAPPER}} .nexora-ph .ph-video-cover--empty', 'ph-media-empty-base', '#16283C', '#0B1620', 160 ),
				[
					'name'           => 'grid_horizontal',
					'label'          => 'Background grid — horizontal',
					'group'          => 'fill',
					'selector'       => '{{WRAPPER}} .nexora-ph .gridlines',
					'default'        => [
						'background'     => 'gradient',
						'color'          => 'rgba(11,22,32,.05)',
						'color_b'        => 'rgba(11,22,32,0)',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 180 ],
					],
					'signature'      => 'linear-gradient(var(--ph-grid-horizontal-angle,180deg),var(--ph-grid-horizontal,rgba(11,22,32,.05)) 1px,var(--ph-grid-horizontal-end,rgba(11,22,32,0)) 1px)',
					'fields_options' => nexora_ph_style_colors_bridge(
						[
							'color'          => '--ph-grid-horizontal',
							'color_b'        => '--ph-grid-horizontal-end',
							'gradient_angle' => '--ph-grid-horizontal-angle',
						],
						'linear',
						[ 'hidden' => [ 'color_stop', 'color_b_stop' ] ]
					),
					'notes'          => 'The 1px line stops are structural and stay fixed in CSS, so the stop pickers are hidden.',
				],
				[
					'name'           => 'grid_vertical',
					'label'          => 'Background grid — vertical',
					'group'          => 'fill',
					'selector'       => '{{WRAPPER}} .nexora-ph .gridlines',
					'default'        => [
						'background'     => 'gradient',
						'color'          => 'rgba(11,22,32,.05)',
						'color_b'        => 'rgba(11,22,32,0)',
						'gradient_type'  => 'linear',
						'gradient_angle' => [ 'unit' => 'deg', 'size' => 90 ],
					],
					'signature'      => 'linear-gradient(var(--ph-grid-vertical-angle,90deg),var(--ph-grid-vertical,rgba(11,22,32,.05)) 1px,var(--ph-grid-vertical-end,rgba(11,22,32,0)) 1px)',
					'fields_options' => nexora_ph_style_colors_bridge(
						[
							'color'          => '--ph-grid-vertical',
							'color_b'        => '--ph-grid-vertical-end',
							'gradient_angle' => '--ph-grid-vertical-angle',
						],
						'linear',
						[ 'hidden' => [ 'color_stop', 'color_b_stop' ] ]
					),
					'notes'          => 'The 1px line stops are structural and stay fixed in CSS, so the stop pickers are hidden.',
				],
			],
		];
	}
}
