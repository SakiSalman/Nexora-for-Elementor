<?php
/**
 * Cold Email phase 3 color inventory gate (plan section 6.3).
 *
 * Checks, per ce-* widget:
 *   1. the inventory file exists and returns an array from the expected function;
 *   2. include_shared is false (plan D6: entries come from the subset);
 *   3. 4-8 entries (hard cap 8, warning below 4);
 *   4. every label is a non-empty human string that is not the bare token /
 *      gradient name / CSS variable name;
 *   5. every group is a registrar-known group (unknown groups render nowhere);
 *   6. every default is a sane color literal (#hex, or rgba() for gradients);
 *   7. every CSS variable the entry emits is read as var(--name, fallback) in
 *      the widget delta CSS, the widget template, or nexora-ph-shared.css;
 *   8. at least one such fallback equals the entry default (case/space/0.x
 *      insensitive) so defaults equal the original hex.
 *
 * The render half of 6.3 ("unset colors equal the phase-2 render byte for
 * byte") stays with bin/ce-parity.mjs, which already reverses
 * var(--ph-*, fallback) back to the fallback before comparing.
 *
 * Usage: php bin/ce-style-colors-verify.php [--widget ce-hero] [--verbose]
 */

error_reporting( E_ALL );
ini_set( 'display_errors', '1' );

$root = dirname( __DIR__ );

define( 'ABSPATH', $root . '/' );
require_once $root . '/includes/ph-style-colors/_shared-tokens.php';

$slug_filter = null;
$verbose     = in_array( '--verbose', $argv, true );
for ( $i = 1; $i < $argc; $i++ ) {
	if ( '--widget' === $argv[ $i ] && isset( $argv[ $i + 1 ] ) ) {
		$slug_filter = (string) $argv[ $i + 1 ];
	}
}

$slugs = [
	'hero',
	'problem',
	'solution',
	'services',
	'pipeline',
	'process',
	'outlook',
	'why',
	'signal',
];

if ( null !== $slug_filter ) {
	$slug_filter = preg_replace( '/^ce-/', '', $slug_filter );
	if ( ! in_array( $slug_filter, $slugs, true ) ) {
		fwrite( STDERR, "unknown widget: {$slug_filter}\n" );
		exit( 2 );
	}
	$slugs = [ $slug_filter ];
}

$allowed_groups = [ 'text', 'accent', 'surface', 'button', 'tag', 'fill', 'widget' ];

$errors = 0;
$warns  = 0;

/**
 * Canonicalize a color-ish CSS literal for comparison.
 *
 * Lowercases, drops whitespace, and folds a leading zero in decimals so
 * `rgba(241, 94, 34, 0.32)` and `rgba(241,94,34,.32)` compare equal.
 */
$canon = static function ( string $value ): string {
	$value = strtolower( trim( $value ) );
	$value = preg_replace( '/\s+/', '', $value );
	$value = preg_replace( '/(?<![0-9])0\./', '.', (string) $value );
	return (string) $value;
};

/**
 * Collect var(--name, fallback) fallbacks for one variable name in $text.
 */
$fallbacks_for = static function ( string $var, string $text ): array {
	$pattern = '/var\(\s*' . preg_quote( $var, '/' ) . '\s*,\s*((?:[^()]|\([^()]*\))*?)\s*\)/';
	preg_match_all( $pattern, $text, $m );
	return isset( $m[1] ) ? $m[1] : [];
};

$fail = static function ( string $msg ) use ( &$errors ): void {
	$errors++;
	echo "FAIL {$msg}\n";
};
$warn = static function ( string $msg ) use ( &$warns ): void {
	$warns++;
	echo "WARN {$msg}\n";
};

$entries_total = 0;

