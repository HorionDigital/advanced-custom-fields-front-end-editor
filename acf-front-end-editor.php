<?php

/**
 * @link              http://www.horiondigital.com
 * @since             1.0.0
 * @package           Acf_Front_End_Editor
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Front End Editor
 * Plugin URI:        https://github.com/HorionDigital/advanced-custom-fields-front-end-editor
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           2.0.2
 * Author:            Horion Digital
 * Author URI:        http://www.horiondigital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       acf-front-end-editor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-acf-front-end-editor-activator.php
 */
function activate_acf_front_end_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acf-front-end-editor-activator.php';
	Acf_Front_End_Editor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-acf-front-end-editor-deactivator.php
 */
function deactivate_acf_front_end_editor() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-acf-front-end-editor-deactivator.php';
	Acf_Front_End_Editor_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_acf_front_end_editor' );
register_deactivation_hook( __FILE__, 'deactivate_acf_front_end_editor' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-acf-front-end-editor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_acf_front_end_editor() {

	$plugin = new Acf_Front_End_Editor();
	$plugin->run();

}
run_acf_front_end_editor();
