<?php
/**
 * A simple demonstration of using the client.
 *
 * @package XHQConnector
 */

// Use the XHQConnector\Client.
use XHQConnector\Client;

add_action(
	'admin_init', function() {

		if ( 'xhq-connector' !== $_REQUEST['page'] ) {
			return;
		}

		// Use get_client_args() utility function from XHQConnector.
		$xhq = new Client( XHQConnector\get_client_args() );

		$query = [
			'query' => [
				'channels' => [
					'items' => [
						'id',
						'name',
					],
				],
				'members'  => [
					'items' => [
						'id',
						'userName',
					],
				],
			],
		];

		$response            = $xhq->query( $query );
		$_REQUEST['example'] = $response['body'];
	}
);
