<?php
/**
 * XHQ Connector
 *
 * @package   XHQConnector
 * @copyright Copyright(c) 2019, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 *
 * Plugin Name: XHQ Connector
 * Plugin URI: https://github.com/rheinardkorf/wp-xhq-connector
 * Description: Connector plugin for XHQ.com
 * Version: 0.1
 * Author: Rheinard Korf
 * Author URI: https://github.com/rheinardkorf
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: xhq-connector
 * Domain Path: /languages
 */

if ( version_compare( phpversion(), '5.3', '>=' ) ) {

	define( 'XHQ_CONNECTOR_URL', plugin_dir_url( __FILE__ ) );
	define( 'XHQ_CONNECTOR_DIR', plugin_dir_path( __FILE__ ) );

	// Bootstrap the plugin.
	include __DIR__ . '/lib/bootstrap.php';
} else {
	if ( defined( 'WP_CLI' ) ) {
		WP_CLI::warning( xhq_connector_php_version_text() );
	} else {
		add_action( 'admin_notices', 'xhq_connector_php_version_error' );
	}
}

/**
 * Admin notice for incompatible versions of PHP.
 */
function xhq_connector_php_version_error() {
	printf( '<div class="error"><p>%s</p></div>', esc_html( xhq_connector_php_version_text() ) );
}

/**
 * String describing the minimum PHP version.
 *
 * @return string
 */
function xhq_connector_php_version_text() {
	return __( 'WP Middleware plugin error: Your version of PHP is too old to run this plugin. You must be running PHP 5.3 or higher.', 'xhq-connector' );
}
