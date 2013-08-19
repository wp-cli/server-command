<?php

class Server_Command extends WP_CLI_Command {

	/**
	 * Start a development server.
	 *
	 * ## OPTIONS
	 *
	 * --host=<host>
	 * : The hostname to bind the server to. Default: localhost
	 *
	 * --port=<port>
	 * : The port number to bind the server to. Default: 8080
	 *
	 * ## EXAMPLES
	 *
	 *     # Make the instance available on any address (with port 8080)
	 *     wp server --host=0.0.0.0
	 *
	 *     # Run on port 80 (for multisite)
	 *     sudo wp server --host=localhost.localdomain --port=80
	 *
	 * @when before_wp_load
	 * @synopsis [--host=<host>] [--port=<port>]
	 */
	function __invoke( $_, $assoc_args ) {
		$defaults = array(
			'host' => 'localhost',
			'port' => 8080
		);
		$assoc_args = array_merge( $defaults, $assoc_args );

		$cmd = \WP_CLI\Utils\esc_cmd( PHP_BINARY . ' -S %s %s',
			$assoc_args['host'] . ':' . $assoc_args['port'],
			__DIR__ . '/router.php'
		);

		$config_path = WP_CLI::get_runner()->config_path;

		if ( false === $config_path ) {
			$docroot = ABSPATH;
		} else {
			$docroot = dirname( $config_path );
		}

		chdir( $docroot );

		WP_CLI::launch( $cmd );
	}
}

WP_CLI::add_command( 'server', 'Server_Command' );

