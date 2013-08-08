<?php

class Server_Command extends WP_CLI_Command {

	/**
	 * Start a development server.
	 *
	 * @when before_wp_load
	 * @synopsis [--host=<host>] [--port=<port>]
	 */
	function __invoke( $_, $assoc_args ) {
		if ( version_compare( PHP_VERSION, '5.4', '<' ) ) {
			WP_CLI::error( 'PHP 5.4 or newer is needed.' );
		}

		$defaults = array(
			'host' => 'localhost',
			'port' => 8080
		);
		$assoc_args = array_merge( $defaults, $assoc_args );

		$cmd = \WP_CLI\Utils\esc_cmd( PHP_BINARY . ' -S %s %s',
			$assoc_args['host'] . ':' . $assoc_args['port'],
			__DIR__ . '/router.php'
		);

		WP_CLI::launch( $cmd );
	}
}

WP_CLI::add_command( 'server', 'Server_Command' );

