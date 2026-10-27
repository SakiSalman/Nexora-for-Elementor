<?php
$root  = dirname( __DIR__ );
$src   = $root . '/dist/1-design-reference/index.html';
$dest  = $root . '/widgets/ph-li-signal/markup.html';
$c     = file_get_contents( $src );
$start = strpos( $c, '<!-- LI4 SIGNAL-BASED OUTREACH (signal radar) -->' );
$end   = strpos( $c, '<!-- LI6 B2B GROWTH -->' );
if ( false === $start || false === $end || $end <= $start ) {
	fwrite( STDERR, "markers not found\n" );
	exit( 1 );
}
$slice = rtrim( substr( $c, $start, $end - $start ), "\r\n " ) . "\n";
file_put_contents( $dest, $slice );
$i = strpos( $slice, 'class=check>' );
echo 'wrote ' . strlen( $slice ) . ' bytes; check hex=' . bin2hex( substr( $slice, $i + 11, 3 ) ) . PHP_EOL;
