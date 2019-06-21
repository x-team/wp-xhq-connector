<?php
/**
 * Register plugin settings.
 *
 * @package   XHQConnector
 * @copyright Copyright(c) 2019, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace XHQConnector;

/**
 * Convert structurer array into GraphQL query.
 *
 * @param array $array The array to convert into a query.
 *
 * @return array|string
 */
function convert_to_query( $array ) {
	if ( is_array( $array ) ) {
		$query = '';
		foreach ( $array as $key => $val ) {
			$value  = convert_to_query( $val );
			$query .= is_int( $key ) ? $value . '\n' : $key . '{' . $value . '}\n';
		}
		return $query;
	} else {
		return $array;
	}
}
