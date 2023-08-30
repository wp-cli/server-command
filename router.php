<?php
// Used by `wp server` to route requests.

namespace WP_CLI\Router;

/**
 * This is a copy of WordPress's add_filter() function.
 *
 * We duplicate it because WordPress is not loaded yet.
 */
function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
	global $wp_filter, $merged_filters;

	$idx = _wp_filter_build_unique_id( $tag, $function_to_add, $priority );

	// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	$wp_filter[ $tag ][ $priority ][ $idx ] = array(
		'function'      => $function_to_add,
		'accepted_args' => $accepted_args,
	);
	unset( $merged_filters[ $tag ] );
	return true;
}

/**
 * This is a copy of WordPress's _wp_filter_build_unique_id() function.
 *
 * We duplicate it because WordPress is not loaded yet.
 */
function _wp_filter_build_unique_id( $tag, $callback, $priority ) {
	global $wp_filter;
	static $filter_id_count = 0;

	if ( is_string( $callback ) ) {
		return $callback;
	}

	if ( is_object( $callback ) ) {
		// Closures are currently implemented as objects
		$callback = array( $callback, '' );
	} else {
		$callback = (array) $callback;
	}

	if ( is_object( $callback[0] ) ) {
		// Object Class Calling
		if ( function_exists( 'spl_object_hash' ) ) {
			return spl_object_hash( $callback[0] ) . $callback[1];
		} else {
			$obj_idx = get_class( $callback[0] ) . $callback[1];
			if ( ! isset( $callback[0]->wp_filter_id ) ) {
				if ( false === $priority ) {
					return false;
				}
				$obj_idx .= isset( $wp_filter[ $tag ][ $priority ] )
					? count( (array) $wp_filter[ $tag ][ $priority ] )
					: $filter_id_count;

				$callback[0]->wp_filter_id = $filter_id_count;
				++$filter_id_count;
			} else {
				$obj_idx .= $callback[0]->wp_filter_id;
			}

			return $obj_idx;
		}
	} elseif ( is_string( $callback[0] ) ) {
		// Static Calling
		return $callback[0] . '::' . $callback[1];
	}
}

function _get_full_host( $url ) {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
	$parsed_url = parse_url( $url );

	$host = $parsed_url['host'];
	if ( isset( $parsed_url['port'] ) && 80 !== $parsed_url['port'] ) {
		$host .= ':' . $parsed_url['port'];
	}

	return $host;
}

// We need to trick WordPress into using the URL set by `wp server`, especially on multisite.
add_filter(
	'option_home',
	function ( $url ) {
		$GLOBALS['wpcli_server_original_url'] = $url;

		return 'http://' . $_SERVER['HTTP_HOST'];
	},
	20
);

add_filter(
	'option_siteurl',
	function ( $url ) {
		if ( ! isset( $GLOBALS['wpcli_server_original_url'] ) ) {
			get_option( 'home' );  // trigger the option_home filter
		}

		$home_url_host = _get_full_host( $GLOBALS['wpcli_server_original_url'] );
		$site_url_host = _get_full_host( $url );

		if ( $site_url_host === $home_url_host ) {
			$url = str_replace( $site_url_host, $_SERVER['HTTP_HOST'], $url );
		}

		return $url;
	},
	20
);

$_SERVER['SERVER_ADDR'] = gethostbyname( $_SERVER['SERVER_NAME'] );
$wpcli_server_root      = $_SERVER['DOCUMENT_ROOT'];
// phpcs:ignore WordPress.WP.AlternativeFunctions.parse_url_parse_url
$wpcli_server_path = '/' . ltrim( parse_url( urldecode( $_SERVER['REQUEST_URI'] ) )['path'], '/' );

if ( file_exists( $wpcli_server_root . $wpcli_server_path ) ) {
	if ( is_dir( $wpcli_server_root . $wpcli_server_path ) && substr( $wpcli_server_path, -1 ) !== '/' ) {
		header( "Location: $wpcli_server_path/" );
		exit;
	}

	if ( strpos( $wpcli_server_path, '.php' ) !== false ) {
		chdir( dirname( $wpcli_server_root . $wpcli_server_path ) );
		require_once $wpcli_server_root . $wpcli_server_path;
	} else {
		return false;
	}
} else {
	chdir( $wpcli_server_root );
	require_once 'index.php';
}
