<?php // Silence is golden

defined( 'WPINC' ) || die;

$body = sprintf('%s/body.php', dirname( __FILE__, 1 ));
$wrap = sprintf('%s/parts/index.php', untrailingslashit( dirname( __FILE__, 2 ) ) );

include $wrap;