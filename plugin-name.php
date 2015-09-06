<?php

/**
 * The plugin bootstrap file
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           Plugin_Name
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Front End Editor
 * Plugin URI:        http://example.com/plugin-name-uri/
 * Description:       Plugin allows to seamlesly edit acf field values from front end
 * Version:           1.0.0
 * Author:            Horion DIgital
 * Author URI:        http://horiondigital.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-activator.php';
	Plugin_Name_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name-deactivator.php';
	Plugin_Name_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plugin-name.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_plugin_name() {

	$plugin = new Plugin_Name();
	$plugin->run();

}
run_plugin_name();


function acf_targeter( $value, $post_id, $field ) {
	if(is_user_logged_in()):
		if(strpos($value, 'http') === 0 || $value == '#' || $value == '' || filter_var($value, FILTER_VALIDATE_EMAIL) || is_admin()) {
    		$value = $value;
    	} else {
    		$key=$field['key'];
    		$label=$field['name'];
    		$type = 'labas';
    		$value = '<d contenteditable data-postid="'.$post_id.'" data-name="'.$label.'" data-key="'.$field['key'].'">'.$value.'</d>';
    	}
	endif;
    return $value;
}
add_filter('acf/load_value/type=text', 'acf_targeter', 10, 3);
add_filter('acf/load_value/type=textarea', 'acf_targeter', 10, 3);

function acf_wysiwyg_targeter( $value, $post_id, $field ) {
    if(is_user_logged_in() && !is_admin()):
            $key=$field['key'];
            $label=$field['name'];
            $value = '<div contenteditable class="editableHD" data-postid="'.$post_id.'" data-name="'.$label.'" data-key="'.$field['key'].'"><p></p>'.$value.'</div>';
    endif;
    return $value;
}
add_filter('acf/load_value/type=wysiwyg', 'acf_wysiwyg_targeter', 10, 3);

// function acf_wysiwyg_targeter($content) {
//     $key=$field['key'];
//     $label=$field['name'];
//   return '<d class="aaaaaaaaaaaaaa">'.$content.'</div>';
// }
// add_filter('acf_the_content', 'acf_wysiwyg_targeter');

function my_acf_format_value( $value, $post_id, $field ) {
	$value = html_entity_decode($value);
	return $value;
}

add_filter('acf/format_value/type=text', 'my_acf_format_value', 10, 3);
add_filter('acf/format_value/type=textarea', 'my_acf_format_value', 10, 3);
add_filter('acf/format_value/type=wysiwyg', 'my_acf_format_value', 10, 3);

add_action('wp_ajax_nopriv_update_positions', 'update_texts');
add_action('wp_ajax_update_texts', 'update_texts');

function update_texts() {

    if ( isset($_REQUEST) ) {
    	if(is_user_logged_in()):

        $siteID   = $_REQUEST['siteID'];
        $textArr  = $_REQUEST['textArr'];

        foreach ($textArr as $arr):
        	$key  = $arr[0];
        	$text = $arr[1];
        	$name = $arr[2];
            $postid = $arr[3];
        	$obj = get_field_object($name,$postid);
        	$type = ($obj['key'] ? 'single' : 'repeater');
        	$acf_post = get_post( $obj['parent'] );
        	update_field($name, $text, $postid);
        endforeach;
       	endif;
    }
    die();
}