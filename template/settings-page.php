<?php
/**
 * Settings Page Template.
 *
 * @package   XHQConnector
 * @copyright Copyright(c) 2019, Rheinard Korf
 * @licence http://opensource.org/licenses/GPL-2.0 GNU General Public License, version 2 (GPL-2.0)
 */

?>

<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<hr />
	<?php if ( $_REQUEST['settings-updated'] && $_REQUEST['xhq-auth-failed'] ) : ?>
	<div class="notice notice-error">
		<p><?php _e( 'XHQ cannot authenticate.', 'xhq-connector' ); ?></p>
	</div>
	<?php elseif ( $_REQUEST['settings-updated'] ) : ?>
	<div class="notice notice-success">
		<p><?php _e( 'XHQ settings updated.', 'xhq-connector' ); ?></p>
	</div>
	<?php endif; ?>
	<form method="post" action="options.php">
		<?php settings_fields( 'xhq_connector_settings' ); ?>
		<?php do_settings_sections( 'xhq-connector' ); ?>
		<?php submit_button(); ?>
	</form>
	<p><em>Debugging</em></p>
	<code>
		<?php print_r( $_REQUEST ); ?>
	</code>
</div>
