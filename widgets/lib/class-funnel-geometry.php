<?php
/**
 * GTM Funnel geometry resolver.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Nexora_Funnel_Geometry' ) ) {

/**
 * Returns exact preset coordinates for the classic 4-tier design when
 * geometry settings are at defaults; otherwise computes parametric paths.
 */
class Nexora_Funnel_Geometry {

	/**
	 * Default geometry parameters (viewBox user units).
	 *
	 * @return array<string, float|int>
	 */
	public static function defaults(): array {
		return [
			'top_width'    => 500,
			'bottom_width' => 208,
			'top_y'        => 28,
			'tier_height'  => 104,
			'tier_gap'     => 8,
			'curve_depth'  => 10,
			'node_offset'  => 30,
			'node_radius'  => 20,
			'view_width'   => 600,
		];
	}

	/**
	 * Exact hand-tuned coordinates for the original 4-tier funnel.
	 *
	 * @return array{tiers: array<int, array>, viewBoxHeight: int}
	 */
	public static function preset_four(): array {
		return [
			'viewBoxHeight' => 480,
			'tiers'         => [
				[
					'path'            => 'M 50 28 Q 300 16 550 28 L 516 138 Q 300 148 84 138 Z',
					'valueY'          => 66,
					'labelY'          => 91,
					'subY'            => 108,
					'nodeX'           => 38,
					'nodeY'           => 82,
					'transformOrigin' => '300px 80px',
					'nodeRadius'      => 20,
				],
				[
					'path'            => 'M 86 146 Q 300 156 514 146 L 476 250 Q 300 260 124 250 Z',
					'valueY'          => 184,
					'labelY'          => 209,
					'subY'            => 226,
					'nodeX'           => 74,
					'nodeY'           => 198,
					'transformOrigin' => '300px 198px',
					'nodeRadius'      => 20,
				],
				[
					'path'            => 'M 126 258 Q 300 268 474 258 L 436 360 Q 300 368 164 360 Z',
					'valueY'          => 295,
					'labelY'          => 320,
					'subY'            => 337,
					'nodeX'           => 114,
					'nodeY'           => 309,
					'transformOrigin' => '300px 309px',
					'nodeRadius'      => 20,
				],
				[
					'path'            => 'M 166 368 Q 300 376 434 368 L 404 460 Q 300 467 196 460 Z',
					'valueY'          => 400,
					'labelY'          => 425,
					'subY'            => 442,
					'nodeX'           => 152,
					'nodeY'           => 414,
					'transformOrigin' => '300px 414px',
					'nodeRadius'      => 20,
				],
			],
		];
	}

