<?php
/**
 * Bootstraps the plugin.
 *
 * @package   XHQConnector
 * @copyright Copyright(c) 2019, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

namespace XHQConnector;

// Get utilities.
include __DIR__ . '/utils.php';

// Plugin Settings.
include __DIR__ . '/settings.php';

// Register Client.
include __DIR__ . '/class-client.php';

// Example usage.
// include __DIR__ . '/example-usage.php';
