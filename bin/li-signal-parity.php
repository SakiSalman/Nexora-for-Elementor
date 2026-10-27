<?php
/**
 * One-off parity check: LI Signal template defaults vs markup.html.
 */

define( 'ABSPATH', '1' );
define( 'NEXORA_ELE_PATH', dirname( __DIR__ ) . '/' );

if ( ! function_exists( 'esc_html' ) ) {
	function esc_html( $s ) {
		return htmlspecialchars( (string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
	}
}
if ( ! function_exists( 'esc_attr' ) ) {
	function esc_attr( $s ) {
		return htmlspecialchars( (string) $s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
	}
}
if ( ! function_exists( 'esc_url' ) ) {
	function esc_url( $s ) {
		return $s;
	}
}
if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $s ) {
		return $s;
	}
}
if ( ! function_exists( 'nexora_ele_heading_tag' ) ) {
	function nexora_ele_heading_tag( $v, $d = 'h2' ) {
		$a = [ 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'div', 'span' ];
		return in_array( $v, $a, true ) ? $v : $d;
	}
}

$settings = [];
ob_start();
include NEXORA_ELE_PATH . 'widgets/ph-li-signal/template.php';
$actual   = (string) ob_get_clean();
$expected = (string) file_get_contents( NEXORA_ELE_PATH . 'widgets/ph-li-signal/markup.html' );

$norm = static function ( $h ) {
	$h = str_replace( [ "\r\n", "\r" ], "\n", $h );
	$h = preg_replace( '/\s+/', ' ', $h );
	$h = preg_replace( '/>\s+</', '><', $h );
	return trim( (string) $h );
};

$a = $norm( $actual );
$e = $norm( $expected );

if ( $a === $e ) {
	fwrite( STDOUT, "PARITY OK\n" );
	exit( 0 );
}

fwrite( STDOUT, "PARITY DIFF\n" );
fwrite( STDOUT, 'actual len ' . strlen( $a ) . ' expected len ' . strlen( $e ) . "\n" );
$min = min( strlen( $a ), strlen( $e ) );
for ( $i = 0; $i < $min; $i++ ) {
	if ( $a[ $i ] !== $e[ $i ] ) {
		fwrite( STDOUT, "first diff at {$i}\n" );
		fwrite( STDOUT, 'ctx a: ' . substr( $a, max( 0, $i - 40 ), 120 ) . "\n" );
		fwrite( STDOUT, 'ctx e: ' . substr( $e, max( 0, $i - 40 ), 120 ) . "\n" );
		break;
	}
}
exit( 1 );
