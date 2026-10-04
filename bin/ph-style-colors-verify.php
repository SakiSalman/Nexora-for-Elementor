<?php
/**
 * Extract and verify Prospects Hive widget style-color coverage.
 *
 * Run from the plugin root:
 *   php bin/ph-style-colors-verify.php
 *   php bin/ph-style-colors-verify.php --extract[=slug]
 *   php bin/ph-style-colors-verify.php --slug=slug
 */

declare( strict_types=1 );

if ( PHP_SAPI !== 'cli' ) {
	fwrite( STDERR, "This script must be run from the command line.\n" );
	exit( 2 );
}

if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __DIR__, 3 ) . DIRECTORY_SEPARATOR );
}

const PH_SC_SLUGS = [
	'approach',
	'cases',
	'challenge',
	'contact',
	'cta',
	'expertise',
	'faq',
	'footer',
	'framework',
	'growth',
	'hero',
	'impact',
	'industries',
	'insights',
	'nav',
	'partners',
	'pricing',
	'process',
	'solutions',
	'stories',
	'tech',
	'video',
	'why',
];

/**
 * Normalize a hex color to an uppercase, six-digit RGB value.
 */
function ph_sc_norm_hex( string $raw ): string {
	$h = strtoupper( ltrim( trim( $raw ), '#' ) );
	if ( strlen( $h ) === 3 ) {
		$h = $h[0] . $h[0] . $h[1] . $h[1] . $h[2] . $h[2];
	}
	if ( strlen( $h ) === 8 ) {
		$h = substr( $h, 0, 6 );
	}
	return '#' . $h;
}

/**
 * Read a file, returning an empty string when it is unavailable.
 */
function ph_sc_read( string $path ): string {
	if ( ! is_readable( $path ) ) {
		return '';
	}

	$content = file_get_contents( $path );
	return is_string( $content ) ? $content : '';
}

/**
 * Remove comments which can contain non-rendered color examples.
 */
function ph_sc_strip_comments( string $source ): string {
	$source = preg_replace( '#/\*.*?\*/#s', '', $source ) ?? $source;
	$source = preg_replace( '/<!--.*?-->/s', '', $source ) ?? $source;
	return preg_replace( '/^[\t ]*\/\/.*$/m', '', $source ) ?? $source;
}

/**
 * Return all PHP and JS files beneath a widget directory.
 *
 * @return list<string>
 */
function ph_sc_widget_source_paths( string $widget_dir ): array {
	if ( ! is_dir( $widget_dir ) ) {
		return [];
	}

	$paths    = [];
	$iterator = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator( $widget_dir, FilesystemIterator::SKIP_DOTS )
	);

	foreach ( $iterator as $file ) {
		if ( ! $file instanceof SplFileInfo || ! $file->isFile() ) {
			continue;
		}

		$extension = strtolower( $file->getExtension() );
		if ( in_array( $extension, [ 'php', 'js' ], true ) ) {
			$paths[] = $file->getPathname();
		}
	}

	sort( $paths );
	return $paths;
}

/**
 * Read widget PHP/JS plus rendered markup, which may contain inline colors.
 */
function ph_sc_widget_source( string $widget_dir ): string {
	$chunks = [];
	foreach ( ph_sc_widget_source_paths( $widget_dir ) as $path ) {
		$chunks[] = ph_sc_read( $path );
	}

	$markup_path = $widget_dir . DIRECTORY_SEPARATOR . 'markup.html';
	if ( is_readable( $markup_path ) ) {
		$chunks[] = ph_sc_read( $markup_path );
	}

	return ph_sc_strip_comments( implode( "\n", $chunks ) );
}

/**
 * Extract class tokens from rendered widget markup and inline templates.
 *
 * @return array<string, true>
 */
function ph_sc_markup_classes( string $source, string $slug ): array {
	$classes = [
		'nexora-ph'              => true,
		'nexora-ph-' . $slug     => true,
	];

	if ( preg_match_all( '/\bclass\s*=\s*(["\'])(.*?)\1/is', $source, $matches ) ) {
		foreach ( $matches[2] as $class_list ) {
			foreach ( preg_split( '/\s+/', trim( (string) $class_list ) ) ?: [] as $class ) {
				$class = trim( $class );
				if ( preg_match( '/^[A-Za-z_][A-Za-z0-9_-]*$/', $class ) ) {
					$classes[ $class ] = true;
				}
			}
		}
	}

	return $classes;
}

/**
 * Find the closing brace for a CSS block.
 */
function ph_sc_matching_brace( string $css, int $open ): ?int {
	$depth  = 0;
	$length = strlen( $css );
	$quote  = '';

	for ( $index = $open; $index < $length; $index++ ) {
		$char = $css[ $index ];
		if ( '' !== $quote ) {
			if ( '\\' === $char ) {
				$index++;
				continue;
			}
			if ( $char === $quote ) {
				$quote = '';
			}
			continue;
		}
		if ( '"' === $char || "'" === $char ) {
			$quote = $char;
			continue;
		}
		if ( '{' === $char ) {
			$depth++;
		} elseif ( '}' === $char ) {
			$depth--;
			if ( 0 === $depth ) {
				return $index;
			}
		}
	}

	return null;
}

/**
 * Parse leaf CSS rules, including rules nested inside media queries.
 *
 * @return list<array{selector: string, body: string}>
 */