	/**
	 * Whether geometry settings match defaults (use exact preset when n=4).
	 *
	 * @param array $settings Geometry settings from Elementor.
	 */
	public static function is_default_geometry( array $settings ): bool {
		$defaults = self::defaults();

		foreach ( $defaults as $key => $value ) {
			if ( 'view_width' === $key ) {
				continue;
			}

			$current = $value;

			if ( isset( $settings[ $key ] ) && is_array( $settings[ $key ] ) && isset( $settings[ $key ]['size'] ) && is_numeric( $settings[ $key ]['size'] ) ) {
				$current = (float) $settings[ $key ]['size'];
			} elseif ( isset( $settings[ $key ] ) && is_numeric( $settings[ $key ] ) ) {
				$current = (float) $settings[ $key ];
			}

			if ( abs( (float) $current - (float) $value ) > 0.01 ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Resolve tier geometry.
	 *
	 * @param int   $tier_count Number of funnel tiers.
	 * @param array $settings   Geometry settings (slider values).
	 * @return array{tiers: array<int, array>, viewBoxHeight: float|int, viewWidth: int}
	 */
	public static function resolve( int $tier_count, array $settings = [] ): array {
		$tier_count = max( 1, min( 24, $tier_count ) );
		$defaults   = self::defaults();

		try {
			if ( 4 === $tier_count && self::is_default_geometry( $settings ) ) {
				$preset              = self::preset_four();
				$preset['viewWidth'] = (int) $defaults['view_width'];
				return $preset;
			}

			return self::generate( $tier_count, $settings );
		} catch ( \Throwable $e ) {
			$preset              = self::preset_four();
			$preset['viewWidth'] = (int) $defaults['view_width'];
			return $preset;
		}
	}

	/**
	 * Parametric funnel path generator.
	 *
	 * @param int   $n        Tier count.
	 * @param array $settings Geometry inputs.
	 * @return array{tiers: array<int, array>, viewBoxHeight: float, viewWidth: int}
	 */
	public static function generate( int $n, array $settings = [] ): array {
		$d = self::defaults();

		$get = static function ( string $key ) use ( $settings, $d ): float {
			if ( isset( $settings[ $key ] ) && is_array( $settings[ $key ] ) && isset( $settings[ $key ]['size'] ) && is_numeric( $settings[ $key ]['size'] ) ) {
				return (float) $settings[ $key ]['size'];
			}
			if ( isset( $settings[ $key ] ) && is_numeric( $settings[ $key ] ) ) {
				return (float) $settings[ $key ];
			}
			return (float) $d[ $key ];
		};

		$top_width    = $get( 'top_width' );
		$bottom_width = $get( 'bottom_width' );
		$top_y        = $get( 'top_y' );
		$tier_height  = $get( 'tier_height' );
		$tier_gap     = $get( 'tier_gap' );
		$curve_depth  = $get( 'curve_depth' );
		$node_offset  = $get( 'node_offset' );
		$node_radius  = $get( 'node_radius' );
		$view_width   = (int) $d['view_width'];
		$cx           = $view_width / 2;

		$span = ( $n * $tier_height ) + ( ( $n - 1 ) * $tier_gap );

		$half_at = static function ( float $y ) use ( $top_y, $span, $top_width, $bottom_width ): float {
			if ( $span <= 0 ) {
				return $top_width / 2;
			}
			$t = ( $y - $top_y ) / $span;
			$t = max( 0, min( 1, $t ) );
			return ( $top_width + ( $bottom_width - $top_width ) * $t ) / 2;
		};

		$tiers = [];

		for ( $i = 0; $i < $n; $i++ ) {
			$y_top = $top_y + $i * ( $tier_height + $tier_gap );
			$y_bot = $y_top + $tier_height;

			$lt = $cx - $half_at( $y_top );
			$rt = $cx + $half_at( $y_top );
			$lb = $cx - $half_at( $y_bot );
			$rb = $cx + $half_at( $y_bot );

			$ctrl_top = ( 0 === $i ) ? $y_top - 12 : $y_top + $curve_depth;
			$ctrl_bot = $y_bot + $curve_depth;

			$path = sprintf(
				'M %.2f %.2f Q %.2f %.2f %.2f %.2f L %.2f %.2f Q %.2f %.2f %.2f %.2f Z',
				$lt,
				$y_top,
				$cx,
				$ctrl_top,
				$rt,
				$y_top,
				$rb,
				$y_bot,
				$cx,
				$ctrl_bot,
				$lb,
				$y_bot
			);

			$node_x = ( ( $lt + $lb ) / 2 ) - $node_offset;
			$node_y = ( $y_top + $y_bot ) / 2;

			$tiers[] = [
				'path'            => $path,
				'valueY'          => $y_top + $tier_height * 0.345,
				'labelY'          => $y_top + $tier_height * 0.573,
				'subY'            => $y_top + $tier_height * 0.727,
				'nodeX'           => $node_x,
				'nodeY'           => $node_y,
				'transformOrigin' => sprintf( '%.2fpx %.2fpx', $cx, $node_y ),
				'nodeRadius'      => $node_radius,
			];
		}

		return [
			'tiers'         => $tiers,
			'viewBoxHeight' => $top_y + $span + 20,
			'viewWidth'     => $view_width,
		];
	}
}
} // class_exists Nexora_Funnel_Geometry
