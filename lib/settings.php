<?php
/**
 * Register plugin settings.
 *
 * @package   XHQConnector
 * @copyright Copyright(c) 2019, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace XHQConnector;

add_action( 'admin_menu', '\XHQConnector\admin_menu' );
add_action( 'admin_init', '\XHQConnector\init_settings' );
add_action( 'admin_init', '\XHQConnector\attempt_auth' );

/**
 * Register settings pages.
 *
 * @return void
 */
function admin_menu() {
	add_menu_page(
		__( 'XHQ Connector', 'xhq-connector' ),
		__( 'XHQ Connector', 'xhq-connector' ),
		'manage_options',
		'xhq-connector',
		'\XHQConnector\admin_menu_page',
		'',
		100
	);
}

/**
 * Attempt authentication.
 *
 * Saving settings again forces an update of the token.
 *
 * @return void
 */
function attempt_auth() {
	if ( $_REQUEST['settings-updated'] && 'xhq-connector' === $_REQUEST['page'] ) {

		$args            = get_client_args();
		$args['refresh'] = true;
		$xhq             = new Client( $args );
		$token           = $xhq->get_token();

		if ( ! is_wp_error( $token ) ) {
			$settings          = get_option( 'xhq_connector_settings' );
			$settings['token'] = $token;
			update_option( 'xhq_connector_settings', $settings );
			$_REQUEST['xhq'] = $settings;
		} else {
			$_REQUEST['xhq-auth-failed'] = true;
		}
	}
}

/**
 * Get the API Client credentials and settings.
 *
 * @return array Client values.
 */
function get_client_args() {
	$settings = get_option( 'xhq_connector_settings' );
	return [
		'endpoint'   => isset( $settings['endpoint'] ) ? esc_url_raw( $settings['endpoint'] ) : '',
		'api_key'    => isset( $settings['api_key'] ) ? sanitize_text_field( $settings['api_key'] ) : '',
		'api_secret' => isset( $settings['api_secret'] ) ? sanitize_text_field( $settings['api_secret'] ) : '',
		'community'  => isset( $settings['community'] ) ? sanitize_text_field( $settings['community'] ) : '',
		'token'      => isset( $settings['token'] ) ? sanitize_text_field( $settings['token'] ) : '',
		'refresh'    => false,
	];
}

/**
 * Settings Page.
 *
 * @return void
 */
function admin_menu_page() {

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	include XHQ_CONNECTOR_DIR . 'template/settings-page.php';
}

/**
 * Init settings.
 *
 * @return void
 */
function init_settings() {

	if ( ! get_option( 'xhq_connector_settings' ) ) {
		add_option( 'xhq_connector_settings' );
	}

	add_settings_section(
		'xhq-connector-settings-section',
		__( 'General Settings', 'xhq-connector' ),
		'\XHQConnector\settings_header',
		'xhq-connector'
	);

	add_settings_field(
		'endpoint',
		__( 'XHQ Endpoint', 'wpplugin' ),
		'\XHQConnector\endpoint_callback',
		'xhq-connector',
		'xhq-connector-settings-section'
	);

	add_settings_field(
		'community',
		__( 'XHQ Community', 'wpplugin' ),
		'\XHQConnector\community_callback',
		'xhq-connector',
		'xhq-connector-settings-section'
	);

	add_settings_field(
		'api_key',
		__( 'XHQ Access Key', 'wpplugin' ),
		'\XHQConnector\api_key_callback',
		'xhq-connector',
		'xhq-connector-settings-section'
	);

	add_settings_field(
		'api_secret',
		__( 'XHQ Secret Key', 'wpplugin' ),
		'\XHQConnector\api_secret_callback',
		'xhq-connector',
		'xhq-connector-settings-section'
	);

	register_setting(
		'xhq_connector_settings',
		'xhq_connector_settings'
	);
}

/**
 * Settings header.
 *
 * @return void
 */
function settings_header() {
	?>
	<?php echo wp_kses_post( __( 'This plugin requires API credentials for <strong><a href="https://xhq.com">XHQ</a></strong>.', 'xhq-connector' ) ); ?>
		<ol>
			<li><?php echo wp_kses_post( __( 'Use the XHQ API credentials given to.', 'xhq-connector' ) ); ?></li>
		</ol>
	<?php
}

/**
 * XHQ endpoint.
 *
 * @return void
 */
function endpoint_callback() {

	$options = get_option( 'xhq_connector_settings' );

	$endpoint = '';
	if ( isset( $options['endpoint'] ) ) {
		$endpoint = esc_url_raw( $options['endpoint'] );
	}
	echo '<input type="text" id="endpoint" name="xhq_connector_settings[endpoint]" value="' . $endpoint . '" />';
}

/**
 * XHQ community.
 *
 * @return void
 */
function community_callback() {

	$options = get_option( 'xhq_connector_settings' );

	$community = '';
	if ( isset( $options['community'] ) ) {
		$community = esc_html( $options['community'] );
	}
	echo '<input type="text" id="community" name="xhq_connector_settings[community]" value="' . $community . '" />';
}

/**
 * API key field.
 *
 * @return void
 */
function api_key_callback() {

	$options = get_option( 'xhq_connector_settings' );

	$api_key = '';
	if ( isset( $options['api_key'] ) ) {
		$api_key = esc_html( $options['api_key'] );
	}
	echo '<input type="text" id="api_key" name="xhq_connector_settings[api_key]" value="' . $api_key . '" />';
}

/**
 * API secret field.
 *
 * @return void
 */
function api_secret_callback() {

	$options = get_option( 'xhq_connector_settings' );

	$api_secret = '';
	if ( isset( $options['api_secret'] ) ) {
		$api_secret = esc_html( $options['api_secret'] );
	}
	echo '<input type="text" id="api_secret" name="xhq_connector_settings[api_secret]" value="' . $api_secret . '" />';
}
