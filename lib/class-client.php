<?php
/**
 * XHQ Client.
 *
 * @package   XHQConnector
 * @copyright Copyright(c) 2019, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace XHQConnector;

/**
 * XHQ Client
 */
class Client {

	/**
	 * Endpoint for API.
	 *
	 * @var string
	 */
	private $endpoint;

	/**
	 * XHQ community to target.
	 *
	 * @var string
	 */
	private $community;

	/**
	 * XHQ access key.
	 *
	 * @var string
	 */
	private $api_key;

	/**
	 * XHQ access secret.
	 *
	 * @var string
	 */
	private $api_secret;

	/**
	 * XHQ client token.
	 *
	 * @var string
	 */
	private $token;

	/**
	 * Constructor.
	 *
	 * @param array $args API Client arguments.
	 */
	function __construct( $args = [
		'endpoint'   => '',
		'api_key'    => '',
		'api_secret' => '',
		'community'  => '',
		'token'      => '',
		'refresh'    => false,
	] ) {
		$this->endpoint   = $args['endpoint'];
		$this->community  = $args['community'];
		$this->api_key    = $args['api_key'];
		$this->api_secret = $args['api_secret'];
		$refresh          = $args['refresh'] || empty( $args['token'] );
		$this->token      = $this->get_token( $refresh );
	}

	/**
	 * Get the XHQ token for this client.
	 *
	 * @param boolean $refresh Refresh token.
	 *
	 * @return string|WP_Error
	 */
	public function get_token( $refresh = false ) {

		if ( ! $refresh && ! empty( $this->token ) && ! is_wp_error( $this->token ) ) {
			return $this->token;
		}

		$bool_string = $refresh ? 'true' : 'false';

		$query = '{"query":"mutation {obtainApiClientToken(accessKey: \"' . $this->api_key . '\", secretKey: \"' . $this->api_secret . '\", refresh: ' . $bool_string . ') {token}}"}';
		$args  = [
			'headers' => [
				'Authorization'   => $this->community,
				'Content-Type'    => 'application/json',
				'X-XHQ-Community' => $this->community,
			],
			'body'    => $query,
		];

		$response = wp_safe_remote_post( $this->endpoint, $args );

		if ( ! is_wp_error( $response ) ) {
			$raw        = json_decode( $response['body'], true );
			$json_error = json_last_error();
			if ( JSON_ERROR_NONE === $json_error &&
				isset( $raw['data'] ) &&
				isset( $raw['data']['obtainApiClientToken'] ) &&
				isset( $raw['data']['obtainApiClientToken']['token'] ) ) {
					return $raw['data']['obtainApiClientToken']['token'];
			}
		}

		return $response;
	}

	/**
	 * Executes a query on the client.
	 *
	 * @param Array $query An array representing a GraphQL query.
	 *
	 * @example
	 *   [
	 *       'query' => [
	 *           'channels(limit:1)' => [
	 *               'items' => [
	 *                   'id',
	 *                   'name',
	 *               ],
	 *           ],
	 *       ],
	 *   ]
	 *
	 * @return Array|WP_Error
	 */
	public function query( $query ) {

		$query = convert_to_query( $query );

		$args = [
			'headers' => [
				'Authorization' => $this->token,
				'Content-Type'  => 'application/json',
			],
			'body'    => '{"query":"' . $query . '"}',
		];

		$response = wp_safe_remote_post( $this->endpoint, $args );
		return $response;
	}
}
