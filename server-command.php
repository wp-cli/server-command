<?php

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

$wpcli_server_autoloader = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $wpcli_server_autoloader ) ) {
	require_once $wpcli_server_autoloader;
}

WP_CLI::add_command( 'server', 'Server_Command' );
