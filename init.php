<?php
/*
 * Plugin Name:       Web:Mi Speed Up 
 * Plugin URI:        https://github.com/trueqap/Webmi-speed-up
 * Description:       WordPress Speed Up package
 * Version:           0.0.6
 * Author:            Laszlo Patai
 * Author URI:        https://webmi.io
 * Text Domain:       webmi-speed-up
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */
 
if ( ! defined( 'WPINC' ) ) {
    die;
}
 
require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-loader.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/updater/plugin-update-checker.php';


$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/trueqap/WebMi-Speed-Up/',
	__FILE__,
	'webmi-speed-up'
);
 
function run_plugin_loader() {
 
    $wsu = new WebMi_Speed_Up();
   	return $wsu;
 
}
 
run_plugin_loader();