<?php

/**
 * Fired during plugin activation
 *
 * @link       https://dev.ilyasine.com/
 * @since      1.0.0
 *
 * @package    Ikacom_Api
 * @subpackage Ikacom_Api/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Ikacom_Api
 * @subpackage Ikacom_Api/includes
 * @author     Yassine Idrissi <ydrissi9@gmail.com>
 */
class Ikacom_Api_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		add_filter('wsfw_check_pro_plugin', array('Ikacom_Api_Activator', 'activate_wallet_system_plugin'));
	}

	public function activate_wallet_system_plugin($is_pro_plugin){
		$is_pro_plugin = true;
		return $is_pro_plugin;
	}

}