function ph_sc_css_leaf_rules( string $css ): array {
	$rules  = [];
	$offset = 0;
	$length = strlen( $css );

	while ( $offset < $length ) {
		$open = strpos( $css, '{', $offset );
		if ( false === $open ) {
			break;
		}

		$selector = trim( substr( $css, $offset, $open - $offset ) );
		$close    = ph_sc_matching_brace( $css, $open );
		if ( null === $close ) {
			break;
		}

		$body = substr( $css, $open + 1, $close - $open - 1 );
		if ( false !== strpos( $body, '{' ) ) {
			$rules = array_merge( $rules, ph_sc_css_leaf_rules( $body ) );
		} elseif ( '' !== $selector && 0 !== strpos( $selector, '@' ) ) {
			$rules[] = [
				'selector' => $selector,
				'body'     => $body,
			];
		}

		$offset = $close + 1;
	}

	return $rules;
}

/**
 * Decide whether one selector can apply to the widget markup.
 *
 * @param array<string, true> $markup_classes
 */
function ph_sc_selector_applies( string $selector, array $markup_classes ): bool {
	$selector = str_replace( '{{WRAPPER}}', '', $selector );
	if ( ! preg_match_all( '/\.([A-Za-z_][A-Za-z0-9_-]*)/', $selector, $matches ) ) {
		return false;
	}

	foreach ( array_unique( $matches[1] ) as $class ) {
		if ( ! isset( $markup_classes[ $class ] ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Keep shared CSS declarations only when at least one selector applies.
 *
 * @param array<string, true> $markup_classes
 */
function ph_sc_applicable_shared_css( string $shared_css, array $markup_classes ): string {
	$applicable = [];
	foreach ( ph_sc_css_leaf_rules( ph_sc_strip_comments( $shared_css ) ) as $rule ) {
		foreach ( explode( ',', $rule['selector'] ) as $selector ) {
			if ( ph_sc_selector_applies( trim( $selector ), $markup_classes ) ) {
				$applicable[] = $rule['selector'] . '{' . $rule['body'] . '}';
				break;
			}
		}
	}

	return implode( "\n", $applicable );
}

/**
 * Capture gradient functions with balanced parentheses.
 *
 * @return list<array{raw: string, offset: int, length: int}>
 */
function ph_sc_gradient_spans( string $source ): array {
	$spans  = [];
	$offset = 0;
	$length = strlen( $source );

	while (
		preg_match(
			'/\b(?:linear|radial)-gradient\s*\(/i',
			$source,
			$match,
			PREG_OFFSET_CAPTURE,
			$offset
		)
	) {
		$start = $match[0][1];
		$open  = strpos( $source, '(', $start );
		if ( false === $open ) {
			break;
		}

		$depth = 0;
		$quote = '';
		$end   = null;
		for ( $index = $open; $index < $length; $index++ ) {
			$char = $source[ $index ];
			if ( '' !== $quote ) {
				if ( '\\' === $char ) {
					$index++;
					continue;
				}
				if ( $char === $quote ) {
					$quote = '';
				}
				continue;
			}
			if ( '"' === $char || "'" === $char ) {
				$quote = $char;
				continue;
			}
			if ( '(' === $char ) {
				$depth++;
			} elseif ( ')' === $char ) {
				$depth--;
				if ( 0 === $depth ) {
					$end = $index;
					break;
				}
			}
		}

		if ( null === $end ) {
			break;
		}

		$span_length = $end - $start + 1;
		$spans[]     = [
			'raw'    => substr( $source, $start, $span_length ),
			'offset' => $start,
			'length' => $span_length,
		];
		$offset      = $end + 1;
	}

	return $spans;
}

/**
 * Convert an RGB triplet to normalized hex.
 */
function ph_sc_rgb_hex( int $red, int $green, int $blue ): string {
	return sprintf(
		'#%02X%02X%02X',
		max( 0, min( 255, $red ) ),
		max( 0, min( 255, $green ) ),
		max( 0, min( 255, $blue ) )
	);
}

/**
 * Return normalized color tokens from arbitrary CSS.
 *
 * @return list<array{key: string, overlay: bool, transparent: bool}>
 */
function ph_sc_color_tokens( string $source ): array {
	$tokens  = [];
	$pattern = '/#[0-9A-Fa-f]{8}\b|#[0-9A-Fa-f]{6}\b|#[0-9A-Fa-f]{3}\b|rgba?\(\s*[-.\d%]+\s*,\s*[-.\d%]+\s*,\s*[-.\d%]+(?:\s*[,\/]\s*[-.\d%]+)?\s*\)|\btransparent\b/i';

	if ( ! preg_match_all( $pattern, $source, $matches ) ) {
		return [];
	}

	foreach ( $matches[0] as $raw ) {
		if ( 0 === strcasecmp( $raw, 'transparent' ) ) {
			$tokens[] = [
				'key'         => 'transparent',
				'overlay'     => true,
				'transparent' => true,
			];
			continue;
		}

		if ( '#' === $raw[0] ) {
			$hex       = strtoupper( ltrim( $raw, '#' ) );
			$has_alpha = 8 === strlen( $hex );
			$key       = ph_sc_norm_hex( $raw );
			$alpha     = $has_alpha ? hexdec( substr( $hex, 6, 2 ) ) / 255 : 1.0;
			$tokens[]  = [
				'key'         => $key,
				'overlay'     => $has_alpha && $alpha < 1 && in_array( $key, [ '#000000', '#FFFFFF' ], true ),
				'transparent' => false,
			];
			continue;
		}

		if ( ! preg_match( '/rgba?\(\s*([\d.]+)%?\s*,\s*([\d.]+)%?\s*,\s*([\d.]+)%?(?:\s*[,\/]\s*([\d.]+)%?)?\s*\)/i', $raw, $parts ) ) {
			continue;
		}

		$red   = (int) round( (float) $parts[1] );
		$green = (int) round( (float) $parts[2] );
		$blue  = (int) round( (float) $parts[3] );
		$alpha = isset( $parts[4] ) ? (float) $parts[4] : 1.0;
		if ( isset( $parts[4] ) && false !== strpos( $raw, '%' ) && $alpha > 1 ) {
			$alpha /= 100;
		}

		$key      = ph_sc_rgb_hex( $red, $green, $blue );
		$canonical = $alpha < 1
			? sprintf( 'rgba(%d,%d,%d,%s)', $red, $green, $blue, rtrim( rtrim( sprintf( '%.4F', $alpha ), '0' ), '.' ) )
			: $key;
		$tokens[] = [
			'key'         => $canonical,
			'overlay'     => $alpha < 1 && in_array( $key, [ '#000000', '#FFFFFF' ], true ),
			'transparent' => false,
		];
	}

	return $tokens;
}

/**
 * Return a token's base RGB hex, discarding alpha for overlay checks.
 *
 * @param array{key: string, overlay: bool, transparent: bool} $token
 */
function ph_sc_token_base_hex( array $token ): string {
	if ( preg_match( '/^#[0-9A-F]{6}$/', $token['key'] ) ) {
		return $token['key'];
	}
	if ( preg_match( '/^rgba\((\d+),(\d+),(\d+),/', $token['key'], $rgb ) ) {
		return ph_sc_rgb_hex( (int) $rgb[1], (int) $rgb[2], (int) $rgb[3] );
	}
	return '';
}

/**
 * Canonicalize a gradient while preserving its complete stop signature.
 */
function ph_sc_gradient_signature( string $gradient ): string {
	$signature = strtolower( trim( $gradient ) );
	$signature = preg_replace_callback(
		'/#[0-9a-f]{3,8}\b/i',
		static fn( array $match ): string => ph_sc_norm_hex( $match[0] ),
		$signature
	) ?? $signature;
	$signature = preg_replace( '/\s+/', ' ', $signature ) ?? $signature;
	$signature = preg_replace( '/\s*([(,])\s*/', '$1', $signature ) ?? $signature;
	$signature = preg_replace( '/\s+\)/', ')', $signature ) ?? $signature;
	$signature = preg_replace( '/\)\s+(?=[,)])/', ')', $signature ) ?? $signature;
	$signature = preg_replace( '/\s*:\s*/', ':', $signature ) ?? $signature;
	return $signature;
}

/**
 * Extract includable gradients.
 *
 * @return list<string>
 */
function ph_sc_extract_gradients( string $source ): array {
	$gradients = [];
	foreach ( ph_sc_gradient_spans( $source ) as $span ) {
		$tokens = ph_sc_color_tokens( $span['raw'] );
		if ( count( $tokens ) < 2 ) {
			continue;
		}

		$visible = array_filter(
			$tokens,
			static fn( array $token ): bool => ! $token['transparent']
		);
		$only_excluded_overlays = ! empty( $visible );
		foreach ( $visible as $token ) {
			if ( ! in_array( ph_sc_token_base_hex( $token ), [ '#000000', '#FFFFFF' ], true ) ) {
				$only_excluded_overlays = false;
				break;
			}
		}
		if ( $only_excluded_overlays ) {
			continue;
		}

		$gradients[] = ph_sc_gradient_signature( $span['raw'] );
	}

	$gradients = array_values( array_unique( $gradients ) );
	sort( $gradients );
	return $gradients;
}

/**
 * Remove gradients before extracting standalone colors.
 */
function ph_sc_without_gradients( string $source ): string {
	$spans = array_reverse( ph_sc_gradient_spans( $source ) );
	foreach ( $spans as $span ) {
		$source = substr_replace( $source, str_repeat( ' ', $span['length'] ), $span['offset'], $span['length'] );
	}
	return $source;
}

/**
 * Extract normalized standalone solids.
 *
 * @return list<string>
 */
function ph_sc_extract_solids( string $source ): array {
	$solids = [];
	foreach ( ph_sc_color_tokens( ph_sc_without_gradients( $source ) ) as $token ) {
		if ( $token['transparent'] || $token['overlay'] ) {
			continue;
		}

		$key = $token['key'];
		if ( 0 === strpos( $key, 'rgba(' ) && preg_match( '/rgba\((\d+),(\d+),(\d+),/', $key, $rgb ) ) {
			$key = ph_sc_rgb_hex( (int) $rgb[1], (int) $rgb[2], (int) $rgb[3] );
		}
		$solids[] = $key;
	}

	$solids = array_values( array_unique( $solids ) );
	sort( $solids );
	return $solids;
}

/**
 * Load a per-widget inventory.
 *
 * @return array{solids: array<int, mixed>, gradients: array<int, mixed>}
 */
function ph_sc_load_inventory( string $root, string $slug ): array {
	$empty = [
		'solids'    => [],
		'gradients' => [],
	];
	$path = $root . '/includes/ph-style-colors/' . $slug . '.php';
	if ( ! is_readable( $path ) ) {
		return $empty;
	}

	$shared_path = $root . '/includes/ph-style-colors/_shared-tokens.php';
	if ( is_readable( $shared_path ) ) {
		require_once $shared_path;
	}
	require_once $path;
	$function = 'nexora_ph_style_colors_inventory_' . str_replace( '-', '_', $slug );
	if ( ! function_exists( $function ) ) {
		return $empty;
	}

	$inventory = $function();
	if ( ! is_array( $inventory ) ) {
		return $empty;
	}

	$solids    = isset( $inventory['solids'] ) && is_array( $inventory['solids'] ) ? array_values( $inventory['solids'] ) : [];
	$gradients = isset( $inventory['gradients'] ) && is_array( $inventory['gradients'] ) ? array_values( $inventory['gradients'] ) : [];

	if ( ! empty( $inventory['include_shared'] ) ) {
		if ( function_exists( 'nexora_ph_style_colors_shared_catalog' ) ) {
			$shared = nexora_ph_style_colors_shared_catalog();
			$solids = array_merge(
				isset( $shared['solids'] ) && is_array( $shared['solids'] ) ? $shared['solids'] : [],
				$solids
			);
			$gradients = array_merge(
				isset( $shared['gradients'] ) && is_array( $shared['gradients'] ) ? $shared['gradients'] : [],
				$gradients
			);
		}
	}

	return [
		'solids'    => $solids,
		'gradients' => $gradients,
	];
}

/**
 * Return normalized inventory solid defaults.
 *
 * @param array<int, mixed> $entries
 * @return list<string>
 */
function ph_sc_inventory_solids( array $entries ): array {
	$solids = [];
	foreach ( $entries as $entry ) {
		$default = is_array( $entry ) ? ( $entry['default'] ?? '' ) : $entry;
		if ( is_string( $default ) && preg_match( '/^#[0-9A-Fa-f]{3,8}$/', trim( $default ) ) ) {
			$solids[] = ph_sc_norm_hex( $default );
		}
	}
	$solids = array_values( array_unique( $solids ) );
	sort( $solids );
	return $solids;
}

/**
 * Read the first and last inventory gradient colors plus type/angle.
 *
 * @param mixed $entry
 * @return array{exact: string, type: string, angle: string, first: string, last: string, selector: string}
 */
function ph_sc_inventory_gradient_descriptor( $entry ): array {
	$descriptor = [
		'exact'    => '',
		'type'     => '',
		'angle'    => '',
		'first'    => '',
		'last'     => '',
		'selector' => '',
	];
	if ( is_string( $entry ) ) {
		$descriptor['exact'] = ph_sc_gradient_signature( $entry );
		return $descriptor;
	}
	if ( ! is_array( $entry ) ) {
		return $descriptor;
	}

	foreach ( [ 'signature', 'css', 'value' ] as $key ) {
		if ( isset( $entry[ $key ] ) && is_string( $entry[ $key ] ) ) {
			$descriptor['exact'] = ph_sc_gradient_signature( $entry[ $key ] );
			break;
		}
	}

	$default = isset( $entry['default'] ) && is_array( $entry['default'] ) ? $entry['default'] : [];
	$type    = isset( $default['gradient_type'] ) ? strtolower( (string) $default['gradient_type'] ) : 'linear';
	$descriptor['type'] = $type . '-gradient';
	foreach ( [ 'color' => 'first', 'color_b' => 'last' ] as $default_key => $descriptor_key ) {
		if ( ! isset( $default[ $default_key ] ) || ! is_string( $default[ $default_key ] ) ) {
			continue;
		}
		$tokens = ph_sc_color_tokens( $default[ $default_key ] );
		if ( ! empty( $tokens ) ) {
			$descriptor[ $descriptor_key ] = ph_sc_token_base_hex( $tokens[0] );
		}
	}
	$angle = $default['gradient_angle'] ?? null;
	if ( is_array( $angle ) && isset( $angle['size'] ) ) {
		$descriptor['angle'] = (string) $angle['size'] . (string) ( $angle['unit'] ?? 'deg' );
	}
	$descriptor['selector'] = isset( $entry['selector'] ) && is_string( $entry['selector'] )
		? $entry['selector']
		: '';

	return $descriptor;
}

/**
 * Determine whether an expected gradient is represented by an inventory entry.
 *
 * @param mixed $entry
 */
function ph_sc_gradient_matches_inventory( string $gradient, $entry ): bool {
	$descriptor = ph_sc_inventory_gradient_descriptor( $entry );

	// Bridged or layered gradients declare their full CSS signature: type, size,
	// origin, every stop color and position, and every bridge variable.
	if ( '' !== $descriptor['exact'] ) {
		return $gradient === $descriptor['exact'];
	}

	// Unbridged gradients may only be plain two-stop linear gradients, compared
	// on angle, complete colors (including alpha), and stop positions.
	if ( 'linear-gradient' !== $descriptor['type'] || ! is_array( $entry ) ) {
		return false;
	}

	$default = isset( $entry['default'] ) && is_array( $entry['default'] ) ? $entry['default'] : [];
	if ( ! isset( $default['color'], $default['color_b'] ) ) {
		return false;
	}

	$parsed = ph_sc_gradient_unwrapped_stops( $gradient );
	if ( null === $parsed || 2 !== count( $parsed['stops'] ) || 'linear-gradient' !== $parsed['type'] ) {
		return false;
	}

	$angle = ph_sc_inventory_field_default( $default, 'gradient_angle' );
	if ( '' !== $parsed['prefix'] && ! ph_sc_values_equal( $parsed['prefix'], $angle ) ) {
		return false;
	}
	if ( '' === $parsed['prefix'] && ! ph_sc_values_equal( $angle, '180deg' ) ) {
		return false;
	}

	$expected = [
		[ ph_sc_inventory_field_default( $default, 'color' ), ph_sc_inventory_field_default( $default, 'color_stop' ) ],
		[ ph_sc_inventory_field_default( $default, 'color_b' ), ph_sc_inventory_field_default( $default, 'color_b_stop' ) ],
	];
	foreach ( $parsed['stops'] as $index => $stop ) {
		if ( ! ph_sc_values_equal( $stop['color'], $expected[ $index ][0] ) ) {
			return false;
		}
		$position = '' === $stop['position'] ? ( 0 === $index ? '0%' : '100%' ) : $stop['position'];
		if ( ! ph_sc_values_equal( $position, $expected[ $index ][1] ) ) {
			return false;
		}
	}

	return true;
}

/**
 * Split a function argument list on top-level commas.
 *
 * @return list<string>
 */
function ph_sc_split_arguments( string $arguments ): array {
	$parts = [];
	$depth = 0;
	$start = 0;
	$total = strlen( $arguments );

	for ( $index = 0; $index < $total; $index++ ) {
		$char = $arguments[ $index ];
		if ( '(' === $char ) {
			$depth++;
		} elseif ( ')' === $char ) {
			$depth--;
		} elseif ( ',' === $char && 0 === $depth ) {
			$parts[] = trim( substr( $arguments, $start, $index - $start ) );
			$start   = $index + 1;
		}
	}
	$parts[] = trim( substr( $arguments, $start ) );

	return $parts;
}

/**
 * Replace every `var(--x, fallback)` with its fallback.
 */
function ph_sc_unwrap_vars( string $css ): string {
	while ( preg_match( '/\bvar\s*\(/i', $css, $match, PREG_OFFSET_CAPTURE ) ) {
		$start = $match[0][1];
		$open  = $start + strlen( $match[0][0] ) - 1;
		$depth = 0;
		$end   = null;
		for ( $index = $open; $index < strlen( $css ); $index++ ) {
			if ( '(' === $css[ $index ] ) {
				$depth++;
			} elseif ( ')' === $css[ $index ] ) {
				$depth--;
				if ( 0 === $depth ) {
					$end = $index;
					break;
				}
			}
		}
		if ( null === $end ) {
			return $css;
		}

		$arguments = ph_sc_split_arguments( substr( $css, $open + 1, $end - $open - 1 ) );
		$fallback  = count( $arguments ) > 1 ? implode( ',', array_slice( $arguments, 1 ) ) : '';
		$css       = substr_replace( $css, $fallback, $start, $end - $start + 1 );
	}

	return $css;
}

/**
 * Parse a gradient into its type, leading argument, and ordered color stops
 * after replacing variables with their fallbacks.
 *
 * @return array{type: string, prefix: string, stops: list<array{color: string, position: string}>}|null
 */
function ph_sc_gradient_unwrapped_stops( string $gradient ): ?array {
	if ( ! preg_match( '/^((?:linear|radial)-gradient)\((.*)\)$/is', trim( $gradient ), $parts ) ) {
		return null;
	}

	$prefix = '';
	$stops  = [];
	foreach ( ph_sc_split_arguments( ph_sc_unwrap_vars( $parts[2] ) ) as $argument ) {
		$tokens = ph_sc_color_tokens( $argument );
		if ( empty( $tokens ) ) {
			$prefix = trim( $argument );
			continue;
		}

		if ( ! preg_match( '/^(.*?(?:\([^()]*\)|#[0-9A-Fa-f]{3,8}\b|transparent))\s*(.*)$/is', trim( $argument ), $stop ) ) {
			return null;
		}
		$stops[] = [
			'color'    => trim( $stop[1] ),
			'position' => trim( $stop[2] ),
		];
	}

	return [
		'type'   => strtolower( $parts[1] ),
		'prefix' => $prefix,
		'stops'  => $stops,
	];
}

/**
 * Return a control default for one gradient field, mirroring the registrar.
 *
 * @param array<string, mixed> $default Inventory default array.
 */
function ph_sc_inventory_field_default( array $default, string $field ): string {
	$fallbacks = [
		'color_stop'        => [ 'unit' => '%', 'size' => 0 ],
		'color_b_stop'      => [ 'unit' => '%', 'size' => 100 ],
		'gradient_angle'    => [ 'unit' => 'deg', 'size' => 180 ],
		'gradient_position' => 'center center',
	];
	$value = $default[ $field ] ?? ( $fallbacks[ $field ] ?? '' );

	if ( is_array( $value ) ) {
		return (string) ( $value['size'] ?? '' ) . (string) ( $value['unit'] ?? '' );
	}

	return (string) $value;
}

/**
 * Canonicalize a CSS value for default/fallback comparison.
 */
function ph_sc_canonical_value( string $value ): string {
	$value = strtolower( trim( $value ) );
	$value = preg_replace( '/\s+/', ' ', $value ) ?? $value;
	$value = preg_replace( '/\s*,\s*/', ',', $value ) ?? $value;

	if ( ! preg_match( '/^(?:#[0-9a-f]{3,8}|rgba?\([^)]*\)|transparent)$/', $value ) ) {
		return $value;
	}

	if ( preg_match( '/^#[0-9a-f]{8}$/', $value ) ) {
		return $value;
	}

	$tokens = ph_sc_color_tokens( $value );
	if ( 1 !== count( $tokens ) ) {
		return $value;
	}

	$key = strtolower( $tokens[0]['key'] );
	if ( 'transparent' === $key || preg_match( '/^rgba\(\d+,\d+,\d+,0\)$/', $key ) ) {
		return 'transparent';
	}

	return $key;
}

/**
 * Compare two CSS values, ignoring formatting and zero-alpha color spelling.
 */
function ph_sc_values_equal( string $left, string $right ): bool {
	return ph_sc_canonical_value( $left ) === ph_sc_canonical_value( $right );
}

/**
 * Return true when a hardcoded standalone hex is not a PH var fallback.
 */
function ph_sc_has_unwired_hex( string $source, string $hex ): bool {
	$source  = ph_sc_without_gradients( $source );
	$compact = ltrim( $hex, '#' );
	$pattern = '/#(?:' . preg_quote( $compact, '/' ) . ')(?:[0-9A-Fa-f]{2})?\b/i';
	if ( ! preg_match_all( $pattern, $source, $matches, PREG_OFFSET_CAPTURE ) ) {
		return false;
	}

	foreach ( $matches[0] as $match ) {
		$start   = max( 0, $match[1] - 120 );
		$context = substr( $source, $start, $match[1] - $start + strlen( $match[0] ) + 8 );
		if ( ! preg_match( '/var\(\s*--ph-[A-Za-z0-9_-]+\s*,\s*' . preg_quote( $match[0], '/' ) . '\s*\)/i', $context ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Return true when a source gradient is still a competing hardcoded fill.
 *
 * Group controls own the background declaration, so their old source
 * gradients must be removed. A gradient may remain only as a PH variable
 * bridge whose fallbacks preserve the default appearance.
 */
function ph_sc_gradient_is_unwired( string $gradient, array $emitted ): bool {
	$vars = ph_sc_collect_vars( $gradient );
	if ( empty( $vars ) ) {
		return true;
	}

	$unowned = $gradient;
	foreach ( $vars as $var ) {
		// Every bridge variable needs a fallback and a control that emits it.
		if ( '' === $var['fallback'] || ! isset( $emitted[ $var['name'] ] ) ) {
			return true;
		}
		$unowned = substr_replace( $unowned, str_repeat( ' ', $var['length'] ), $var['offset'], $var['length'] );
	}

	// Anything outside a bridge variable is a competing hardcoded color, except
	// the excluded pure white/black glass overlays and transparent stops.
	foreach ( ph_sc_color_tokens( $unowned ) as $token ) {
		if ( ! $token['transparent'] && ! $token['overlay'] ) {
			return true;
		}
	}

	return false;
}

/**
 * Collect every `var(--ph-*)` call with its fallback.
 *
 * @return list<array{name: string, fallback: string, offset: int, length: int}>
 */
function ph_sc_collect_vars( string $css ): array {
	$vars   = [];
	$offset = 0;
	$total  = strlen( $css );

	while ( preg_match( '/\bvar\s*\(\s*(--ph-[A-Za-z0-9_-]+)/i', $css, $match, PREG_OFFSET_CAPTURE, $offset ) ) {
		$start = $match[0][1];
		$open  = strpos( $css, '(', $start );
		if ( false === $open ) {
			break;
		}

		$depth = 0;
		$end   = null;
		for ( $index = $open; $index < $total; $index++ ) {
			if ( '(' === $css[ $index ] ) {
				$depth++;
			} elseif ( ')' === $css[ $index ] ) {
				$depth--;
				if ( 0 === $depth ) {
					$end = $index;
					break;
				}
			}
		}
		if ( null === $end ) {
			break;
		}

		$arguments = ph_sc_split_arguments( substr( $css, $open + 1, $end - $open - 1 ) );
		$vars[]    = [
			'name'     => strtolower( $match[1][0] ),
			'fallback' => count( $arguments ) > 1 ? trim( implode( ',', array_slice( $arguments, 1 ) ) ) : '',
			'offset'   => $start,
			'length'   => $end - $start + 1,
		];
		$offset    = $end + 1;
	}

	return $vars;
}

/**
 * Normalize a sanitize_key()-style token without loading WordPress.
 */
function ph_sc_key( string $value ): string {
	return preg_replace( '/[^a-z0-9_-]/', '', strtolower( $value ) ) ?? '';
}

/**
 * Record one emitted variable, noting when two controls write the same one.
 *
 * @param array<string, array<string, string>> $emitted
 */
function ph_sc_add_emitted( array &$emitted, string $name, string $default, string $origin, string $entry ): void {
	if ( isset( $emitted[ $name ] ) ) {
		$emitted[ $name ]['duplicate'] = $emitted[ $name ]['origin'] . ' + ' . $origin;
		return;
	}

	$emitted[ $name ] = [
		'default'   => $default,
		'origin'    => $origin,
		'entry'     => $entry,
		'duplicate' => '',
	];
}

/**
 * Map every CSS variable a widget's Style controls emit to its control default.
 *
 * @param array{solids: array<int, mixed>, gradients: array<int, mixed>} $inventory
 * @return array<string, array{default: string, origin: string, entry: string, duplicate: string}>
 */
function ph_sc_inventory_emitted_vars( array $inventory, string $slug ): array {
	$emitted = [];

	foreach ( $inventory['solids'] as $solid ) {
		if ( ! is_array( $solid ) || ! isset( $solid['token'], $solid['default'] ) ) {
			continue;
		}
		$token = ph_sc_key( (string) $solid['token'] );
		$name  = '--ph-' . ( ! empty( $solid['shared'] ) ? $token : $slug . '-' . $token );
		ph_sc_add_emitted( $emitted, $name, strtoupper( (string) $solid['default'] ), 'solid:' . $token, '' );
	}

	foreach ( $inventory['gradients'] as $entry ) {
		if ( ! is_array( $entry ) || empty( $entry['fields_options'] ) || ! is_array( $entry['fields_options'] ) ) {
			continue;
		}
		$default = isset( $entry['default'] ) && is_array( $entry['default'] ) ? $entry['default'] : [];
		$label   = (string) ( $entry['name'] ?? '' );

		foreach ( $entry['fields_options'] as $field => $options ) {
			if ( ! is_array( $options ) || empty( $options['selectors'] ) || ! is_array( $options['selectors'] ) ) {
				continue;
			}
			foreach ( $options['selectors'] as $declaration ) {
				if ( ! is_string( $declaration ) || ! preg_match_all( '/(--ph-[A-Za-z0-9_-]+)\s*:/', $declaration, $names ) ) {
					continue;
				}
				foreach ( $names[1] as $name ) {
					$field_default = $options['default'] ?? null;
					$value         = null === $field_default
						? ph_sc_inventory_field_default( $default, (string) $field )
						: ph_sc_inventory_field_default( [ $field => $field_default ], (string) $field );
					ph_sc_add_emitted( $emitted, strtolower( $name ), $value, 'gradient:' . $label . '.' . $field, $label );
				}
			}
		}
	}

	return $emitted;
}

/**
 * Find bridged gradient controls that leave a visible field doing nothing.
 *
 * A bridged control (any field emits a variable) must either emit a variable
 * for every visible field of its gradient type or hide the field.
 *
 * @param array<int, mixed> $gradients
 * @return list<string>
 */
function ph_sc_inventory_dead_fields( array $gradients ): array {
	$dead = [];
	foreach ( $gradients as $entry ) {
		if ( ! is_array( $entry ) || empty( $entry['fields_options'] ) || ! is_array( $entry['fields_options'] ) ) {
			continue;
		}

		$fields_options = $entry['fields_options'];
		$bridged        = false;
		foreach ( $fields_options as $options ) {
			if ( is_array( $options ) && ! empty( $options['selectors'] ) ) {
				$bridged = true;
				break;
			}
		}
		if ( ! $bridged ) {
			continue;
		}

		$type   = strtolower( (string) ( $entry['default']['gradient_type'] ?? 'linear' ) );
		$fields = [ 'color', 'color_b', 'color_stop', 'color_b_stop', 'radial' === $type ? 'gradient_position' : 'gradient_angle' ];
		foreach ( $fields as $field ) {
			$options = isset( $fields_options[ $field ] ) && is_array( $fields_options[ $field ] ) ? $fields_options[ $field ] : [];
			if ( empty( $options['selectors'] ) && empty( $options['condition'] ) ) {
				$dead[] = ( $entry['name'] ?? '?' ) . '.' . $field . ' (visible but emits no variable)';
			}
		}
	}

	return $dead;
}

/**
 * Build source and expected sets for one widget.
 *
 * @return array{source: string, classes: array<string, true>, solids: list<string>, gradients: list<string>}
 */
function ph_sc_extract_slug( string $root, string $slug, string $shared_css ): array {
	$widget_dir    = $root . '/widgets/ph-' . $slug;
	$widget_source = ph_sc_widget_source( $widget_dir );
	$classes       = ph_sc_markup_classes( $widget_source, $slug );
	$own_css       = ph_sc_strip_comments( ph_sc_read( $root . '/assets/css/nexora-ph-' . $slug . '.css' ) );
	$shared        = ph_sc_applicable_shared_css( $shared_css, $classes );
	$source        = implode( "\n", [ $own_css, $widget_source, $shared ] );

	return [
		'source'    => $source,
		'classes'   => $classes,
		'solids'    => ph_sc_extract_solids( $source ),
		'gradients' => ph_sc_extract_gradients( $source ),
	];
}

/**
 * Compare extracted values with one inventory.
 *
 * @param array{source: string, classes: array<string, true>, solids: list<string>, gradients: list<string>} $extracted
 * @param array{solids: array<int, mixed>, gradients: array<int, mixed>} $inventory
 * @return array<string, mixed>
 */
function ph_sc_verify_slug( array $extracted, array $inventory, string $slug ): array {
	$emitted          = ph_sc_inventory_emitted_vars( $inventory, $slug );
	$source_vars      = ph_sc_collect_vars( $extracted['source'] );
	$consumed         = [];
	foreach ( $source_vars as $var ) {
		$consumed[ $var['name'] ] = true;
	}

	// Solids: hex coverage, hardcoded leftovers, and unused controls.
	$inventory_solids = ph_sc_inventory_solids( $inventory['solids'] );
	$missing_solids   = array_values( array_diff( $extracted['solids'], $inventory_solids ) );
	$orphan_solids    = [];
	foreach ( $inventory['solids'] as $solid ) {
		if ( ! is_array( $solid ) || ! isset( $solid['token'], $solid['default'] ) ) {
			continue;
		}
		$token = ph_sc_key( (string) $solid['token'] );
		$name  = '--ph-' . ( ! empty( $solid['shared'] ) ? $token : $slug . '-' . $token );
		$hex   = ph_sc_norm_hex( (string) $solid['default'] );
		// A solid is only used when its own variable is consumed; sharing a hex
		// with some other color in the source must not mask an unused control.
		if ( ! isset( $consumed[ $name ] ) ) {
			$orphan_solids[] = $hex . ' (' . $name . ')';
		}
	}

	$unwired_solids = [];
	foreach ( array_intersect( $extracted['solids'], $inventory_solids ) as $solid ) {
		if ( ph_sc_has_unwired_hex( $extracted['source'], $solid ) ) {
			$unwired_solids[] = $solid;
		}
	}

	// Gradients: full-signature coverage and competing hardcoded fills.
	$missing_gradients = [];
	$unwired_gradients = [];
	foreach ( $extracted['gradients'] as $gradient ) {
		$matched = false;
		foreach ( $inventory['gradients'] as $entry ) {
			if ( ph_sc_gradient_matches_inventory( $gradient, $entry ) ) {
				$matched = true;
				break;
			}
		}
		if ( ! $matched ) {
			$missing_gradients[] = $gradient;
		}
		if ( ph_sc_gradient_is_unwired( $gradient, $emitted ) ) {
			$unwired_gradients[] = $gradient;
		}
	}

	$orphan_gradients = [];
	foreach ( $inventory['gradients'] as $index => $entry ) {
		$matched = false;
		foreach ( $extracted['gradients'] as $gradient ) {
			if ( ph_sc_gradient_matches_inventory( $gradient, $entry ) ) {
				$matched = true;
				break;
			}
		}
		if ( ! $matched ) {
			$orphan_gradients[] = is_array( $entry ) && isset( $entry['name'] ) ? (string) $entry['name'] : 'gradient[' . $index . ']';
		}
	}

	// Variables: every CSS variable must be emitted by a control with a matching
	// default, and every emitted gradient variable must be consumed in CSS.
	$unwired_vars        = [];
	$mismatched_defaults = [];
	foreach ( $source_vars as $var ) {
		$name = $var['name'];
		if ( ! isset( $emitted[ $name ] ) ) {
			$unwired_vars[] = $name . ' (used in CSS/markup, no Style control emits it)';
			continue;
		}
		if ( '' === $var['fallback'] ) {
			$mismatched_defaults[] = $name . ' (CSS usage has no fallback)';
			continue;
		}
		if ( ! ph_sc_values_equal( $var['fallback'], $emitted[ $name ]['default'] ) ) {
			$mismatched_defaults[] = sprintf(
				'%s: css fallback "%s" vs control default "%s" (%s)',
				$name,
				$var['fallback'],
				$emitted[ $name ]['default'],
				$emitted[ $name ]['origin']
			);
		}
	}

	foreach ( $emitted as $name => $info ) {
		if ( '' !== $info['duplicate'] ) {
			$unwired_vars[] = $name . ' (emitted by multiple controls: ' . $info['duplicate'] . ')';
		}
		if ( 0 === strpos( $info['origin'], 'gradient:' ) && ! isset( $consumed[ $name ] ) ) {
			$unwired_vars[] = $name . ' (emitted by ' . $info['origin'] . ', never consumed)';
		}
	}

	foreach ( $inventory['gradients'] as $entry ) {
		$descriptor = ph_sc_inventory_gradient_descriptor( $entry );
		if ( '' === $descriptor['exact'] || ! is_array( $entry ) ) {
			continue;
		}
		foreach ( $emitted as $name => $info ) {
			if ( ( $entry['name'] ?? '' ) === $info['entry'] && false === strpos( $descriptor['exact'], $name . ',' ) ) {
				$unwired_vars[] = $name . ' (emitted by ' . $info['origin'] . ', absent from its gradient signature)';
			}
		}
	}

	foreach ( ph_sc_inventory_dead_fields( $inventory['gradients'] ) as $dead_field ) {
		$unwired_vars[] = $dead_field;
	}

	$orphan = array_merge(
		array_map( static fn( string $value ): string => 'solid:' . $value, $orphan_solids ),
		array_map( static fn( string $value ): string => 'gradient:' . $value, $orphan_gradients )
	);

	return [
		'expected_solids'     => count( $extracted['solids'] ),
		'expected_gradients'  => count( $extracted['gradients'] ),
		'inventory_solids'    => count( $inventory_solids ),
		'inventory_gradients' => count( $inventory['gradients'] ),
		'missing_solids'      => $missing_solids,
		'missing_gradients'   => $missing_gradients,
		'unwired_solids'      => array_values( array_unique( $unwired_solids ) ),
		'unwired_gradients'   => array_values( array_unique( $unwired_gradients ) ),
		'unwired_vars'        => array_values( array_unique( $unwired_vars ) ),
		'mismatched_defaults' => array_values( array_unique( $mismatched_defaults ) ),
		'orphan'              => $orphan,
	];
}

/**
 * Parse supported CLI options.
 *
 * @return array{extract: bool, slug: string}
 */
function ph_sc_options( array $arguments ): array {
	$options = [
		'extract' => false,
		'slug'    => '',
	];

	foreach ( array_slice( $arguments, 1 ) as $argument ) {
		if ( '--extract' === $argument ) {
			$options['extract'] = true;
			continue;
		}
		if ( 0 === strpos( $argument, '--extract=' ) ) {
			$options['extract'] = true;
			$options['slug']    = substr( $argument, strlen( '--extract=' ) );
			continue;
		}
		if ( 0 === strpos( $argument, '--slug=' ) ) {
			$options['slug'] = substr( $argument, strlen( '--slug=' ) );
			continue;
		}

		fwrite( STDERR, 'Unknown option: ' . $argument . "\n" );
		exit( 2 );
	}

	if ( '' !== $options['slug'] && ! in_array( $options['slug'], PH_SC_SLUGS, true ) ) {
		fwrite( STDERR, 'Unknown PH slug: ' . $options['slug'] . "\n" );
		exit( 2 );
	}

	return $options;
}

$root       = dirname( __DIR__ );
$options    = ph_sc_options( $argv );
$slugs      = '' !== $options['slug'] ? [ $options['slug'] ] : PH_SC_SLUGS;
$shared_css = ph_sc_read( $root . '/assets/css/nexora-ph-shared.css' );
$results    = [];
$has_gaps   = false;

foreach ( $slugs as $slug ) {
	$extracted = ph_sc_extract_slug( $root, $slug, $shared_css );
	if ( $options['extract'] ) {
		$results[ $slug ] = [
			'solids'    => $extracted['solids'],
			'gradients' => $extracted['gradients'],
		];
		continue;
	}

	$verified        = ph_sc_verify_slug( $extracted, ph_sc_load_inventory( $root, $slug ), $slug );
	$results[ $slug ] = $verified;
	if (
		! empty( $verified['missing_solids'] )
		|| ! empty( $verified['missing_gradients'] )
		|| ! empty( $verified['unwired_solids'] )
		|| ! empty( $verified['unwired_gradients'] )
		|| ! empty( $verified['unwired_vars'] )
		|| ! empty( $verified['mismatched_defaults'] )
	) {
		$has_gaps = true;
	}
}

if ( $options['extract'] ) {
	echo json_encode( $results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES ) . "\n";
	exit( 0 );
}

foreach ( $results as $slug => $result ) {
	printf(
		"%s: expected_solids=%d expected_gradients=%d inventory_solids=%d inventory_gradients=%d\n",
		$slug,
		$result['expected_solids'],
		$result['expected_gradients'],
		$result['inventory_solids'],
		$result['inventory_gradients']
	);
	foreach ( [ 'missing_solids', 'missing_gradients', 'unwired_solids', 'unwired_gradients', 'unwired_vars', 'mismatched_defaults', 'orphan' ] as $key ) {
		echo '  ' . $key . '=' . json_encode( $result[ $key ], JSON_UNESCAPED_SLASHES ) . "\n";
	}
}

exit( $has_gaps ? 1 : 0 );
