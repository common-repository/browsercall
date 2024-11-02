<?php
class BrowserCall_Configurator {
	public static $addedScripts, $addedData;
	public function __construct() {
		self::$addedScripts = false;
		self::$addedData = false;

		/*	import web interface inside frontend */
			add_filter('wp_head', array($this, 'addInterfaceData'));
			add_filter('wp_enqueue_scripts', array($this, 'requestInterface'));
			add_filter('admin_enqueue_scripts', array($this, 'requestAdminInterface'));

		/*	add administration menu */
			if(is_admin())
			{
				add_action('admin_menu', array($this, 'buildAdminNavi'));
				add_action('admin_init', array($this, 'registerSettings'));
			}
	}

	public function buildAdminNavi() {
		add_menu_page('BrowserCall Settings', 'BrowserCall', 'administrator', __FILE__, array($this,'buildAdminPanel'),null);
	}

	public function buildAdminPanel() {
		?>
		<div class="wrap">
		<h2>Tevox &raquo; BrowserCall Settings</h2>
		<p>Geben Sie nachfolgend Ihre Daten an um das Plugin entsprechend zu konfigurieren.</p>
		<form method="post" action="options.php">
		    <?php settings_fields( 'settings_browsercall' ); ?>
		    <?php do_settings_sections( 'settings_browsercall' ); ?>
		    <table class="form-table">
		        <tr valign="top">
		        <th scope="row">Data-Token</th>
		        <td><input type="text" name="browsercall_token" value="<?php echo esc_attr( get_option('browsercall_token') ); ?>" /></td>
		        </tr>

		        <tr valign="top">
			        <th scope="row">Position</th>
			        <td><select name="browsercall_position">
			        		<?php
			        			$directions = array(
			        				'top' => 'Oben',
			        				'right' => 'Rechts',
			        				'bottom' => 'Unten',
			        				'left' => 'Links'
			        			);
			        			foreach($directions as $key => $dir) {
			        				echo sprintf(
			        					'<option value="%s"%s>%s</option>',
			        					$key,
			        					(esc_attr( get_option('browsercall_position') ) == $key) ? ' selected="selected"' : '',
			        					$dir
			        				);
			        			}
			        		?>
			        </select></td>
		        </tr>
		    </table>

		    <?php submit_button(); ?>

		</form>
		</div>
		<?php
	}

	public function registerSettings() {
		register_setting( 'settings_browsercall', 'browsercall_token' );
		register_setting( 'settings_browsercall', 'browsercall_position' );
	}

	function addInterfaceData()
	{
	}

	function requestAdminInterface()
	{
	}

	/**
	 *	add interface specific files to current service
	 */
	function requestInterface()
	{
		if(self::$addedScripts) return;
		?>
			<script src="https://portal.tevox.com/portal/public/js/libs/tenios-click2call.js" id="tenios-c2c-widget" data-token="<?php echo esc_attr( get_option('browsercall_token')); ?>" data-widget-domain="https://portal.tevox.com" data-position="<?php echo esc_attr(get_option('browsercall_position')); ?>"></script>
		<?php

		self::$addedScripts = true;
	}
}