foreach ( $slugs as $slug ) {
	$file = $root . '/includes/ph-style-colors/ce-' . $slug . '.php';
	if ( ! is_readable( $file ) ) {
		$fail( "{$slug}  inventory file missing: includes/ph-style-colors/ce-{$slug}.php" );
		continue;
	}

	$fn = 'nexora_ph_style_colors_inventory_ce_' . str_replace( '-', '_', $slug );
	require_once $file;
	if ( ! function_exists( $fn ) ) {
		$fail( "{$slug}  inventory function missing: {$fn}()" );
		continue;
	}

	$inventory = $fn();
	if ( ! is_array( $inventory ) ) {
		$fail( "{$slug}  inventory did not return an array" );
		continue;
	}

	$own_fail_before = $errors;
	$own_warn_before = $warns;

	if ( ! empty( $inventory['include_shared'] ) ) {
		$fail( "{$slug}  include_shared must be false (plan D6)" );
	}

	$solids    = isset( $inventory['solids'] ) && is_array( $inventory['solids'] ) ? $inventory['solids'] : [];
	$gradients = isset( $inventory['gradients'] ) && is_array( $inventory['gradients'] ) ? $inventory['gradients'] : [];
	$count     = count( $solids ) + count( $gradients );
	$entries_total += $count;

	if ( $count > 8 ) {
		$fail( "{$slug}  {$count} entries exceeds the cap of 8 (plan D6/6.2)" );
	}
	if ( $count < 4 ) {
		$warn( "{$slug}  only {$count} entries; plan D6 asks for 4-8 pickers" );
	}

	// Consumption files: widget delta CSS, widget template, shared CSS.
	$consume_files = [
		$root . '/assets/css/nexora-ph-ce-' . $slug . '.css',
		$root . '/widgets/ph-ce-' . $slug . '/template.php',
		$root . '/assets/css/nexora-ph-shared.css',
	];
	$consume_text  = '';
	$own_text      = '';
	foreach ( $consume_files as $cf ) {
		$chunk = is_readable( $cf ) ? (string) file_get_contents( $cf ) : '';
		$consume_text .= "\n/* file: {$cf} */\n" . $chunk;
		if ( false !== strpos( $cf, 'nexora-ph-ce-' . $slug . '.css' ) || false !== strpos( $cf, 'template.php' ) ) {
			$own_text .= "\n" . $chunk;
		}
	}

	$check_var = static function ( string $slug, string $var, string $default_label, string $expected, string $consume_text, callable $fallbacks_for, bool $required_match ) use ( $fail, $warn, $canon, $verbose ): void {
		$found = $fallbacks_for( $var, $consume_text );
		if ( ! $found ) {
			$fail( "{$slug}  {$var} ({$default_label}) is never read as var({$var}, fallback) in the widget CSS/template or shared CSS" );
			return;
		}
		$match = false;
		$diffs = [];
		foreach ( array_unique( $found ) as $fb ) {
			if ( $canon( $fb ) === $canon( $expected ) ) {
				$match = true;
			} else {
				$diffs[] = $fb;
			}
		}
		if ( $required_match && ! $match ) {
			$fail( "{$slug}  {$var} ({$default_label}): default {$expected} not among fallbacks: " . implode( ' | ', array_unique( $found ) ) );
		} elseif ( $diffs ) {
			$warn( "{$slug}  {$var}: fallback(s) differing from default {$expected}: " . implode( ' | ', array_unique( $diffs ) ) );
		}
		if ( $verbose ) {
			echo "     {$var} -> " . count( $found ) . " read site(s), default {$expected}\n";
		}
	};

	// Solids.
	foreach ( $solids as $solid ) {
		if ( ! is_array( $solid ) ) {
			$fail( "{$slug}  solid entry is not an array" );
			continue;
		}
		$token   = isset( $solid['token'] ) ? (string) $solid['token'] : '';
		$label   = isset( $solid['label'] ) ? trim( (string) $solid['label'] ) : '';
		$group   = isset( $solid['group'] ) ? (string) $solid['group'] : 'widget';
		$default = isset( $solid['default'] ) ? (string) $solid['default'] : '';
		$shared  = ! empty( $solid['shared'] );

		if ( '' === $token ) {
			$fail( "{$slug}  solid without token" );
			continue;
		}
		if ( '' === $label || strtolower( $label ) === strtolower( $token ) || 0 === strpos( $label, '--' ) || false !== stripos( $label, 'ph-' . $token ) ) {
			$fail( "{$slug}  solid {$token}: label '" . $label . "' is not a human paint-role label (plan 6.1/6.3)" );
		}
		if ( ! in_array( $group, $allowed_groups, true ) ) {
			$fail( "{$slug}  solid {$token}: unknown group '{$group}' (would never render)" );
		}
		if ( ! preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $default ) ) {
			$fail( "{$slug}  solid {$token}: default '{$default}' is not a hex color" );
			continue;
		}

		$var          = '--ph-' . ( $shared ? $token : 'ce-' . $slug . '-' . $token );
		$check_var( $slug, $var, $label, $default, $consume_text, $fallbacks_for, true );

		// Shadow hint: this default painted literally in the widget's own files
		// (svg attributes like stroke="#..." are not CSS paint sites: skipped).
		if ( '' !== $own_text ) {
			$pos       = 0;
			$len       = strlen( $own_text );
			$shadowed  = 0;
			while ( ( $pos = stripos( $own_text, $default, $pos ) ) !== false ) {
				$before = substr( $own_text, max( 0, $pos - 90 ), min( 90, $pos ) );
				$is_var = (bool) preg_match( '/var\(\s*--ph-[a-z0-9_-]+\s*,\s*$/', $before );
				$is_attr = (bool) preg_match( '/[a-z-]+="\s*$/', $before );
				if ( ! $is_var && ! $is_attr ) {
					$shadowed++;
					if ( $shadowed <= 3 ) {
						$warn( "{$slug}  literal {$default} ({$label}) in own files is not wrapped in var({$var}, ...) — verify the cascade winner reads the var" );
					}
				}
				$pos += strlen( $default );
			}
			if ( $shadowed > 3 ) {
				$warn( "{$slug}  literal {$default} ({$label}): " . ( $shadowed - 3 ) . " more unwrapped occurrence(s) suppressed" );
			}
		}
	}

	// Gradients.
	foreach ( $gradients as $gradient ) {
		if ( ! is_array( $gradient ) ) {
			$fail( "{$slug}  gradient entry is not an array" );
			continue;
		}
		$name  = isset( $gradient['name'] ) ? (string) $gradient['name'] : '';
		$label = isset( $gradient['label'] ) ? trim( (string) $gradient['label'] ) : '';
		$group = isset( $gradient['group'] ) ? (string) $gradient['group'] : 'widget';

		if ( '' === $name ) {
			$fail( "{$slug}  gradient without name" );
			continue;
		}
		if ( '' === $label || strtolower( $label ) === strtolower( $name ) || 0 === strpos( $label, '--' ) ) {
			$fail( "{$slug}  gradient {$name}: label '" . $label . "' is not a human paint-role label (plan 6.1/6.3)" );
		}
		if ( ! in_array( $group, $allowed_groups, true ) ) {
			$fail( "{$slug}  gradient {$name}: unknown group '{$group}' (would never render)" );
		}
		$selector = isset( $gradient['selector'] ) ? trim( (string) $gradient['selector'] ) : '';
		if ( '' === $selector ) {
			$fail( "{$slug}  gradient {$name}: missing selector" );
		}

		$defaults = isset( $gradient['default'] ) && is_array( $gradient['default'] ) ? $gradient['default'] : [];
		foreach ( [ 'color', 'color_b' ] as $ck ) {
			if ( ! isset( $defaults[ $ck ] ) || '' === $defaults[ $ck ] ) {
				continue;
			}
			$cv = (string) $defaults[ $ck ];
			if ( ! preg_match( '/^#([0-9a-f]{3}|[0-9a-f]{6}|[0-9a-f]{8})$/i', $cv ) && ! preg_match( '/^rgba?\(/i', $cv ) ) {
				$fail( "{$slug}  gradient {$name}: default {$ck} '{$cv}' is not a hex/rgba color" );
			}
		}

		// Variables the entry emits through the registrar bridge.
		$vars = [];
		$first_var     = null;
		$first_default = '';
		$fields        = isset( $gradient['fields_options'] ) && is_array( $gradient['fields_options'] ) ? $gradient['fields_options'] : [];
		foreach ( $fields as $field => $opts ) {
			if ( ! is_array( $opts ) || empty( $opts['selectors'] ) || ! is_array( $opts['selectors'] ) ) {
				continue;
			}
			foreach ( $opts['selectors'] as $sel_rule ) {
				if ( preg_match_all( '/--(ph-[a-z0-9_-]+)\s*:/i', (string) $sel_rule, $mm ) ) {
					foreach ( $mm[1] as $vn ) {
						$vars[ '--' . $vn ] = true;
						if ( null === $first_var ) {
                        $first_var = '--' . $vn;
                        $first_default = isset( $defaults[ $field ] ) && is_scalar( $defaults[ $field ] ) ? (string) $defaults[ $field ] : '';
						}
					}
				}
			}
		}

		if ( ! $vars ) {
			$fail( "{$slug}  gradient {$name}: emits no CSS variables (bridge/fields_options missing) — it would paint nothing owned" );
			continue;
		}

		foreach ( array_keys( $vars ) as $var ) {
			$found = $fallbacks_for( $var, $consume_text );
			if ( ! $found ) {
				$fail( "{$slug}  {$var} ({$label}: {$name}) is never read as var({$var}, fallback) in the widget CSS/template or shared CSS" );
			} elseif ( $verbose ) {
				echo "     {$var} -> " . count( $found ) . " read site(s)\n";
			}
		}

		if ( null !== $first_var && '' !== $first_default ) {
			$check_var( $slug, $first_var, $label, $first_default, $consume_text, $fallbacks_for, true );
		}
	}

	$de = $errors - $own_fail_before;
	$dw = $warns - $own_warn_before;
	$status = $de ? 'FAIL' : 'OK  ';
	echo "{$status} {$slug}  {$count} entries (" . count( $solids ) . ' solids, ' . count( $gradients ) . ' gradients)' .
		( $de ? "  {$de} error(s)" : '' ) . ( $dw ? "  {$dw} warning(s)" : '' ) . "\n";
}

echo "\n" . ( $errors ? "FAIL: {$errors} error(s), {$warns} warning(s), {$entries_total} entries across " . count( $slugs ) . " widget(s)" : "PASS: " . count( $slugs ) . " widget(s), {$entries_total} entries, {$warns} warning(s)" );
echo "\nParity half of 6.3: php <temp>/ce-phase1-render.php && node bin/ce-parity.mjs <temp>/ce-phase1-render.html (expect 18/18 OK)\n";
exit( $errors ? 1 : 0 );
